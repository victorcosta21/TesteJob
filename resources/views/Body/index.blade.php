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
                <input type="number" class="form-control moneyMask" id="qtdProduct"></input>
            </div>
            <div class="col-md-2">
                <label for="qtdProduct">Valor Unitário</label>
                <input type="text" class="form-control moneyMask" id="unitProduct"></input>
            </div>
            <div class="col-md-2">
                <label for="qtdProduct">Subtotal</label>
                <input type="text" class="form-control moneyMask" id="totProduct"></input>
            </div>
            <div class="col-md-1 mt-4">
                <button type="button" class="btn btn-primary product-btn" id="addList">+</button>
            </div>
        </div>
        @extends('Modal.products')
    </div>
</div>

<script>
$('#addList').on('click', function(event){
    console.log("clicou")
});

<script>

@endsection

@extends('Layout.footer')

