<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Cadastro de Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cadastroProductForm">
                    <div class="mb-3">
                        <label for="nomeProduct" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nomeProduct" placeholder="Digite o nome do produto" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="valueProduct" class="form-label">Valor</label>
                        <input type="text" class="form-control moneyMask" id="valueProduct" placeholder="R$ 0.000,00" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="cadastroProductForm">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#cadastroProductForm').on('submit', function(event) {
        event.preventDefault();

        var nome = $('#nomeProduct').val();
        var valor = $('#valueProduct').val();

        $.ajax({
            url: '/products/create',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: nome,
                value: valor
            },
            success: function(response) {
                if (response.success) {
                    $('#productSelect').append(
                        $('<option>', {
                            text: response.product.name + ' / ' + response.product.value,
                        })
                    );

                    $('#productSelect').selectpicker('refresh');
                    
                    alert(response.message);
                    $('#productModal').modal('hide');
                    $('#cadastroProductForm')[0].reset();
                } else {
                    console.log(response);
                    alert('Ocorreu um erro ao cadastrar o produto.');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessages = '';

                $.each(errors, function(key, value) {
                    errorMessages += value + '\n';
                });

                alert('Erro: \n' + errorMessages);
            }
        });
    });
});
</script>