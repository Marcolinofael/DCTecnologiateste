@extends('adminlte::page')

@section('title', 'Vendas')

@section('content_header')
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Controle de Vendas</h3>
        </div>
        <form method="POST" action="{{ isset($edit) ? route('sale.update', $edit->id) : route('sale.store') }}">
            @csrf
            @if (isset($edit))
                @method('PUT')
            @endif
            <div class="card-body">
                <div class="form-group">
                    <div class="row">

                        <div class="col-sm-4">
                            <label for="costumer_id">Cliente</label>
                            <select class="form-control" id="costumer_id" name="costumer_id">
                                <option value="">Selecione</option>
                                @foreach ($costumers as $costumer)
                                    <option value="{{ $costumer->id }}" data-cpf="{{ $costumer->cpf }}"
                                        data-phone="{{ $costumer->phone }}"
                                        {{ old('costumer_id', @$edit->costumer_id) == $costumer->id ? 'selected' : '' }}>
                                        {{ $costumer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('costumer_id'))
                                <span style="color: red;">{{ $errors->first('costumer_id') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <label for="cpf">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf" readonly>
                        </div>
                        <div class="col-sm-4">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control" id="phone" name="phone" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-2">
                            <label for="product_id">Produto | Estoque</label>
                            <select class="form-control" id="product_id" name="product_id">
                                <option value="">Selecione</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock_quantity }}"
                                        {{ old('product_id', @$edit->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product['product_name'] . ' | ' . $product[''] }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('product_id'))
                                <span style="color: red;">{{ $errors->first('product_id') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-1">
                            <label for="quantity">Quantidade</label>
                            <input type="text" class="form-control" id="quantity" name="quantity"
                                value="{{ old('quantity', @$edit->quantity) }}">
                            @if ($errors->has('quantity'))
                                <span style="color: red;">{{ $errors->first('quantity') }}</span>
                            @endif
                        </div>
                        <div class="col-sm-2">
                            <label for="price">Valor</label>
                            <input type="text" class="form-control" id="price" name="price" readonly>
                        </div>
                        <div class="col-sm-2">
                            <label for="total_unity">Total</label>
                            <input type="text" class="form-control" id="total_unity" name="total_unity" readonly>
                        </div>
                        <div class="col-sm-2 align-self-end">
                            <a type="button" class="btn btn-primary w-60" id="showProductModal">Incluir Produto</a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Total</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label for="total_amount">Total</label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
                    </div>
                    <div class="col-sm-12 mt-3" id="paymentConditionsDiv">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h5 class="card-title">Condições de Pagamento</h5>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <br>
                                    <select class="form-control" id="conditionpayment_id" name="conditionpayment_id">
                                        <option value="">Selecione</option>
                                        @foreach ($paymentConditions as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                        {{-- <option value="personalizado">Personalizado</option> --}}
                                    </select>
                                </div>
                                <div id="customInstallmentsDiv" style="display:none; margin-top:15px;">
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                            <label for="installments_qty">Quantidade de Parcelas</label>
                                            <input type="number" min="1" class="form-control" id="installments_qty"
                                                value="1">
                                        </div>
                                    </div>
                                    <div id="installmentsList"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Reg. Venda</button>
                <a href="{{ route('sale.index') }}" type="button" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="{{ asset('vendor/jquery/jquery.maskMoney.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#costumer_id').on('change', function() {
                var selected = $(this).find('option:selected');
                $('#cpf').val(selected.data('cpf') || '');
                $('#phone').val(selected.data('phone') || '');
            });

            var selected = $('#costumer_id').find('option:selected');
            $('#cpf').val(selected.data('cpf') || '');
            $('#phone').val(selected.data('phone') || '');

            $('#product_id').on('change', function() {
                var selected = $(this).find('option:selected');
                $('#price').val(selected.data('price') || '');

            });
            var selectedProduct = $('#product_id').find('option:selected');
            $('#price').val(selectedProduct.data('price') || '');
            $('#total_amount').maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            });


            $('#quantity, #price').on('input', function() {
                let quantity = parseFloat($('#quantity').val().replace(',', '.')) || 0;
                let price = parseFloat($('#price').val().replace(',', '.')) || 0;
                let total = (quantity * price).toFixed(2);
                $('#total_unity').val(total);
            });


            $('#product_id').on('change', function() {
                let selected = $(this).find('option:selected');
                $('#price').val(selected.data('price') || '');
                $('#stock_quantity').val(selected.data('stock') || '');
                $('#quantity').trigger('input');
            });

        });
    </script>

    <script>
        $(document).ready(function() {


            $('#quantity, #price').on('input', function() {
                let quantity = parseFloat($('#quantity').val().replace(',', '.')) || 0;
                let price = parseFloat($('#price').val().replace(',', '.')) || 0;
                let total = (quantity * price).toFixed(2);
                $('#total_unity').val(total);
            });


            $('#product_id').on('change', function() {
                let selected = $(this).find('option:selected');
                $('#price').val(selected.data('price') || '');
                $('#stock_quantity').val(selected.data('stock') || '');
                $('#quantity').trigger('input');
            });
        });
    </script>

    <script>
        function updateGrandTotal() {
            let total = 0;
            $('#productsTable tbody tr').each(function() {
                let rowTotal = parseFloat($(this).find('input[name^="products"][name$="[total]"]').val()) || 0;
                total += rowTotal;
            });
            $('#grandTotal').text(total.toFixed(2));
            $('#total_amount').val(total.toFixed(2));
        }
    </script>

    <script>
        $(document).ready(function() {
            let productIndex = 0;

            $('#showProductModal').on('click', function() {
                var productId = $('#product_id').val();
                var product_name = $('#product_id option:selected').text();
                var quantity = $('#quantity').val();
                var price = $('#price').val();
                var total = $('#total_unity').val();

                if (!productId || !quantity || !price) {
                    alert('Selecione o produto e informe a quantidade!');
                    return;
                }

                $('#productsTable tbody').append(`
            <tr>
                <td>
                    ${product_name}
                    <input type="hidden" name="products[${productIndex}][product_id]" value="${productId}">
                </td>
                <td>
                    ${quantity}
                    <input type="hidden" name="products[${productIndex}][quantity]" value="${quantity}">
                </td>
                <td>
                    ${price}
                    <input type="hidden" name="products[${productIndex}][price]" value="${price}">
                </td>
                <td>
                    ${total}
                    <input type="hidden" name="products[${productIndex}][total]" value="${total}">
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm edit-product">Editar</button>
                    <button type="button" class="btn btn-danger btn-sm remove-product">Remover</button>
                </td>
            </tr>
        `);

                productIndex++;


                $('#product_id').val('');
                $('#quantity').val('');
                $('#price').val('');
                $('#total_unity').val('');
                updateGrandTotal();
            });


            $(document).on('click', '.remove-product', function() {
                $(this).closest('tr').remove();
                updateGrandTotal();
            });
            $(document).on('click', '.edit-product', function() {
                var row = $(this).closest('tr');
                var productId = row.find('input[name^="products"][name$="[product_id]"]').val();
                var quantity = row.find('input[name^="products"][name$="[quantity]"]').val();
                var price = row.find('input[name^="products"][name$="[price]"]').val();
                var total = row.find('input[name^="products"][name$="[total]"]').val();

                $('#product_id').val(productId);
                $('#quantity').val(quantity);
                $('#price').val(price);
                $('#total_unity').val(total);

                row.remove();
                updateGrandTotal();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#conditionpayment_id').on('change', function() {
                if ($(this).val() === 'personalizado') {
                    $('#customInstallmentsDiv').slideDown();
                    generateInstallments();
                } else {
                    $('#customInstallmentsDiv').slideUp();
                }
            });

            $('#installments_qty').on('input', function() {
                generateInstallments();
            });

            function generateInstallments() {
                let qty = parseInt($('#installments_qty').val()) || 1;
                let total = parseFloat($('#total_amount').val().replace(',', '.')) || 0;
                let baseValue = (total / qty).toFixed(2);

                let html = '<label>Parcelas</label>';
                let today = new Date();
                for (let i = 1; i <= qty; i++) {
                    let dueDate = new Date(today.getFullYear(), today.getMonth() + i, today.getDate());
                    let dueDateStr = dueDate.toISOString().slice(0, 10);

                    html += `
                <div class="input-group mb-2 installment-row" data-index="${i}">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Parcela ${i}</span>
                    </div>
                    <input type="number" step="0.01" min="0" class="form-control installment-value" name="installments[${i}][value]" value="${baseValue}">
                    <input type="date" class="form-control installment-date" name="installments[${i}][date]" value="${dueDateStr}">
                </div>
            `;
                }
                $('#installmentsList').html(html);
                updateInstallmentsSum();
            }

            $(document).on('input', '.installment-value', function() {
                let qty = parseInt($('#installments_qty').val()) || 1;
                let total = parseFloat($('#total_amount').val().replace(',', '.')) || 0;
                let changedIndex = $(this).closest('.installment-row').data('index');
                let sumPrev = 0;

                // Soma todas as parcelas anteriores à alterada
                $('.installment-row').each(function() {
                    let idx = $(this).data('index');
                    if (idx < changedIndex) {
                        sumPrev += parseFloat($(this).find('.installment-value').val()) || 0;
                    }
                });

                // Valor já definido para a parcela alterada
                let currentValue = parseFloat($(this).val()) || 0;
                sumPrev += currentValue;

                // Valor restante para as próximas parcelas
                let remaining = total - sumPrev;
                let remainingInstallments = qty - changedIndex;

                // Atualiza as parcelas posteriores
                if (remainingInstallments > 0) {
                    let newValue = (remaining / remainingInstallments).toFixed(2);
                    $('.installment-row').each(function() {
                        let idx = $(this).data('index');
                        if (idx > changedIndex) {
                            $(this).find('.installment-value').val(newValue);
                        }
                    });
                }

                updateInstallmentsSum();
            });

            function updateInstallmentsSum() {
                let sum = 0;
                $('.installment-value').each(function() {
                    sum += parseFloat($(this).val()) || 0;
                });

                if ($('#installmentsList').find('.installments-total').length === 0) {
                    $('#installmentsList').append('<div class="installments-total mt-2"></div>');
                }
                $('#installmentsList .installments-total').html('<strong>Total das parcelas: R$ ' + sum.toFixed(2) +
                    '</strong>');
            }
        });
    </script>
@stop
