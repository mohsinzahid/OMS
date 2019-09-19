<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalesFormsController extends Controller
{
    public function getreceipt(Request $request)
    {
        $Start_date = $request['start'];
        $End_date = $request['end'];
        if($request['id'] === $request['walkid'])
        {
            $result = DB::table('walkincustomer')
                ->join('saleinventory', 'walkincustomer.saleinventory_id', '=', 'saleinventory.id')
                ->select('walkincustomer.name','saleinventory.id','saleinventory.dateofsale' ,'saleinventory.invoiceno' , 'saleinventory.total_amount')
                ->where('saleinventory.customer_id',$request['id'])
                ->whereNull('saleinventory.paidamount')
                ->orWhere('saleinventory.paidamount',0)
                ->where('saleinventory.dateofsale', '>=', $Start_date)
                ->where('saleinventory.dateofsale', '<=', $End_date)
                ->get();
        }
        else
        {
            $result = DB::table('customers')
                ->join('saleinventory', 'customers.id', '=', 'saleinventory.customer_id')
                ->select('customers.name','saleinventory.id','saleinventory.dateofsale' ,'saleinventory.invoiceno' , 'saleinventory.total_amount')
                ->where('saleinventory.customer_id',$request['id'])
                ->whereNull('saleinventory.paidamount')
                ->orWhere('saleinventory.paidamount',0)
                ->where('saleinventory.dateofsale', '>=', $Start_date)
                ->where('saleinventory.dateofsale', '<=', $End_date)
                ->get();
        }
        return response()->json($result, 200);
    }

    public function receipt()
    {
        $customer = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();
        $walk = DB::table('customers')->where('type',0)->first();
        return view('sales.forms.receipt', ['customer' => $customer, 'walk' => $walk]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */




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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        dd($request);
//        foreach($request['id'] as $k => $v){
        foreach($request['amount'] as $k => $v){
            if(!empty($v))
            {
                DB::table('saleinventory')->where('id', $k)
                    ->update(['paidamount' => $request['amount'][$k]]);
            }
        }
        return redirect('/sale/receipt');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
