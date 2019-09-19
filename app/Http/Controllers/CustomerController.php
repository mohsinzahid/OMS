<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    public function addprevbalance()
    {

    }

    public function build()
    {
        $customer = DB::table('customers')->where('type', 0)->first();
        if($customer === null)
        {
            DB::table('customers')
                ->insert(['name' => 'Walk-In-Customer',
                    'short_name' => 0,
                    'sales_tax_no' => 0,
                    'ntn_no' => 0,
                    'credit_limit' => 0,
                    'contact_name' => 0,
                    'city' => 0,
                    'country' => 0,
                    'address'=> 0,
                    'phone' => 0,
                    'email' => 0,
                    'mobile' => 0,
                    'status' => 1, //status 1 defines active and 0 defines hide
                    'type' => 0, //type 0 defines walk in customer and there will be only one row of this in Customer table
                    'prevbalance' => 0]);
        }
        return view('trading.forms.customer opening.add', ["msg"=>'']);
    }

    public function addsaleprice(Request $request)
    {
        DB::table('rates')
            ->insert(['customer_id' => $request['customer_id'],
                'size_id'=>$request['sizeid'],
                'saleprice' =>$request['saleprice']]);

        return redirect(url('/customer/saleprice'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = DB::table('customers')->where('type', 1)->get();
        return view('customer.index', ['customer' => $customer]);
    }

    /**
     * Show the forms for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = DB::table('customers')->where('status',1)
                                ->where('type',1)
                                ->orderBy('name', 'asc')
                                ->get();

        return view('trading.forms.set sale price.index', ['customer' => $customer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::table('customers')
            ->insert(['name' => $request['name'],
                        'short_name' => $request['shortname'],
                        'sales_tax_no' => $request['salestaxno'],
                        'ntn_no' => $request['ntnno'],
                        'credit_limit' => $request['creditlimit'],
                        'contact_name' => $request['contactname'],
                        'address'=>$request['address'],
                        'phone' =>$request['phone'],
                        'mobile' =>$request['mobile'],
                        'email' =>$request['email'],
                        'city' => $request['city'],
                        'country' => $request['country'],
                        'status' =>$request['status'],
                        'prevbalance' =>$request['prevbalance'],
                        'type' => 1]);

        return view('trading.forms.customer opening.add', ["msg"=>'Customer']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $result['rates'] = DB::table('rates')
            ->join('size', 'rates.size_id', '=', 'size.id')
            ->select('rates.saleprice','rates.size_id','rates.id' ,'size.size')
            ->where('rates.customer_id',$request['id'])
            ->get();

        $result['size']= DB::table('size')
            ->whereNotExists(function($query) use ($request)
            {

                /*                $query->DB::table('rates')->where('customer_id', $request['id'])->get();*/
                $query->select(DB::raw('*'))
                    ->from('rates')
                    ->whereRaw('rates.size_id = size.id')
                    ->where('rates.customer_id', $request['id']);
            })
            ->get();
        return response()->json($result, 200);

    }

    /**
     * Show the forms for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = DB::table('customers')->where('id', $id)->first();

        return view('trading.forms.customer opening.edit',['customer' => $customer]);
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
        DB::table('customers')->where('id',$request['id'])
            ->update(['name' => $request['name'],
                'short_name' => $request['shortname'],
                'sales_tax_no' => $request['salestaxno'],
                'ntn_no' => $request['ntnno'],
                'credit_limit' => $request['creditlimit'],
                'contact_name' => $request['contactname'],
                'address'=>$request['address'],
                'phone' =>$request['phone'],
                'mobile' =>$request['mobile'],
                'email' =>$request['email'],
                'city' => $request['city'],
                'country' => $request['country'],
                'status' =>$request['status'],
                'prevbalance' =>$request['prevbalance']]);

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
        DB::table('rates')->where('id', $id)->delete();
        return redirect(url('/customer/saleprice'));
    }
}
