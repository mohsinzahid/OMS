<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class JobOrderController extends Controller
{
    public function create()
    {
        //all customer will be listed unless walkincustomer is hidden by changing type 0 to type 1.
        //$customer = DB::table('customers')->where('type',1)->orderBy('name', 'asc')->get();
        $customer = DB::table('customers')->orderBy('name', 'asc')->get();
        $size = DB::table('size')->get();
        $walk = DB::table('customers')->where('type',0)->first();
        $employee = DB::table('employees')->get();

        return view('sales.forms.job order.create', ['customer' => $customer,'size' => $size, 'walk' => $walk, 'employee' => $employee]);
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
        $replaceval= $request['replace'];

        if($replaceval === null)
        {
            DB::table('saleinventory')->insert(
                ['customer_id' => $request['customer_id'], 'employee_id' => $request['employee'],
                    'dateofsale' => $request['dateofsale'], 'invoiceno' => $request['invoiceno'], 'status' => 0,
                    'added_at' => now(), 'paidamount' => 0, 'isreplace' => 0, 'total_amount' => $request['totalamount']]);
        }
        else
        {
            DB::table('saleinventory')->insert(
                ['customer_id' => $request['customer_id'], 'employee_id' => $request['employee'],
                    'dateofsale' => $request['dateofsale'], 'invoiceno' => $request['invoiceno'], 'status' => 0,
                    'added_at' => now(), 'paidamount' => 0, 'isreplace' => 1]
            );
        }

        $last_id = DB::getPdo()->lastInsertId();


        $arr = explode(",", $request['productItemArray']);

        for ($i = 0; $i < $request['productItemCount']; $i++) {
            $index = $arr[$i];



            DB::table('saleinventoryitem')->insert(
                ['size_id' => $request['data']['size'][$index], 'description' => $request['data']['description'][$index],
                    'color' => $request['data']['color'][$index], 'set' => $request['data']['set'][$index],
                    'saleinventory_id' => $last_id, 'saleprice' => $request['data']['saleprice'][$index]]);
        }

        if($request['customer_id'] === $request['walkid']) {
            DB::table('walkincustomer')->insert(
                ['name' => $request['walkname'],
                    'mobile' => $request['walkmobile'],
                    'email' => $request['walkemail'],
                    'saleinventory_id' => $last_id ]
            );
        }

        return redirect('/sales/forms/job-order');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();
        $walk = DB::table('customers')->where('type',0)->first();
        $inventory = DB::table('saleinventory')->where('id',$id)->first();
        $items = DB::table('saleinventoryitem')->where('saleinventory_id',$id)->get();
        $size = DB::table('size')->get();
        $walkincustomer = DB::table('walkincustomer')->where('saleinventory_id',$id)->first();
        $len =sizeof($items);
        $employee = DB::table('employees')->get();
        return view('sales.forms.job order.edit',['customer' => $customer, 'walk' => $walk, 'inventory' =>  $inventory,
            'walkincustomer' =>$walkincustomer, 'item' => $items, 'size' => $size, 'len' =>$len,
            'saleinventoryid' => $id , 'employee' => $employee]);
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
        DB::table('saleinventory')->where('id', $request['saleid'])->update(
            ['customer_id' => $request['customer_id'], 'dateofsale' => $request['dateofsale'],
                'invoiceno' => $request['invoiceno'], 'total_amount' => $request['total_amount'],
                'employee_id' => $request['employee_id']]
        );

        for($i =0 ; $i <$request['productItemCount']; $i++)
        {
            DB::table('saleinventoryitem')->where('id', $request['data']['itemid'][$i])->update(
                ['description' => $request['data']['description'][$i], 'saleprice' => $request['data']['saleprice'][$i],
                    'size_id' => $request['data']['size'][$i], 'set' => $request['data']['set'][$i],
                    'color' => $request['data']['color'][$i]]);
        }

        if($request['customer_id'] === $request['walkid'])
        {
            $walkincustomer = DB::table('walkincustomer')->where('saleinventory_id',$request['saleid'])->first();

            if($walkincustomer !== null)
            {
                DB::table('walkincustomer')->where('saleinventory_id', $request['saleid'])->update(
                    ['name' => $request['walkname'],
                        'mobile' => $request['walkmobile'],
                        'email' => $request['walkemail']]
                );
            }
            else
            {
                DB::table('walkincustomer')->insert(
                    ['name' => $request['walkname'],
                        'mobile' => $request['walkmobile'],
                        'email' => $request['walkemail'],
                        'saleinventory_id' => $request['saleid'] ]
                );
            }
        }
        elseif ($request['oldcustid'] === $request['walkid'])
        {
            DB::table('walkincustomer')->where('saleinventory_id', $request['saleid'])->delete();

        }

        return redirect('/form/edit');
    }

    public function getsaleprice(Request $request)
    {
        $setsaleprice = DB::table('rates')
            ->select('rates.saleprice','rates.size_id')
            ->where('rates.customer_id',$request['id'])
            ->get();

        $defaultsaleprice= DB::table('stock')
            ->select('size_id', 'saleprice')
            ->whereNotExists(function($query) use ($request)
            {

                /*                $query->DB::table('rates')->where('customer_id', $request['id'])->get();*/
                $query->select(DB::raw('*'))
                    ->from('rates')
                    ->whereRaw('rates.size_id = stock.size_id')
                    ->where('rates.customer_id', $request['id']);
            })
            ->get();

        if ($setsaleprice)
        {
            for($i =0 ;$i < sizeof($setsaleprice); $i++)
            {
                $result[$setsaleprice[$i]->size_id] = $setsaleprice[$i]->saleprice;
            }

        }
        if ($defaultsaleprice)
        {
            for($i =0 ;$i < sizeof($defaultsaleprice); $i++)
            {
                $result[$defaultsaleprice[$i]->size_id] = $defaultsaleprice[$i]->saleprice;
            }

        }
        return response()->json($result, 200);
    }

    public function destroy($id)
    {
        DB::table('saleinventory')->where('id',$id)->delete();
        return redirect('/form/edit');
    }

    public function AjaxSearch(Request $request)
    {
        $result = DB::table('saleinventory as sin')
            ->leftJoin('employees as e', 'sin.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("sin.id as id", "sin.dateofsale as date", "sin.invoiceno as invoice_no",
                "sin.total_amount as debit_amount", "sin.added_at as added_at","e.name as created_by",
                DB::raw("'' as remarks"), DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as type"))
            ->where('sin.invoiceno', $request['id'])
            ->get();

        return response()->json($result, 200);

    }

    public function printjoborder($id)
    {
        $items = DB::table('saleinventoryitem')
            ->join('size', 'saleinventoryitem.size_id', '=', 'size.id')
            ->select('saleinventoryitem.*', 'size.size')
            ->where('saleinventory_id',$id)
            ->get();

        $inventory = DB::table('saleinventory')->where('id',$id)->first();
        $walk = DB::table('customers')->select('id')->where('type',0)->first();

        if($inventory->customer_id === $walk->id)
        {
            $walkincustomer = DB::table('walkincustomer')->where('saleinventory_id',$id)->first();
        }
        else
        {
            $walkincustomer = DB::table('customers')->where('id',$inventory->customer_id)->first();
        }

        $len =sizeof($items);

        return view('jobs list.print job order',['walk' => $walk, 'inventory' =>  $inventory, 'walkincustomer' =>$walkincustomer, 'item' => $items, 'len' =>$len, 'saleinventoryid' => $id]);
    }


}
