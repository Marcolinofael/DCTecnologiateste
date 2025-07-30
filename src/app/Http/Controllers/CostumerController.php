<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CostumerController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Costumer::latest()->get();

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $actionBtns = '
                        <a href="' . route("costumer.edit", $row->id) . '" class="btn btn-outline-info btn-sm"><i class="fas fa-pen"></i></a>
                        
                        <form action="' . route("costumer.destroy", $row->id) . '" method="POST" style="display:inline" onsubmit="return confirm(\'Deseja realmente excluir este registro?\')">
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

        return view('costumer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('costumer.crud');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);


        $user = Auth::user();

        $name = $request->post('name');
        $cpf = $request->post('cpf');
        $phone = $request->post('phone');
        $email = $request->post('email');

        $cost = new Costumer();

        $cost->name = $name;
        $cost->cpf = $cpf;
        $cost->phone = $phone;
        $cost->email = $email;
        $cost->origin_user = $user->name;
        $cost->last_user = $user->name;
        $cost->save();

        return view('costumer.index');
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
        $costumer = Costumer::find($id);

        $output = array(
            'costumer' => $costumer,
        );

        return view('costumer.crud', $output);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = Auth::user();

        $name = $request->post('name');
        $cpf = $request->post('cpf');
        $phone = $request->post('phone');
        $email = $request->post('email');

        $cost = Costumer::find($id);
        $cost->name = $name;
        $cost->cpf = $cpf;
        $cost->phone = $phone;
        $cost->email = $email;
        $cost->last_user = $user->name;
        $cost->update();

        return view('costumer.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $edit = Costumer::find($id);
        $edit->delete();

        return view('costumer.index');
    }
}
