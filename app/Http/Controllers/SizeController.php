<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $size = DB::table('size')->get();
        return view('stock.size.add', ['size' => $size]);
    }

    /**
     * Show the forms for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('size')->insert(
            ['size' => $request['size']]
        );

        /*$users = DB::table('size')->select('id')->where('size',$request['size'])->get();

        DB::table('stock')->insert(
            ['size_id' => $users['id'],'quantity'=>0,'saleprice'=>0]
        );*/

        return redirect('/stock/size');

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
        $result = DB::table('size')->where('id', $id)->first();
        return view('stock.size.edit', ['size' => $result]);
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
        DB::table('size')
            ->where('id', $request['id'])
            ->update(['size' => $request['size']]);

        return redirect('/stock/size');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
