@extends('Layout.header')

@section('content')

<div class="container payment-container">
    <div class="payment-header">
        <h3> Lista de Vendas</h3>
        <a href="/" class="btn btn-primary">Adicionar Venda</a>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                  <th scope="col" style="width: 5%">ID</th>
                  <th scope="col" style="width: 20%">Cliente</th>
                  <th scope="col" style="width: 10%">Valor</th>
                  <th scope="col" style="width: 15%">Quantiade Parcelas</th>
                  <th scope="col" style="width: 15%">Tipo de Pagamento</th>
                  <th scope="col" style="width: 15%">Ações</th>
                </tr>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                    <tr> 
                        <th>{{$payment->id}}</th>
                        <th>{{$payment->client}}</th>
                        <th>{{$payment->subtotal}}</th>
                        <th>{{$payment->qtd_parcels}}</th>
                        <th>{{$payment->payment_type}}</th>
                        <th> 
                            <button type="button" class="btn btn-danger remove-payment" data-id={{$payment->id}}>
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="btn btn-primary info-payment" data-id={{$payment->id}}>
                                <i class="fa fa-info" aria-hidden="true"></i>
                            </button>
                            <a href="/payment/edit/{{$payment->id}}" type="button" class="btn btn-warning edit-payment">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Detalhes da Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


@endsection

@extends('Layout.footer')