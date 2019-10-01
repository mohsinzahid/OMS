<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashCollectionController extends Controller
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
     * Show the forms for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();
        $walk = DB::table('customers')->where('type',0)->first();


        return view('sales.forms.cash collection.add',['customer' => $customers,'walk' => $walk],['msg'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd($request);
        if ($request['formtype'] == 'v') //if cash collection type is voucher (enter amount against total ledger)
        {
            DB::table('salepayment')->insert(
                ['customer_id' => $request['customerid'], 'date' => $request['paiddate'] ,
                    'invoiceno' => $request['invoiceno'] , 'added_at' => now() , 'amount' => $request['amount'],
                    'payee_name' => $request['payeename'], 'type' => "cash" ]
            );
            $sale_payment_id = DB::getPdo()->lastInsertId();

            if($request['type'] == 0)
            {
                DB::table('chequeinfo')->insert(
                    ['cheque_no' => $request['chequeno'], 'cheque_date' => $request['chequedate'] ,
                        'tax_amount' => $request['taxamount'] , 'sale_payment_id' => $sale_payment_id]);
                DB::table('salepayment')->where('id', $sale_payment_id)
                    ->update(['type' => "cheque"]);
            }
        }
        else  //if cash collection type is Receipt (enter amount against single job order)
        {
            DB::table('salepayment')->insert(
                ['customer_id' => $request['customerid'], 'date' => $request['paiddate'] ,
                    'job_order_no' => $request['invoiceno'] , 'added_at' => now() , 'amount' => $request['amount'],
                    'payee_name' => $request['payeename'], 'type' => "cash" ]
            );
        }


        $customers = DB::table('customers')->select('name', 'id')->where('status',1)->get();

        return redirect('/sales/forms/cash-collection')->with('msg', 'Payment added Successfully');


//        return view('sales.forms.cash collection.add',['customer' => $customers],['msg'=>'Payment added Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the forms for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        dd($request);
        $payment = DB::table('salepayment')->where('id', $id)->first();
        $chequeinfo = DB::table('chequeinfo')->where('sale_payment_id', $id)->first();
        $customers = DB::table('customers')->select('name', 'id')->where('status',1)->get();

        return view('sales.forms.cash collection.edit',['customer' => $customers, 'payment' => $payment ,
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
        if ($request['formtype'] == 'r') //assuming form type is changed from voucher to Receipt
        {
            $data = DB::table('salepayment')->where('id', $request['id'])->first();
            DB::table('salepayment')->where('id', $request['id'])
                ->update(['customer_id' => $request['customerid'], 'date' => $request['paiddate'] ,
                    'job_order_no' => $request['invoiceno'] , 'added_at' => now() , 'amount' => $request['amount'],
                    'invoiceno' => NULL, 'payee_name' => $request['payeename'], 'type' => "cash"]);
            if($data->type == 'cheque')
            {
                DB::table('chequeinfo')->where('sale_payment_id', $request['id'])->delete();
            }
        }
        else
        {
            DB::table('salepayment')->where('id', $request['id'])
                ->update(['customer_id' => $request['customerid'], 'date' => $request['paiddate'] ,
                    'invoiceno' => $request['invoiceno'] , 'added_at' => now() , 'amount' => $request['amount'],
                    'payee_name' => $request['payeename'], 'job_order_no' => NULL]);

            if ($request['type'] == "0") /*if cheque entry is made*/
            {
                $data =  DB::table('chequeinfo')->where('sale_payment_id', $request['id'])->first();

                if($data)  /*if data already exist in cheque_info table*/
                {
                    DB::table('chequeinfo')->where('sale_payment_id', $request['id'])
                        ->update(['cheque_no' => $request['chequeno'],
                            'cheque_date' => $request['chequedate'] ,
                            'tax_amount' => $request['taxamount']]);
                }
                else /*if data is updated from cash to cheque then new entry will be made*/
                {
                    DB::table('chequeinfo')->insert(
                        ['cheque_no' => $request['chequeno'], 'cheque_date' => $request['chequedate'] ,
                            'tax_amount' => $request['taxamount'] , 'sale_payment_id' => $request['id']]);
                    DB::table('salepayment')->where('id', $request['id'])
                        ->update(['type' => "cheque"]);
                }
            }
            else  /*if entry change from cheque to cash then cheque entry must be deleted here and payment type updated from cheque to cash*/
            {
                DB::table('chequeinfo')->where('sale_payment_id', $request['id'])->delete();
                DB::table('salepayment')->where('id', $request['id'])
                    ->update(['type' => "cash"]);
            }
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
        DB::table('salepayment')->where('id', $id)->delete();
        return redirect('/form/edit');
    }

    public function getinvoice(Request $request)
    {
        if(( DB::table('saleinventory')->where('customer_id',$request['id'])->where('id',$request['jobid'])->first()))
        {
            if( DB::table('salepayment')->where('customer_id',$request['id'])->where('job_order_no',$request['jobid'])->first()) {
                $salerecord = 0;
            }
            else
            {
                $salerecord = 1;
            }
        }
        else
        {
            $salerecord = 0;
        }

        /*$saleid = DB::table('saleinventory')->select(DB::raw('group_concat(id) as jobid'))
            ->where('customer_id',$request['id'])->get();

        $cashjobid = DB::table('salepayment')->select(DB::raw('group_concat(job_order_no) as cashjobid'))
            ->where('customer_id',$request['id'])->get();

        $result['jobid'] =','. $saleid[0]->jobid . ',';
        $result['cashjobid'] = ',' . $cashjobid[0]->cashjobid . ',';*/

        return response()->json($salerecord, 200);
    }

    public function receipts(Request $request)
    {
        DB::table('salepayment')->insert(
            ['customer_id' => $request['customerid'], 'date' => $request['date'],
                'job_order_no' => $request['job_order_no'] , 'added_at' => now() , 'amount' => $request['amount'],
                'type' => "cash"]);

//        return redirect('/sale/receipt');
        $result = 1;

        return response()->json($result, 200);

    }
}
