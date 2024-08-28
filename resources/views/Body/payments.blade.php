@extends('Layout.header')

@section('content')

<div class="container payment-container">
    <div class="payment-header">
        <h3> Lista de Vendas</h3>
        <a href="/" class="btn btn-primary">Adicionar Venda</a>
        <table class="table table-striped">
            <thead>
                <tr>
                  <th scope="col" style="width: 5%">ID</th>
                  <th scope="col" style="width: 20%">Cliente</th>
                  <th scope="col" style="width: 20%">Valor</th>
                  <th scope="col" style="width: 15%">Quantiade Parcelas</th>
                  <th scope="col" style="width: 10%">Tipo de Pagamento</th>
                  <th scope="col" style="width: 10%">Ações</th>
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
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


@endsection

@extends('Layout.footer')