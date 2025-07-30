@extends('adminlte::page')

@section('title', 'Cadastro de Produtos')

@section('content_header')


@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Cadastro de Produtos</h3>
        </div>
        <div class="card-body">
            <div class=" form-group">

                @if (isset($product->id))
                    <form method="post" action="{{ route('product.update', ['product' => $product->id]) }}">
                        @csrf
                        @method('PUT')
                    @else
                        <form method="post" action="{{ route('product.store') }}">
                            @csrf
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <label for="product_name">Nome do Produto</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder=""
                            value="{{ $product->product_name ?? old('product_name') }}">
                        @if ($errors->has('product_name'))
                            <span style="color: red;">
                                {{ $errors->first('product_name') }}
                            </span>
                        @endif
                        <br>
                    </div>
                    <div class="col-md-3">
                        <label for="price">Valor</label>
                        <input type="text" class="form-control" id="price" name="price" placeholder=""
                            value="{{ $product->price ?? old('price') }}">
                        @if ($errors->has('price'))
                            <span style="color: red;">
                                {{ $errors->first('price') }}
                            </span>
                        @endif
                        <br>
                    </div>
                    <div class="col-md-3">
                        <label for="stock_quantity">Estoque</label>
                        <input type="text" class="form-control" id="stock_quantity" name="stock_quantity" placeholder=""
                            value="{{ $product->stock_quantity ?? old('stock_quantity') }}">
                        @if ($errors->has('stock_quantity'))
                            <span style="color: red;">
                                {{ $errors->first('stock_quantity') }}
                            </span>
                        @endif
                        <br>
                        </span>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="description">Descrição</label>
                        <textarea class="form-control" id="description" name="description" placeholder="" rows="4">{{ $product->description ?? old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span style="color: red;">
                                {{ $errors->first('description') }}
                            </span>
                        @endif
                        <br>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <a href="{{ route('product.index') }}" type="button" class="btn btn-secondary">Voltar</a>
                </div>
                </form>

            </div>
        @stop

        @section('css')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        @stop

        @section('js')
            <script src="{{ asset('vendor/jquery/jquery.maskedinput.min.js') }}"></script>
            <script src="{{ asset('vendor/jquery/jquery.maskMoney.min.js') }}"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <script src="{{ asset('vendor/jquery/jquery.maskMoney.min.js') }}"></script>
            <script>
                $(document).ready(function() {
                    $('#price').maskMoney({
                        thousands: '',
                        decimal: '.',
                        allowZero: true,
                        precision: 2,
                        affixesStay: false
                    });

                    $('#price').maskMoney('mask');
                });
            </script>
        @stop
