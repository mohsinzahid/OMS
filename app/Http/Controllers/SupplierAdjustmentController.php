<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SupplierAdjustmentController extends Controller
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
        $vendor = DB::table('vendor')->orderBy('name', 'asc')->get();
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();
        $size = DB::table('size')->get();

        return view('purchases.forms.supplier adjustment.add', ['vendor' => $vendor , 'size' =>$size, 'gl' => $gl],
            ['msg'=>'']);
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
        if ($request['quantity'])
        {
            DB::table('supplieradjustment')->insert(
                ['supplier_id' => $request['supplierid'],'purchase_order_no' => $request['purchaseorderno'],
                    'date' => $request['paiddate'], 'amount' => $request['amount'], 'added_at' => now(),
                    'type' => $request['type'], 'general_ledger_id' => $request['generalledger'],
                    'size_id' => $request['size'], 'quantity' => $request['quantity'],
                    'remarks' => $request['remarks']]);
        }
        else
        {
            DB::table('supplieradjustment')->insert(
                ['supplier_id' => $request['supplierid'],'purchase_order_no' => $request['purchaseorderno'],
                    'date' => $request['paiddate'], 'amount' => $request['amount'], 'added_at' => now(),
                    'type' => $request['type'], 'general_ledger_id' => $request['generalledger'],
                    'remarks' => $request['remarks']]);
        }


        $vendor = DB::table('vendor')->orderBy('name', 'asc')->get();
        $size = DB::table('size')->get();
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();


        return view('purchases.forms.supplier adjustment.add',['vendor' => $vendor , 'size' =>$size, 'gl' => $gl],['msg'=>'done']);
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
        $sadj= DB::table('supplieradjustment')->where('id', $id)->first();
        $vendor = DB::table('vendor')->orderBy('name', 'asc')->get();
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();
        $size = DB::table('size')->get();


        return view('purchases.forms.supplier adjustment.edit', ['vendor' => $vendor , 'size' =>$size, 'gl' => $gl,
            'sadj' => $sadj], ['msg'=>'']);
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
        DB::table('supplieradjustment')->where('id', $request['id'])
            ->update(['supplier_id' => $request['supplierid'],'purchase_order_no' => $request['purchaseorderno'],
                'date' => $request['paiddate'], 'amount' => $request['amount'], 'size_id' => $request['size'],
                'type' => $request['type'], 'general_ledger_id' => $request['generalledger'], 'quantity' => $request['quantity'],
                'remarks' => $request['remarks']]);

        return redirect('form/edit');
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
        DB::table('supplieradjustment')->where('id', $id)->delete();
        return redirect('form/edit');
    }

    public function getinvoice(Request $request)
    {
        $saleid = DB::table('purchaseinventory')->select(DB::raw('group_concat(id) as purchaseid'))
            ->where('vendor_id',$request['id'])->get();
//        echo $saleid;
//        foreach($saleid->id as $ids){
//            $arr=explode($ids," ");
//        }
//        echo $arr;
        return response()->json($saleid, 200);
    }
}
