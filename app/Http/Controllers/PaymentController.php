<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::all();

        return view('Body.payments', compact('payments'));
    }

    public function store(Request $request)
    {
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

        return response()->json([
            'success' => true,
            'message' => 'Pagamento cadastrado com sucesso!'
        ]);
    }

    public function show($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            return response()->json($payment);
        } else {
            return response()->json(['error' => 'Pagamento não encontrado.'], 404);
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
