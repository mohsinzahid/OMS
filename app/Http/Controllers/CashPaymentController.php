<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = DB::table('vendor')->select('name', 'id')->get();

        return view('purchases.forms.cash payment.add',['vendor' => $vendors],['msg'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('purchasepayment')->insert(
            ['vendor_id' => $request['vendor_id'], 'paiddate' => $request['paiddate'] ,
                'invoiceno' => $request['invoiceno'] , 'added_at' => DB::raw('NOW()') , 'amount' => $request['amount'],
                'type' => "cash"]);

        $purchase_payment_id = DB::getPdo()->lastInsertId();

        if($request['type'] == 0)
        {
            DB::table('chequeinfo')->insert(
                ['cheque_no' => $request['chequeno'], 'cheque_date' => $request['chequedate'] ,
                    'tax_amount' => $request['taxamount'] , 'purchase_payment_id' => $purchase_payment_id]);
            DB::table('purchasepayment')->where('id', $purchase_payment_id)
                ->update(['type' => "cheque"]);
        }

        $vendors = DB::table('vendor')->select('name', 'id')->get();

        return view('purchases.forms.cash payment.add',['vendor' => $vendors],['msg'=>'Payment added Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = DB::table('purchasepayment')->where('id', $id)->first();
        $chequeinfo = DB::table('chequeinfo')->where('purchase_payment_id', $id)->first();
        $vendors = DB::table('vendor')->select('name', 'id')->get();

        return view('purchases.forms.cash payment.edit',['vendor' => $vendors, 'payment' => $payment,
            'chequeinfo' => $chequeinfo],['msg'=>'']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        dd($request);
        $payment = DB::table('purchasepayment')->where('id', $request['id'])->first();

        DB::table('purchasepayment')->where('id', $request['id'])
            ->update(['vendor_id' => $request['vendor_id'],
                'paiddate' => $request['paiddate'] ,
                'invoiceno' => $request['invoiceno'] ,
                'added_at' => DB::raw('NOW()') ,
                'amount' => $request['amount'],
                'type' => 'cash']);

        if (($payment->type === 'cheque') && ($request['type'] == 1))
        {
            DB::table('chequeinfo')->where('purchase_payment_id', $request['id'])->delete();
        }
        else if (($payment->type === 'cash') && ($request['type'] == 0))
        {
            DB::table('chequeinfo')->insert(
                ['cheque_no' => $request['chequeno'], 'cheque_date' => $request['chequedate'] ,
                    'tax_amount' => $request['taxamount'] , 'purchase_payment_id' => $request['id']]);
            DB::table('purchasepayment')->where('id', $request['id'])
                ->update(['type' => "cheque"]);
        }
        else if (($payment->type === 'cheque') && ($request['type'] == 0))
        {
            DB::table('chequeinfo')->where('purchase_payment_id', $request['id'])
                ->update(['cheque_no' => $request['chequeno'], 'cheque_date' => $request['chequedate'] ,
                    'tax_amount' => $request['taxamount']]);
            DB::table('purchasepayment')->where('id', $request['id'])
                ->update(['type' => "cheque"]);
        }

        return redirect('/form/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('purchasepayment')->where('id', $id)->delete();
        DB::table('chequeinfo')->where('purchase_payment_id', $id)->delete();
        return redirect('/form/edit');
    }
}