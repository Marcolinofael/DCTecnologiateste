@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')


@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Cadastro de Clientes</h3>
        </div>
        <div class="card-body">
            <div class=" form-group">

                @if (isset($costumer->id))
                    <form method="post" action="{{ route('costumer.update', ['costumer' => $costumer->id]) }}">
                        @csrf
                        @method('PUT')
                    @else
                        <form method="post" action="{{ route('costumer.store') }}">
                            @csrf
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <label for="name">Nome do Cliente</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder=""
                            value="{{ $costumer->name ?? old('name') }}">
                        @if ($errors->has('name'))
                            <span style="color: red;">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                        <br>
                    </div>
                    <div class="col-md-3">
                        <label for="cpf">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder=""
                            value="{{ $costumer->cpf ?? old('cpf') }}">
                        @if ($errors->has('cpf'))
                            <span style="color: red;">
                                {{ $errors->first('cpf') }}
                            </span>
                        @endif
                        <br>
                    </div>
                    <div class="col-md-3">
                        <label for="phone">Telefone para Contato</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder=""
                            value="{{ $costumer->phone ?? old('phone') }}">
                        @if ($errors->has('phone'))
                            <span style="color: red;">
                                {{ $errors->first('phone') }}
                            </span>
                        @endif
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="email">Email Válido</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder=""
                            value="{{ $costumer->email ?? old('email') }}">
                        @if ($errors->has('email'))
                            <span style="color: red;">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                        <br>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <a href="{{ route('costumer.index') }}" type="button" class="btn btn-secondary">Voltar</a>
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

            <script>
                f

                $(document).ready(function() {



                });
            </script>
        @stop
