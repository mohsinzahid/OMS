<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $size = DB::table('size')->get();
        return view('trading.forms.inventory opening.add', ['size' => $size]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('size')->insert(['size' => $request['size']]);
        $last_id = DB::getPdo()->lastInsertId();

//        $result = DB::table('stock')->where('size_id', $request['id'])->first();
//        if($result== null)
//            DB::table('stock')
//                ->insert(['size_id' => $request['id'],'quantity'=>$request['quantity'], 'saleprice' =>$request['saleprice']]);
//        else
//            DB::table('stock')
//                ->where('size_id',$request['id'])
//                ->update(['quantity'=>($request->quantity + $result->quantity), 'saleprice' =>$request['saleprice']]
//                );
        DB::table('stock')
                ->insert(['size_id' => $last_id,'quantity'=>$request['quantity'], 'saleprice' =>$request['saleprice']]);
        return redirect('/stock/add');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = DB::table('stock')->where('size_id', $id)->first();
        $size = DB::table('size')->where('id', $id)->first();

        return view('trading.forms.inventory opening.edit', ['stock' => $stock,'size'=> $size]);
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

        DB::table('stock')
            ->where('id',$request['stockid'])
            ->update(['quantity'=>$request['quantity'], 'saleprice' =>$request['saleprice']]
            );
        DB::table('size')
            ->where('id', $request['sizeid'])
            ->update(['size' => $request['sizename']]);

        return redirect('/form/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
