<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Costumer;
use App\Models\Installment;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use App\Models\Conditionspayments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    const PAYMENT_METHODS = [
        'dinheiro' => 'Dinheiro',
        'cartao_credito' => 'Cartão de Crédito',
        'cartao_debito' => 'Cartão de Débito',
        'pix' => 'PIX',
        'boleto' => 'Boleto',
        'parcelado' => 'Personalizado'
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::latest()->get();
            return DataTables::of($data)
                ->addColumn('customer_name', function ($row) {
                    return $row->costumer ? $row->costumer->name : 'Cliente não informado';
                })
                ->addColumn('products_count', function ($row) {
                    return $row->products->count() . ' item(s)';
                })
                ->addColumn('formatted_total', function ($row) {
                    return 'R$ ' . number_format($row->total_amount, 2, ',', '.');
                })
                ->addColumn('formatted_date', function ($row) {
                    return $row->sale_date->format('d/m/Y');
                })
                ->addColumn('payment_method_label', function ($row) {
                    return self::PAYMENT_METHODS[$row->payment_method] ?? $row->payment_method;
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : 'Desconhecido';
                })
                ->addColumn('action', function ($row) {
                    $actionBtns = '
                    <a href="' . route('sale.pdf', $row->id) . '" class="btn btn-outline-dark btn-sm" target="_blank" title="PDF"><i class="fas fa-file-pdf"></i></a>
                    <a href="' . route("sale.edit", $row->id) . '" class="btn btn-outline-info btn-sm"><i class="fas fa-pen"></i></a>
                    <form action="' . route("sale.destroy", $row->id) . '" method="POST" style="display:inline" onsubmit="return confirm(\'Deseja realmente excluir este registro?\')">
                        ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <button type="submit" class="btn btn-outline-danger btn-sm ml-2")><i class="fas fa-trash"></i></button>
                        </form>
                        
                    ';
                    return $actionBtns;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('sale.index');
    }

    public function create()
    {
        $products = Product::all();
        $customers = Costumer::all();
        $paymentMethods = self::PAYMENT_METHODS;

        return view('sale.crud')
            ->with('products', $products)
            ->with('customers', $customers)
            ->with('paymentMethods', $paymentMethods);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'total_amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string',
                'sale_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
            ]);


            $sale = Sale::create([
                'costumer_id' => $request->costumer_id ?: null,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'sale_date' => $request->sale_date,
                'user_id' => Auth::id(),
                'origin_user' => Auth::user()->name ?? 'Sistema',
                'last_user' => Auth::user()->name ?? 'Sistema',
            ]);


            foreach ($request->products as $productData) {
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => str_replace(',', '.', $productData['price']),
                    'total_unity' => str_replace(',', '.', $productData['quantity']) * str_replace(',', '.', $productData['price']),
                ]);
            }


            if ($request->payment_method === 'parcelado' && $request->has('installments')) {
                foreach ($request->installments as $index => $installmentData) {
                    Installment::create([
                        'sale_id' => $sale->id,
                        'installment_number' => $index + 1,
                        'amount' => str_replace(',', '.', $installmentData['amount']),
                        'due_date' => $installmentData['due_date'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sale.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $sale = Sale::with(['products', 'installments'])->findOrFail($id);
        $products = Product::all();
        $customers = Costumer::all();
        $paymentMethods = self::PAYMENT_METHODS;

        return view('sale.crud', compact('sale', 'products', 'customers', 'paymentMethods'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);

            $request->validate([
                'total_amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string',
                'sale_date' => 'required|date',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
            ]);


            $sale->update([
                'costumer_id' => $request->costumer_id ?: null,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'sale_date' => $request->sale_date,
                'last_user' => Auth::user()->name ?? 'Sistema',
            ]);


            $sale->products()->delete();
            foreach ($request->products as $productData) {
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => str_replace(',', '.', $productData['price']),
                    'total_unity' => str_replace(',', '.', $productData['quantity']) * str_replace(',', '.', $productData['price']),
                ]);
            }

            $sale->installments()->delete();

            if ($request->payment_method === 'parcelado' && $request->has('installments')) {
                foreach ($request->installments as $index => $installmentData) {
                    Installment::create([
                        'sale_id' => $sale->id,
                        'installment_number' => $index + 1,
                        'amount' => str_replace(',', '.', $installmentData['amount']),
                        'due_date' => $installmentData['due_date'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sale.index')->with('success', 'Venda atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar venda: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sale = Sale::findOrFail($id);

            $sale->delete();

            return redirect()->route('sale.index')->with('success', 'Venda excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir venda: ' . $e->getMessage());
        }
    }

    public function pdf($id)
    {
        $sale = Sale::with(['costumer', 'products.product', 'installments', 'user'])->findOrFail($id);

        $pdf = PDF::loadView('sale.pdf', compact('sale'));
        return $pdf->stream('resumo-venda-' . $sale->id . '.pdf');
    }
}
