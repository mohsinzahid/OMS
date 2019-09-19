<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAdjustmentController extends Controller
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
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();

//        return view('sales.forms.customer adjustment.add',['customer' => $customers, 'saleid' => $saleid],['msg'=>'']);
        return view('sales.forms.customer adjustment.add',['customer' => $customers, 'gl' => $gl],['msg'=>'']);
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
        DB::table('customeradjustment')->insert(
            ['customer_id' => $request['customerid'],'invoice_no' => $request['joborderno'],
                'date' => $request['paiddate'], 'amount' => $request['amount'], 'added_at' => now(),
                'type' => $request['type'], 'general_ledger_id' => $request['generalledger'],
                'remarks' => $request['remarks']]);

        $customers = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();


        return view('sales.forms.customer adjustment.add',['customer' => $customers, 'gl' => $gl],['msg'=>'done']);
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
     * Show the forms for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();
        $adj = DB::table('customeradjustment')->where('id',$id)->first();
        $customers = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();

        return view('sales.forms.customer adjustment.edit',['gl' => $gl, 'adj' => $adj, 'customer' => $customers],['msg'=>'']);
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
        DB::table('customeradjustment')->where('id', $request['id'])->update(
            ['customer_id' => $request['customerid'],'invoice_no' => $request['joborderno'],
                'date' => $request['paiddate'], 'amount' => $request['amount'], 'added_at' => now(),
                'type' => $request['type'], 'general_ledger_id' => $request['generalledger'],
                'remarks' => $request['remarks']]);

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
//        dd($id);
        DB::table('customeradjustment')->where('id', $id)->delete();
        return redirect('/form/edit');
    }

    public function getinvoice(Request $request)
    {
//        $saleid = DB::table('saleinventory')->select('id')->where('customer_id',$request['id'])->get();
        $saleid = DB::table('saleinventory')->select(DB::raw('group_concat(id) as jobid'))
                                            ->where('customer_id',$request['id'])->get();
//        echo $saleid;
//        foreach($saleid->id as $ids){
//            $arr=explode($ids," ");
//        }
//        echo $arr;
        return response()->json($saleid, 200);
    }
}
