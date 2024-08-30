<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.6/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    // Masks
    $('.moneyMask').mask('000.000,00', {reverse: true});
    $('.cpfMask').mask('000.000.000-00', {reverse: true});

    // Funções para o funcionando da listagem
    $('#productSelect, #qtdProduct').on('change', function() {
        var produtoSelecionado = $('#productSelect').val();
        if (produtoSelecionado) {
            $('#addList').prop('disabled', false);
        } else {
            $('#addList').prop('disabled', true);
        }
        var partesProduto = produtoSelecionado.split(' / ');
        var nomeProduto = partesProduto[0];
        var valorUnitario = parseFloat(partesProduto[1].replace(',', '.'));
        
        var quantidade = parseInt($('#qtdProduct').val());

        if (!quantidade || quantidade === 0) {
            quantidade = 1;
            $('#qtdProduct').val(1);
        }
        var subtotal = quantidade * valorUnitario;

        const formatter = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
        const valorUnitarioFormatado = formatter.format(valorUnitario);
        const subtotalFormatado = formatter.format(subtotal);

        $('#unitProduct').val(valorUnitarioFormatado);
        $('#totProduct').val(subtotalFormatado);
    });

    $('#addList').on('click', function() {
        var produtoSelecionado = $('#productSelect').val();
        var quantidade = $('#qtdProduct').val();
        var valorUnitario = $('#unitProduct').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
        var subtotal = $('#totProduct').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');

        if (!subtotal || subtotal === '0.00') {
            subtotal = (parseFloat(quantidade) * parseFloat(valorUnitario.replace(',', '.'))).toFixed(2);
        }

        var valorUnitarioFormatado = formatarMoeda(valorUnitario);
        var subtotalFormatado = formatarMoeda(subtotal);

        var novaLinha = `
            <tr>
                <td>${produtoSelecionado}</th>
                <td>${quantidade}</td>
                <td>${valorUnitarioFormatado}</td>
                <td>${subtotalFormatado}</td>
                <td>
                    <button type="button" class="btn btn-danger remove-item"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                </td>
            </tr>
        `;

        $('.listProducts tbody').append(novaLinha);
        
        $('#productSelect').val('');
        $('#qtdProduct').val('');
        $('#unitProduct').val('');
        $('#totProduct').val('');
        $('.selectpicker').selectpicker('refresh');

        $('#addList').prop('disabled', true);

        atualizarTotal();
    });

    $(document).on('click', '.remove-item', function() {
        if (confirm("Tem certeza que deseja remover este item?")) {
            $(this).closest('tr').remove();
            atualizarTotal();
        }
    });

    function atualizarTotal() {
        var total = 0;

        $('.listProducts tbody tr').each(function() {
            var subtotal = $(this).find('td:nth-child(4)').text().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
            total += parseFloat(subtotal);
        });

        $('#totValue').val(formatarMoeda(total));
    }

    function formatarMoeda(valor) {
        return 'R$ ' + parseFloat(valor).toFixed(2)
            .replace('.', ',')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Função para o switch tabs
    $('#tabList, #tabPayment').on('click', function() {
        if($('#totValue').val()){
            $('#tabList, #tabPayment').removeClass('active inactive');
            $('.listProducts, .paymentProducts').addClass('d-none');
            if ($(this).attr('id') === 'tabList') {
                $('#tabList').addClass('active');
                $('#tabPayment').addClass('inactive');
                $('.listProducts').removeClass('d-none');
            } else {
                $('#tabList').addClass('inactive');
                $('#tabPayment').addClass('active');
                $('.paymentProducts').removeClass('d-none');
            }
        }
    });

    // Função para calcular as parcelas
    $('#daysPayment, #qtdPacel, #datePay, #typePayment').on('change input', function() {
        if ($('#daysPayment').val() === 'Fixo' && $('#qtdPacel').val() && $('#datePay').val() &&$('#typePayment').val()) {
            preencherTabela();
        }
    });

    $(document).on('click', '.remove-item-and-decrease-parcel', function() {
        if (confirm("Tem certeza que deseja remover este item?")) {
            $(this).closest('tr').remove();
            var quantidadeParcelas = parseInt($('#qtdPacel').val(), 10);
            if (quantidadeParcelas > 1) { 
                $('#qtdPacel').val(quantidadeParcelas - 1);
            }
            preencherTabela();
        }
    });
    
    function preencherTabela() {
        var totalValue = parseFloat($('#totValue').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.'));
        var parcelas = parseInt($('#qtdPacel').val(), 10);
        var dataVencimento = new Date($('#datePay').val());

        dataVencimento.setDate(dataVencimento.getDate() + 1);

        if (!isNaN(totalValue) && !isNaN(parcelas) && parcelas > 0) {
            var valorPorParcela = (totalValue / parcelas).toFixed(2);
            var valorTotalArredondado = (valorPorParcela * parcelas).toFixed(2);
            var diferenca = (totalValue - valorTotalArredondado).toFixed(2);
            var tipoPagamento = $('#typePayment').val();

            var tbody = $('.list-payment-type table tbody');
            tbody.empty();

            for (var i = 1; i <= parcelas; i++) {
                var dataParcela = new Date(dataVencimento);
                dataParcela.setMonth(dataParcela.getMonth() + (i - 1));

                var dia = String(dataParcela.getDate()).padStart(2, '0');
                var mes = String(dataParcela.getMonth() + 1).padStart(2, '0');
                var ano = dataParcela.getFullYear();
                var dataFormatada = `${dia}/${mes}/${ano}`;

                var valorExibido = i === parcelas ? 
                    (parseFloat(valorPorParcela) + parseFloat(diferenca)).toFixed(2) : 
                    valorPorParcela;

                valorExibido = parseFloat(valorExibido).toFixed(2);

                tbody.append(`
                    <tr>
                        <td>${i}</td>
                        <td>${dataFormatada}</td>
                        <td>${formatarMoeda(valorExibido)}</td>
                        <td>${tipoPagamento}</td>
                        <td><button type="button" class="btn btn-danger remove-item-and-decrease-parcel"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                    </tr>
                `);
            }
        }
    }

    var personalizedParcelas = [];
    $('#addDayPayment').on('click', function() {
        if ($('#daysPayment').val() === 'Personalizado') {
            var dataVencimento = new Date($('#datePay').val());

            dataVencimento.setDate(dataVencimento.getDate() + 1);
            if (!isNaN(dataVencimento)) {
                var valorParcelaInput = $('#valueToPay').val();
                if (valorParcelaInput === undefined || valorParcelaInput.trim() === '') {
                    alert("Por favor, insira um valor para a parcela.");
                    return;
                }

                var valorParcela = parseFloat(valorParcelaInput.replace('R$', '').replace(/\./g, '').replace(',', '.'));

                if (isNaN(valorParcela) || valorParcela <= 0) {
                    alert("Por favor, insira um valor válido para a parcela.");
                    return;
                }

                var totalValue = parseFloat($('#totValue').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.'));

                var totalParcelas = personalizedParcelas.reduce((acc, val) => acc + val, 0);

                if ((totalParcelas + valorParcela) > totalValue) {
                    alert("O valor total das parcelas não pode exceder o valor total da venda.");
                    return;
                }

                personalizedParcelas.push(valorParcela);

                var diaFormatado = String(dataVencimento.getDate()).padStart(2, '0');
                var mes = String(dataVencimento.getMonth() + 1).padStart(2, '0');
                var ano = dataVencimento.getFullYear();
                var dataFormatada = `${diaFormatado}/${mes}/${ano}`;
                var tipoPagamento = $('#typePayment').val();

                $('.table-striped tbody').append(`
                    <tr>
                        <td>${personalizedParcelas.length}</td>
                        <td>${dataFormatada}</td>
                        <td>${formatarMoeda(valorParcela)}</td>
                        <td>${tipoPagamento}</td>
                        <td><button type="button" class="btn btn-danger remove-item-and-adjuste-final-value"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>
                    </tr>
                `);

                atualizarValorRestante();
            } else {
                alert("Por favor, selecione uma data de vencimento válida.");
            }
        }
    });

    function atualizarValorRestante() {
        var totalValue = parseFloat($('#totValue').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.'));
        var totalParcelas = personalizedParcelas.reduce((acc, val) => acc + val, 0);
        var valorRestante = totalValue - totalParcelas;

        if (valorRestante == 0) {
            $('#qtdPacel').val(personalizedParcelas.length);
        }

        $('#valueNeedPay').val(formatarMoeda(valorRestante));
    }

    $(document).on('click', '.remove-item-and-adjuste-final-value', function() {
        if (confirm("Tem certeza que deseja remover este item?")) {
            var valorParcelaRemovida = parseFloat($(this).closest('tr').find('td:eq(2)').text().replace('R$', '').replace(/\./g, '').replace(',', '.'));

            $(this).closest('tr').remove();

            var index = personalizedParcelas.indexOf(valorParcelaRemovida);
            if (index > -1) {
                personalizedParcelas.splice(index, 1);
            }

            var quantidadeParcelas = parseInt($('#qtdPacel').val(), 10);
            if (quantidadeParcelas > 1) {
                $('#qtdPacel').val(quantidadeParcelas - 1);
            }

            atualizarValorRestante();
        }
    });

    $('#daysPayment').on('change', function() {
        var selectedValue = $(this).val();
        
        if (selectedValue === 'Personalizado') {
            $('#paymentOptionsDiv').removeClass('d-none');
        } else {
            $('#paymentOptionsDiv').addClass('d-none');
        }
    });

    // Salvar Venda
    $('#saveSell').on('click', function() {
        var clientSelect = $('#clientSelect').val();
        var totValue = $('#totValue').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
        var qtdPacel = $('#qtdPacel').val();
        var typePayment = $('#daysPayment').val();

        if(!$('#clientSelect').val()){
            alert("Por favor, selecione um cliente para anexar a venda!");
            return;
        }

        if ($('#daysPayment').val() === 'Personalizado' && $('#valueNeedPay').val() != 'R$ 0,00') {
            console.log('valueNeedPay', $('#valueNeedPay').val());
            alert("Insira o valor completo antes de salvar a venda!");
            return;
        }

        var listProduct = [];
        $('.listProducts tbody tr').each(function() {
            var productNome = $(this).find('td').eq(0).text();
            var productQuantidade = $(this).find('td').eq(1).text();
            var productValor = $(this).find('td').eq(2).text();
            var productSubtotal = $(this).find('td').eq(3).text();

            listProduct.push({
                nome: productNome,
                quantidade: productQuantidade,
                valor: productValor,
                subtotal: productSubtotal
            });
        });

        var listPaymentData = [];
        $('.list-payment-type tbody tr').each(function() {
            var parcela = $(this).find('td').eq(0).text();
            var data = $(this).find('td').eq(1).text();
            var valor = $(this).find('td').eq(2).text().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
            var tipo = $(this).find('td').eq(3).text();

            listPaymentData.push({
                parcela: parcela,
                data: data,
                valor: valor,
                tipo: tipo
            });
        });

        $.ajax({
            url: '/payment/create',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                clientSelect: clientSelect,
                totValue: totValue,
                qtdPacel: qtdPacel,
                typePayment: typePayment,
                paymentDetails: listPaymentData,
                listProduct: listProduct
            },
            success: function(response) {
                alert('Venda salva com sucesso!');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Erro ao salvar a venda:', error);
                alert('Houve um erro ao salvar a venda. Tente novamente.');
            }
        });
    });

    $(document).on('click', '.remove-payment', function() {
        if (confirm("Tem certeza que deseja remover este registro?")) {
            var id = $(this).data('id');

            $.ajax({
                url: '/payment/delete',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao remover o registro:', error);
                    alert('Houve um erro ao remover o registro. Tente novamente.');
                }
            });
        }
    });

    $(document).on('click', '.info-payment', function() {
        var paymentId = $(this).data('id');

        $.ajax({
            url: '/payment/show/' + paymentId,
            type: 'GET',
            success: function(response) {
                if (Array.isArray(response.linked_payments)) {
                    var linkedPaymentsTable = `
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">Parcela</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${response.linked_payments.map(payment => `
                                    <tr>
                                        <td>${payment.parcel}</td>
                                        <td>${payment.pay_date}</td>
                                        <td>${formatarMoeda(payment.pay_value)}</td>
                                        <td>${payment.type_payment}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;

                    $('#paymentModal .modal-body').html(`
                        <p><strong>ID:</strong> ${response.id}</p>
                        <p><strong>Cliente:</strong> ${response.client}</p>
                        <p><strong>Valor Total:</strong> ${formatarMoeda(response.subtotal)}</p>
                        <p><strong>Data:</strong> ${formatarDataISO(response.created_at)}</p>
                        ${linkedPaymentsTable}
                    `);

                    $('#paymentModal').modal('show');
                } else {
                    console.error('Os dados de linked_payments não estão no formato esperado.');
                    alert('Houve um erro ao processar os detalhes do pagamento.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar detalhes do pagamento:', error);
                alert('Houve um erro ao buscar os detalhes do pagamento.');
            }
        });
    });

    function formatarDataISO(dataISO) {
        var data = new Date(dataISO);

        var dia = String(data.getDate()).padStart(2, '0');
        var mes = String(data.getMonth() + 1).padStart(2, '0');
        var ano = data.getFullYear();
        var horas = String(data.getHours()).padStart(2, '0');
        var minutos = String(data.getMinutes()).padStart(2, '0');

        return `${dia}/${mes}/${ano} ${horas}:${minutos}`;
    }

    function toggleSaveButton() {
        if ($('.list-payment-type tbody tr').length === 0) {
            $('#saveSell').prop('disabled', true);
        } else {
            $('#saveSell').prop('disabled', false);
        }
    }

    $('.list-payment-type tbody').on('DOMNodeInserted DOMNodeRemoved', function() {
        toggleSaveButton();
    });

    $('#updateSell').on('click', function() {
        var clientSelect = $('#clientSelect').val();
        var totValue = $('#totValue').val().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
        var qtdPacel = $('#qtdPacel').val();
        var typePayment = $('#daysPayment').val();
        var iptHiddenId = $('#iptHiddenId').val();

        if(!$('#clientSelect').val()){
            alert("Por favor, selecione um cliente para anexar a venda!");
            return;
        }

        if ($('#daysPayment').val() === 'Personalizado' && $('#valueNeedPay').val() != 'R$ 0,00') {
            console.log('valueNeedPay', $('#valueNeedPay').val());
            alert("Insira o valor completo antes de atualizar a venda!");
            return;
        }

        var listProduct = [];
        $('.listProducts tbody tr').each(function() {
            var productNome = $(this).find('td').eq(0).text();
            var productQuantidade = $(this).find('td').eq(1).text();
            var productValor = $(this).find('td').eq(2).text();
            var productSubtotal = $(this).find('td').eq(3).text();

            listProduct.push({
                nome: productNome,
                quantidade: productQuantidade,
                valor: productValor,
                subtotal: productSubtotal
            });
        });

        var listPaymentData = [];
        $('.list-payment-type tbody tr').each(function() {
            var parcela = $(this).find('td').eq(0).text();
            var data = $(this).find('td').eq(1).text();
            var valor = $(this).find('td').eq(2).text().replace('R$', '').trim().replace(/\./g, '').replace(',', '.');
            var tipo = $(this).find('td').eq(3).text();

            listPaymentData.push({
                parcela: parcela,
                data: data,
                valor: valor,
                tipo: tipo
            });
        });

        $.ajax({
            url: '/payment/update/' + iptHiddenId,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                clientSelect: clientSelect,
                totValue: totValue,
                qtdPacel: qtdPacel,
                typePayment: typePayment,
                paymentDetails: listPaymentData,
                listProduct: listProduct
            },
            success: function(response) {
                alert('Venda atualizada com sucesso!');
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atualizar a venda:', error);
                alert('Houve um erro ao atualizar a venda. Tente novamente.');
            }
        });
    });

});
</script>
</body>
</html>