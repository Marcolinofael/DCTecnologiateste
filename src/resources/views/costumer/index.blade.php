@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Clientes</h3>
        </div>

        <div class="card-body">
            <div>
                <a href="{{ route('costumer.create') }}" type="button" class="btn btn-danger" style="width:100px;">Novo</a>
            </div>
            <br>
            <table class="table table-bordered table-striped dataTable dtr-inline" id="costumers" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th style="width: 5%">Id</th>
                        <th style="width: 30%">Cliente</th>
                        <th style="width: 20%">CPF</th>
                        <th style="width: 20%">Telefone</th>
                        <th style="width: 15%">Ações</th>
                    </tr>
                </thead>
            </table>
        </div>:

    </div>
@stop

@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css">
@stop

@section('js')

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#costumers').DataTable({
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "Resultados por página _MENU_",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('costumer.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'cpf',
                        name: 'cpf',
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@stop
