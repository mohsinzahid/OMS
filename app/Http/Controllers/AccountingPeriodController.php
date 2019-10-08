<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingPeriodController extends Controller
{
    public function getAccountingPeriod()
    {
        $closed_period = DB::table('closedperiod')->first();
        return view('accounting period.closeDate',['result' => $closed_period]);
    }

    public function updateAccountingPeriod(Request $request)
    {
        DB::table('closedperiod')->where('id', 1)->update(
            ['closed_date' => $request['closeddate']]
        );
        return redirect('/accounting/close-date');

    }
}
