<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = DB::table('vendor')->get();
        return view('vendor.index', ['vendors' => $vendors]);
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

            DB::table('vendor')->insert(
            ['name' => $request['name'], 'short_name' => $request['shortname'], 'sales_tax_no' =>
                $request['salestaxno'], 'ntn_no' => $request['ntnno'], 'credit_limit' => $request['creditlimit'],
                'contact_name' => $request['contactname'], 'email' => $request['email'], 'mobile' => $request['mobile'],
                'phone' => $request['phone'] , 'address'=>$request['address'], 'city' => $request['city'],
                'country' => $request['country'], 'prevbalance' => $request['prevbalance']]
        );

      return view('trading.forms.supplier opening.add', ['msg'=>'Vendor added Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $result = DB::table('vendor')->where('id', $id)->first();

        return view('trading.forms.supplier opening.edit', ['result' => $result]);

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
        DB::table('vendor')
            ->where('id', $request['id'])
            ->update(['name' => $request['name'], 'short_name' => $request['shortname'], 'sales_tax_no' =>
                $request['salestaxno'], 'ntn_no' => $request['ntnno'], 'credit_limit' => $request['creditlimit'],
                'contact_name' => $request['contactname'], 'email' => $request['email'], 'mobile' => $request['mobile'],
                'phone' => $request['phone'] , 'address'=>$request['address'], 'city' => $request['city'],
                'country' => $request['country'], 'prevbalance' => $request['prevbalance']]);

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
        DB::table('vendor')->where('id',$id)->delete();
        return redirect('/form/edit');


    }
}
