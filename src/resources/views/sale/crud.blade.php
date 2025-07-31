@extends('adminlte::page')

@section('title', 'Vendas')

@section('content_header')
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cadastrar Nova Venda</h3>
        </div>

        <form action="{{ isset($sale) ? route('sale.update', $sale->id) : route('sale.store') }}" method="POST"
            id="sale-form">
            @csrf
            @if (isset($sale))
                @method('PUT')
            @endif
            <div class="card-body">

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="costumer_id">Cliente</label>
                            <select class="form-control" id="costumer_id" name="costumer_id" required>
                                <option value="">Selecione</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-cpf="{{ $customer->cpf }}"
                                        {{ old('costumer_id', @$sale->costumer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" class="form-control" id="customer-cpf" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sale_date">Data da Venda</label>
                            <input type="date" class="form-control" id="sale_date" name="sale_date"
                                value="{{ old('sale_date', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_method">Forma de Pagamento</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Selecione...</option>
                                @foreach ($paymentMethods as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('payment_method', @$sale->payment_method) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                <h5>Itens da Venda</h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="products-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="products-tbody">
                            @if (isset($sale) && $sale->products->count())
                                @foreach ($sale->products as $i => $product)
                                    <tr class="product-row">
                                        <td>
                                            <select class="form-control product-select"
                                                name="products[{{ $i }}][product_id]" required>
                                                <option value="">Selecione um produto</option>
                                                @foreach ($products as $p)
                                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}"
                                                        {{ $product->product_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity-input"
                                                name="products[{{ $i }}][quantity]"
                                                value="{{ $product->quantity }}" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control price-input"
                                                name="products[{{ $i }}][price]" value="{{ $product->price }}"
                                                step="0.01" min="0" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-input"
                                                value="{{ $product->total_unity }}" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-product"
                                                {{ $loop->count == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="product-row">
                                    <td>
                                        <select class="form-control product-select" name="products[0][product_id]" required>
                                            <option value="">Selecione um produto</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity-input"
                                            name="products[0][quantity]" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control price-input" name="products[0][price]"
                                            step="0.01" min="0" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control total-input" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-product" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success" id="add-product">
                            <i class="fas fa-plus"></i> Adicionar Produto
                        </button>
                    </div>
                    <div class="col-md-6 text-right">
                        <h4>Total Geral: R$ <span
                                id="grand-total">{{ number_format(old('total_amount', @$sale->total_amount ?? 0), 2, ',', '.') }}</span>
                        </h4>
                        <input type="hidden" id="total_amount" name="total_amount"
                            value="{{ old('total_amount', @$sale->total_amount ?? 0) }}">
                    </div>
                </div>

                <hr>

                <div id="installments-section"
                    @if (isset($sale) && $sale->payment_method === 'parcelado') style="display: block;" @else style="display: none;" @endif>
                    <div class="form-group col-md-2">
                        <label for="installments_count">Quantidade de Parcelas</label>
                        <input type="number" min="1" class="form-control" id="installments_count"
                            value="1">
                    </div>
                    <h5>Parcelas</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="installments-table">
                            <thead>
                                <tr>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Data de Vencimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="installments-tbody">
                                @if (isset($sale) && $sale->installments->count())
                                    @foreach ($sale->installments as $i => $installment)
                                        <tr class="installment-row">
                                            <td>{{ $i + 1 }}ª</td>
                                            <td>
                                                <input type="text" class="form-control"
                                                    name="installments[{{ $i }}][amount]"
                                                    value="{{ $installment->amount }}" required>
                                            </td>
                                            <td>
                                                <input type="date" class="form-control"
                                                    name="installments[{{ $i }}][due_date]"
                                                    value="{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}"
                                                    store required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-installment">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar Venda</button>
                <a href="{{ route('sale.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {

            if ($('#payment_method').val() === 'parcelado') {
                $('#installments-section').show();
            }
            let productIndex = 1;
            let installmentIndex = 0;

            $('#add-product').click(function() {
                const newRow = `
            <tr class="product-row">
                <td>
                    <select class="form-control product-select" name="products[${productIndex}][product_id]" required>
                        <option value="">Selecione um produto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control quantity-input" name="products[${productIndex}][quantity]" 
                           min="1" value="1" required>
                </td>
                <td>
                    <input type="number" class="form-control price-input" name="products[${productIndex}][price]" 
                           step="0.01" min="0" required>
                </td>
                <td>
                    <input type="number" class="form-control total-input" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
                $('#products-tbody').append(newRow);
                productIndex++;
                updateRemoveButtons();
            });

            // Remover produto
            $(document).on('click', '.remove-product', function() {
                $(this).closest('tr').remove();
                updateRemoveButtons();
                calculateGrandTotal();
            });

            // Atualizar preço quando produto é selecionado
            $(document).on('change', '.product-select', function() {
                const price = $(this).find(':selected').data('price');
                const row = $(this).closest('tr');
                row.find('.price-input').val(price);
                calculateRowTotal(row);
            });

            // Calcular total da linha quando quantidade ou preço muda
            $(document).on('input', '.quantity-input, .price-input', function() {
                const row = $(this).closest('tr');
                calculateRowTotal(row);
            });

            // Mostrar/ocultar seção de parcelas
            $('#payment_method').change(function() {
                if ($(this).val() === 'parcelado') {
                    $('#installments-section').show();
                    if ($('#installments-tbody tr').length === 0) {
                        addInstallment();
                    }
                } else {
                    $('#installments-section').hide();
                }
            });



            // Adicionar parcela
            $('#installments_count').on('input', function() {
                let count = parseInt($(this).val()) || 1;
                let total = parseFloat($('#total_amount').val().replace(',', '.')) || 0;
                let baseValue = Math.floor((total / count) * 100) / 100;
                let remainder = Math.round((total - (baseValue * count)) * 100) / 100;

                $('#installments-tbody').empty();
                for (let i = 0; i < count; i++) {
                    let value = baseValue;
                    // Adiciona o resto à última parcela para fechar o valor total
                    if (i === count - 1) value += remainder;
                    let today = new Date();
                    today.setMonth(today.getMonth() + i);
                    let dueDate = today.toISOString().slice(0, 10);

                    $('#installments-tbody').append(`
            <tr class="installment-row">
                <td>${i + 1}ª</td>
                <td>
                    <input type="text" class="form-control" name="installments[${i}][amount]" value="${value.toFixed(2)}" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="installments[${i}][due_date]" value="${dueDate}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-installment">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                }
            });
            $(document).on('input', 'input[name^="installments"][name$="[amount]"]', function() {
                let total = parseFloat($('#total_amount').val().replace(',', '.')) || 0;
                let count = $('#installments-tbody tr').length;
                let currentIndex = $(this).closest('tr').index();
                let sumPrev = 0;

                // Soma todas as parcelas anteriores (incluindo a atual)
                $('#installments-tbody tr').each(function(i) {
                    if (i <= currentIndex) {
                        sumPrev += parseFloat($(this).find('input[name$="[amount]"]').val().replace(
                            ',', '.')) || 0;
                    }
                });

                // Valor restante para as próximas parcelas
                let remaining = total - sumPrev;
                let remainingCount = count - (currentIndex + 1);

                // Recalcula as próximas parcelas
                if (remainingCount > 0) {
                    let baseValue = Math.floor((remaining / remainingCount) * 100) / 100;
                    let remainder = Math.round((remaining - (baseValue * remainingCount)) * 100) / 100;

                    $('#installments-tbody tr').each(function(i) {
                        if (i > currentIndex) {
                            let value = baseValue;
                            if (i === count - 1) value += remainder;
                            $(this).find('input[name$="[amount]"]').val(value.toFixed(2));
                        }
                    });
                }
            });

            $('#payment_method').change(function() {
                if ($(this).val() === 'parcelado') {
                    $('#installments-section').show();
                    $('#installments_count').trigger('input');
                } else {
                    $('#installments-section').hide();
                }


            });



            // Remover parcela
            $(document).on('click', '.remove-installment', function() {
                $(this).closest('tr').remove();
                updateInstallmentNumbers();
            });

            function calculateRowTotal(row) {
                const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                const price = parseFloat(row.find('.price-input').val()) || 0;
                const total = quantity * price;
                row.find('.total-input').val(total.toFixed(2));
                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                let grandTotal = 0;
                $('.total-input').each(function() {
                    grandTotal += parseFloat($(this).val()) || 0;
                });
                $('#grand-total').text(grandTotal.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2
                }));
                $('#total_amount').val(grandTotal.toFixed(2));
            }

            function updateRemoveButtons() {
                const rows = $('.product-row');
                $('.remove-product').prop('disabled', rows.length <= 1);
            }

            function addInstallment() {
                const installmentNumber = installmentIndex + 1;
                const newRow = `
            <tr class="installment-row">
                <td>${installmentNumber}ª</td>
                <td>
                    <input type="number" class="form-control" name="installments[${installmentIndex}][amount]" 
                           step="0.01" min="0" required>
                </td>
                <td>
                    <input type="date" class="form-control" name="installments[${installmentIndex}][due_date]" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-installment">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
                $('#installments-tbody').append(newRow);
                installmentIndex++;
            }

            function updateInstallmentNumbers() {
                $('#installments-tbody tr').each(function(index) {
                    $(this).find('td:first').text((index + 1) + 'ª');
                });
            }

            // Validação do formulário
            $('#sale-form').submit(function(e) {
                const hasProducts = $('.product-row').length > 0;
                const grandTotal = parseFloat($('#total_amount').val());

                if (!hasProducts) {
                    e.preventDefault();
                    alert('Adicione pelo menos um produto à venda.');
                    return false;
                }

                if (grandTotal <= 0) {
                    e.preventDefault();
                    alert('O valor total da venda deve ser maior que zero.');
                    return false;
                }

                // Validar parcelas se pagamento for parcelado
                if ($('#payment_method').val() === 'parcelado') {
                    let installmentTotal = 0;
                    $('input[name*="[amount]"]').each(function() {
                        installmentTotal += parseFloat($(this).val()) || 0;
                    });

                    if (Math.abs(installmentTotal - grandTotal) > 0.01) {
                        e.preventDefault();
                        alert('A soma das parcelas deve ser igual ao valor total da venda.');
                        return false;
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#costumer_id').change(function() {
                const selectedOption = $(this).find(':selected');
                const cpf = selectedOption.data('cpf') || '';
                $('#customer-cpf').val(cpf);
            });

            $('#costumer_id').trigger('change');
        });
    </script>
@stop
