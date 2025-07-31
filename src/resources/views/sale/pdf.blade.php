<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <img src="{{ public_path('vendor/adminlte/dist/img/dcvendaslogo.png') }}" alt="Logo DC Eletrônico"
        style="height: 100px; margin-bottom: 10px;">
    <title>Resumo da Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>
    <h1>Venda {{ $sale->id }}</h1>
    <p><strong>Cliente:</strong> {{ $sale->costumer->name ?? '-' }}</p>
    <p><strong>Data:</strong> {{ $sale->sale_date ? $sale->sale_date->format('d/m/Y') : '-' }}</p>
    <p><strong>Forma de Pagamento:</strong> {{ $sale->payment_method }}</p>
    <p><strong>Vendedor:</strong> {{ $sale->user->name ?? '-' }}</p>
    <hr>
    <h4>Produtos</h4>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unitário</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->products as $item)
                <tr>
                    <td>{{ $item->product->product_name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->total_unity, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4>Total Geral: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</h4>
    @if ($sale->payment_method === 'parcelado')
        <h4>Parcelas</h4>
        <table>
            <thead>
                <tr>
                    <th>Parcela</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->installments as $i => $parcel)
                    <tr>
                        <td>{{ $i + 1 }}ª</td>
                        <td>R$ {{ number_format($parcel->amount, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($parcel->due_date)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h4>Observações</h4>
    <p>{{ $sale->observations ?? '-' }}</p>
    <h4>Data de Criação</h4>
    <p>{{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</p>
    <h4>Data de Atualização</h4>
    <p>{{ $sale->updated_at ? $sale->updated_at->format('d/m/Y H:i:s') : '-' }}</p>
</body>

</html>
