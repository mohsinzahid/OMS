<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralLedgerAdjustmentController extends Controller
{
    public function Create()
    {
//        dd($request);
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();

        return view('general ledger.forms.general ledger adjustment.add',['gl' => $gl],['msg'=>'']);

    }

    public function store(Request $request)
    {
//        dd($request);
        DB::table('gladjustment')->insert(
            ['added_at' => now() , 'amount' => $request['amount'], 'remarks' => $request['remarks'],
                'debit_gl' => $request['debitgl'], 'credit_gl' => $request['creditgl'], 'date' => $request['date']]);

        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();
        return view('general ledger.forms.general ledger adjustment.add',['gl' => $gl],['msg'=>'adjustment added Successfully']);
    }

    public function edit($id)
    {
        $result = DB::table('gladjustment')->where('id',$id)->first();
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();

        return view('general ledger.forms.general ledger adjustment.edit',['result' => $result,'gl' => $gl]);
    }

    public function update(Request $request)
    {
//        dd($request);
        DB::table('gladjustment')->where('id',$request['id'])->update(
            ['amount' => $request['amount'], 'remarks' => $request['remarks'], 'debit_gl' => $request['debitgl'],
                'credit_gl' => $request['creditgl'], 'date' => $request['date']]);

        return redirect('/form/edit');
    }

    public function destroy(Request $request)
    {
//        dd($request);
        DB::table('gladjustment')->where('id',$request['id'])->delete();

        return redirect('/form/edit');
    }
}
