<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Product::latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $actionBtns = '
                        <a href="' . route("product.edit", $row->id) . '" class="btn btn-outline-info btn-sm"><i class="fas fa-pen"></i></a>

                        <form action="' . route("product.destroy", $row->id) . '" method="POST" style="display:inline" onsubmit="return confirm(\'Deseja realmente excluir este registro?\')">
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

        return view('product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.crud');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $user = Auth::user();

        $product_name = $request->post('product_name');
        $price = $request->post('price');
        $stock_quantity = $request->post('stock_quantity');
        $description = $request->post('description');

        $prod = new Product();

        $prod->product_name = $product_name;
        $prod->price = $price;
        $prod->stock_quantity = $stock_quantity;
        $prod->description = $description;
        $prod->origin_user = $user->name;
        $prod->last_user = $user->name;
        $prod->save();

        return view('product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);

        $output = array(
            'product' => $product,
        );

        return view('product.crud', $output);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = Auth::user();

        $product_name = $request->post('product_name');
        $price = $request->post('price');
        $stock_quantity = $request->post('stock_quantity');
        $description = $request->post('description');

        $prod = Product::find($id);
        $prod->product_name = $product_name;
        $prod->price = $price;
        $prod->stock_quantity = $stock_quantity;
        $prod->description = $description;
        $prod->last_user = $user->name;
        $prod->update();

        return view('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $edit = Product::find($id);
        $edit->delete();

        return view('product.index');
    }
}
