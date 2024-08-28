<!-- Modal -->
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clienteModalLabel">Cadastro de Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cadastroClienteForm">
                    <div class="mb-3">
                        <label for="nomeCliente" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nomeCliente" placeholder="Digite o nome do cliente" required>
                    </div>
                    <div class="mb-3">
                        <label for="cpfCliente" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpfCliente" placeholder="Digite o CPF do cliente" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" form="cadastroClienteForm">Cadastrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#cadastroClienteForm').on('submit', function(event) {
        event.preventDefault();

        var nome = $('#nomeCliente').val();
        var cpf = $('#cpfCliente').val();

        $.ajax({
            url: '/clients/create',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: nome,
                document: cpf
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#clienteModal').modal('hide');
                    $('#cadastroClienteForm')[0].reset();
                } else {
                    console.log(response);
                    alert('Ocorreu um erro ao cadastrar o cliente.');
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