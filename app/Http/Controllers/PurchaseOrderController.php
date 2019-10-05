<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PurchaseOrderController extends Controller
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
        $size = DB::table('size')->get();

        return view('purchases.forms.purchase order.add',['vendor' => $vendors,'size'=>$size]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $totalamount =array_sum($request['data']['amount']);
        DB::table('purchaseinventory')->insert(
            ['vendor_id' => $request['vendor_id'], 'dateofpurchase' => $request['dateofpurchase'] ,
                'invoice_no' => $request['invoiceno'] , 'added_at' => DB::raw('NOW()') , 'total_amount' => $totalamount,
                'received_by' => $request['receivedby'], 'remarks' => $request['remarks']]);

        $last_id =DB::getPdo()->lastInsertId();

        $num =0;

        $arr=explode(",",$request['productItemArray']);


        for($i=1;$i<=$request['productItemCount'];$i++) {
            $index = $arr[$num];
            DB::table('purchaseinventoryitem')->insert(
                ['quantity' => $request['data']['quantity'][$index], 'size_id' => $request['data']['size'][$index],
                    'costprice' => $request['data']['costprice'][$index], 'purchaseinventory_id' =>  $last_id]);

            $quantity =$request['data']['quantity'][$index];

            $result = DB::table('stock')->where('size_id', $request['data']['size'][$index])->first();
            if($result == null)
                DB::table('stock')
                    ->insert(['size_id' => $request['data']['size'][$index],'quantity'=>$quantity]);
            else
                DB::table('stock')
                    ->where('size_id',$request['data']['size'][$index])
                    ->update(['quantity'=>($quantity + $result->quantity)]
                    );
            $num++;
        }
        return redirect('/purchase/add');
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
        $inventorys = DB::table('purchaseinventory')->where('id', $id)->first();
        $items = DB::table('purchaseinventoryitem')->where('purchaseinventory_id', $id)->get();
        $vendors = DB::table('vendor')->select('name', 'id')->get();
        $size = DB::table('size')->get();

        $len = sizeof($items);
        return view('purchases.forms.purchase order.edit',['vendor' => $vendors,'size'=>$size , 'inventory' => $inventorys,
            'item' => $items, 'itemlength' => $len]);
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
        DB::table('purchaseinventory')->where('id', $request['inventoryid'])
            ->update(['vendor_id' => $request['vendor_id'],
                'dateofpurchase' => $request['dateofpurchase'],
                'invoice_no' => $request['invoiceno'],
                'received_by' => $request['receivedby'],
                'added_at' => DB::raw('NOW()'),
                'remarks' => $request['remarks'],
                'total_amount' => $request['totalamount']]);

        $num =0;
        $i=1;
        $arr=explode(",",$request['productItemArray']);

        for(;$i<=$request['productItemOldCount'];$i++) {    //updating previously inserted rows
            $index = $arr[$num];
            DB::table('purchaseinventoryitem')
                ->where('id', $request['data']['itemid'][$index])
                ->update(
                    ['quantity' => $request['data']['quantity'][$index], 'size_id' => $request['data']['size'][$index],
                        'costprice' => $request['data']['costprice'][$index]]
                );

        }

        //inserted newly entered row
        for(;$i<=$request['productItemCount'];$i++) {
            $index = $arr[$num];
            DB::table('purchaseinventoryitem')->insert(
                ['quantity' => $request['data']['quantity'][$index], 'size_id' => $request['data']['size'][$index],
                    'costprice' => $request['data']['costprice'][$index],
                    'purchaseinventory_id' => $request['inventoryid']]
            );
            $num++;
        }

        //deleting old record from the database after deleting that row from front end

        $len = $request['productItemOldTotal'] - $request['productItemOldCount'];
        $arr=explode(",",$request['deleteproductItemID']);

        for($i=0;$i< $len;$i++) {

            DB::table('purchaseinventoryitem')->where('id', $arr[$i])->delete();
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
        DB::table('purchaseinventory')->where('id', $id)->delete();
        return redirect('/form/edit');
    }
}
