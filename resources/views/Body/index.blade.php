@extends('Layout.header')

@section('content')
    
<div class="container">
    <div class="client-header">
        <div class="row">
            <div>Cliente</div>
        </div>
        <div class="row client-form-row mt-2">
            <div class="col-md-4">
                <form class="client-form">
                    <select id="clientSelect" class="selectpicker" title="Selecione..." data-live-search="true">
                        @foreach($clients as $client)
                            <option>{{$client->name}} / {{$client->document}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-primary client-btn" data-bs-toggle="modal" data-bs-target="#clienteModal">+</button>
            </div>
        </div>
        @extends('Modal.clients')
    </div>
    <hr>

    <div class="product-header">
        <div class="row">
            <div class="col-md-4">
                <label>Produto</label>
                <form class="product-form">
                    <select id="productSelect" class="selectpicker" title="Selecione..." data-live-search="true">
                        @foreach($products as $product)
                            <option>{{$product->name}} / {{$product->value}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-1 mt-4">
                <button type="button" class="btn btn-primary product-btn" data-bs-toggle="modal" data-bs-target="#productModal">+</button>
            </div>
            <div class="col-md-2">
                <label for="qtdProduct">Quantidade</label>
                <input type="number" class="form-control" id="qtdProduct" min="1" placeholder="999"></input>
            </div>
            <div class="col-md-2">
                <label for="qtdProduct">Valor Unitário</label>
                <input type="text" class="form-control moneyMask" id="unitProduct" placeholder="R$"></input>
            </div>
            <div class="col-md-2">
                <label for="qtdProduct">Subtotal</label>
                <input type="text" class="form-control moneyMask" id="totProduct" placeholder="R$"></input>
            </div>
            <div class="col-md-1 mt-4">
                <button type="button" class="btn btn-primary product-btn" id="addList" disabled>+</button>
            </div>
        </div>
        
        <div class="row switch-tabs"> 
            <div class="col-md-6 tab active" id="tabList">Lista</div>
            <div class="col-md-6 tab inactive" id="tabPayment">Pagamento</div>
        </div>

        <div class="listProducts mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                      <th scope="col" style="width: 30%">Nome</th>
                      <th scope="col" style="width: 10%">Quantidade</th>
                      <th scope="col" style="width: 15%">Valor</th>
                      <th scope="col" style="width: 15%">Subtotal</th>
                      <th scope="col" style="width: 10%">Ações</th>
                    </tr>
                  </thead>
                  <tbody>

                </tbody>
            </table>
        </div>

        <div class="paymentProducts mt-3 d-none">
            <h3>Pagamento</h3>
            <div class="row mt-4">
                <div class="col-md-5 select-payment-types">
                    <label for="typePayment">Tipo de Pagamento</label>
                    <select class="selectpicker mb-2" title="Selecione..." data-live-search="true" id="typePayment">
                        <option>Dinheiro</option>
                        <option>Cartão Débito</option>
                        <option>Cartão Crédito</option>
                    </select>

                    <label for="daysPayment">Forma de Pagamento</label>
                    <select class="selectpicker mb-2" title="Selecione..." data-live-search="true" id="daysPayment">
                        <option>Fixo</option>
                        <option>Personalizado</option>
                    </select>

                    <label for="qtdPacel">Quantidade de Parcelas</label>
                    <input type="number" class="form-control mb-2" id="qtdPacel" min="1" placeholder="12" max="99">

                    <div class="row">
                        <div class="col-md-6"> 
                            <label for="datePay">Data de Vencimento</label>
                            <input type="date" id="datePay" class="form-control">
                        </div> 
                        <div class="col-md-6"> 
                            <label for="valueToPay">Valor do Pagamento</label>
                            <input type="text" class="moneyMask form-control" id="valueToPay" placeholder="R$"></input>
                        </div> 

                    </div>

                    <div class="row mt-4 d-flex">
                        <div> 
                            <button class="btn btn-success" id="addDayPayment">Adicionar Pagamento</button>
                        </div>
                        <div class="mt-2">
                            <label>Valor Restante</label>
                            <input type="text" class="form-control maskMoney" readonly id="valueNeedPay"></input>
                        </div> 
                    </div>
                </div>

                <div class="col-md-7 list-payment-type"> 
                    <table class="table table-striped">
                        <thead>
                            <tr>
                              <th scope="col" style="width: 15%">Parcelas</th>
                              <th scope="col" style="width: 10%">Data</th>
                              <th scope="col" style="width: 30%">Valor</th>
                              <th scope="col" style="width: 30%">Tipo</th>
                              <th scope="col" style="width: 10%">Ações</th>
                            </tr>
                          </thead>
                          <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>


        </div>

        <br><hr>
        <div class="subtotal">
            <h4>Valor Total</h4>
            <div class="row"> 
                <div class="col-md-3">
                    <input type="text" class="form-control" readonly id="totValue"></input>
                </div>
                <div class="col-md-3"> 
                    <button type="button" class="btn btn-success" id="saveSell">Salvar Venda</button>
                </div>
            </div>
        </div
        
        @extends('Modal.products')
    </div>
</div>

@endsection

@extends('Layout.footer')

