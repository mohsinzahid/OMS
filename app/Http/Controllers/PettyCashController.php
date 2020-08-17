<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PettyCashController extends Controller
{

    public function store(Request $request)
    {
//        dd($request);
        DB::table('pettycashpayment')->insert(
            ['date' => $request['date'], 'added_at' => DB::raw('NOW()') , 'amount' => $request['amount'],
                'remarks' => $request['remarks']]);
            //return view('general ledger.forms.petty cash payment.add',['msg'=>'Payment added Successfully']);
        return redirect('/petty-cash/add');
    }

    public function edit($id)
    {
        $result = DB::table('pettycashpayment')->where('id',$id)->first();

        return view('general ledger.forms.petty cash payment.edit',['result' => $result]);
    }

    public function update(Request $request)
    {
//        dd($request);
        DB::table('pettycashpayment')->where('id',$request['id'])
            ->update(['date' => $request['date'], 'amount' => $request['amount'], 'remarks' => $request['remarks']]);

        return redirect('/form/edit');
    }

    public function destroy(Request $request)
    {
//        dd($request);
        DB::table('pettycashpayment')->where('id',$request['id'])->delete();

        return redirect('/form/edit');
    }

}
