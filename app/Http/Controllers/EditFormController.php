<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditFormController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
//        dd($request);
        if ($request['formid'] === 'jo') {
            return redirect('/job-order/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'cc') {
            return redirect('/cash-collection/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'cadj') {
            return redirect('/customer-adjustment/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'po') {
            return redirect('/purchase-order/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'cp') {
            return redirect('/cash-payment/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'sadj') {
            return redirect('/supplier-adjustment/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'wa') {
            return redirect('/wastage/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'gladj') {
            return redirect('/general-ledger/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'pc') {
            return redirect('/petty-cash/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'so') {
            return redirect('/supplier/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'co') {
            return redirect('/customer/' . $request['cgn'] . '/edit');
        } else if ($request['formid'] === 'io') {
            return redirect('/stock/' . $request['cgn'] . '/edit');
        }
    }

    public function ajaxgetids(Request $request)
    {
       /* $saleid = DB::table($request['table'])
            ->select(DB::raw('group_concat(id) as jobid'))
            ->get();*/

        if( DB::table($request['table'])->where('id',$request['jobid'])->first())
        {
            $salerecord = 1;
        }
        else
        {
            $salerecord = 0;
        }

//        $saleid = DB::select(DB::raw("SELECT group_concat(id) as jobid from ".$request['table']));

//        $saleid = 0;

//        echo ($request['table']);

        return response()->json($salerecord, 200);

    }

}