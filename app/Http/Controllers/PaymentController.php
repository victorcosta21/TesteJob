<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\LinkedPayments;
use App\Models\Clients;
use App\Models\Products;
use App\Models\LinkedProducts;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::all();

        return view('Body.payments', compact('payments'));
    }

    public function store(Request $request)
    {

        return response()->json([
            'success' => true,
            'message' => 'Pagamento cadastrado com sucesso!'
        ]);
        $validated = $request->validate([
            'clientSelect' => 'required',
            'totValue' => 'required',
            'qtdPacel' => 'required',
            'typePayment' => 'required',
        ]);

        $payments = new Payment();
        $payments->client = $request->clientSelect;
        $payments->subtotal = $request->totValue;
        $payments->qtd_parcels = $request->qtdPacel;
        $payments->payment_type = $request->typePayment;
        $payments->save();

        $paymentDetails = $request->paymentDetails;
        foreach($paymentDetails as $paymentDetail){
            $linkedPayment = new LinkedPayments();
            $linkedPayment->payment_id = $payments->id;
            $linkedPayment->type_payment = $paymentDetail['tipo'];
            $linkedPayment->parcel = $paymentDetail['parcela'];
            $linkedPayment->pay_date = $paymentDetail['data'];
            $linkedPayment->pay_value = $paymentDetail['valor'];
            $linkedPayment->save();
        }

        $paymentProducts = $request->listProduct;
        foreach($paymentProducts as $product){
            $linkedProduct = new LinkedProducts();
            $linkedProduct->payment_id = $payments->id;
            $linkedProduct->product_name = $product['nome'];
            $linkedProduct->quantity = $product['quantidade'];
            $linkedProduct->value = $product['valor'];
            $linkedProduct->sub_value = $product['subtotal'];
            $linkedProduct->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pagamento cadastrado com sucesso!'
        ]);
    }

    public function show($id)
    {
        $payment = Payment::where('id', $id)->with('linkedPayments')->first();
        if ($payment) {
            return response()->json($payment);
        } else {
            return response()->json(['error' => 'Pagamento não encontrado.'], 404);
        }
    }

    public function edit($id)
    {
        $payment = Payment::where('id', $id)->with('linkedPayments', 'linkedProducts')->first();
        $clients = Clients::all();
        $products = Products::all();
        
        return view('Body.payment-edit', compact('payment', 'clients', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'totValue' => 'required|numeric',
            'qtdPacel' => 'required|integer',
            'typePayment' => 'required|string',
            'paymentDetails' => 'required|array',
            'paymentDetails.*.tipo' => 'required|string',
            'paymentDetails.*.parcela' => 'required|integer',
            'paymentDetails.*.data' => 'required|string',
            'paymentDetails.*.valor' => 'required|string',
            'listProduct' => 'required|array',
            'listProduct.*.nome' => 'required|string',
            'listProduct.*.quantidade' => 'required|integer',
            'listProduct.*.valor' => 'required|string',
            'listProduct.*.subtotal' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::where('id', $id)->with('linkedPayments')->firstOrFail();
    
            $payment->subtotal = $validated['totValue'];
            $payment->qtd_parcels = $validated['qtdPacel'];
            $payment->payment_type = $validated['typePayment'];
            $payment->save();
    
            LinkedPayments::where('payment_id', $payment->id)->delete();
            LinkedProducts::where('payment_id', $payment->id)->delete();
    
            foreach ($validated['paymentDetails'] as $paymentDetail) {
                LinkedPayments::create([
                    'payment_id' => $payment->id,
                    'type_payment' => $paymentDetail['tipo'],
                    'parcel' => $paymentDetail['parcela'],
                    'pay_date' => $paymentDetail['data'],
                    'pay_value' => $paymentDetail['valor'],
                ]);
            }

            $paymentProducts = $request->listProduct;
            foreach($paymentProducts as $product){
                $linkedProduct = new LinkedProducts();
                $linkedProduct->payment_id = $payments->id;
                $linkedProduct->product_name = $product['nome'];
                $linkedProduct->quantity = $product['quantidade'];
                $linkedProduct->value = $product['valor'];
                $linkedProduct->sub_value = $product['subtotal'];
                $linkedProduct->save();
            }
    
            DB::commit();
    
            return response()->json($payment);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar o pagamento: ' . $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $payment = Payment::find($id);

        if ($payment) {
            $payment->delete();
            return response()->json(['success' => true, 'message' => 'Registro removido com sucesso!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Registro não encontrado.'], 404);
        }
    }

}
