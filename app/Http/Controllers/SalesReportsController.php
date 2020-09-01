<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportsController extends Controller
{
    public function CustomerLedgerReport()
    {
        $customer = DB::table('customers')->orderBy('name', 'asc')->get();
        $walk = DB::table('customers')->where('type',0)->first();
        return view('sales.reports.customerLedgerReport', ['customer' => $customer, 'walk' => $walk]);
    }

    public function CustomerLedgerReportAjaxUpdate(Request $request)
    {
        $Start_date = $request['start'];
        $End_date = $request['end'];

        $prevdebitsale = DB::table('saleinventory')->where('customer_id', $request['id'])
            ->where('status', 1)
            ->where('saleinventory.dateofsale', '<', $Start_date)
            ->sum('saleinventory.total_amount');

        $prevcreditadj = DB::table('customeradjustment')->where('customer_id', $request['id'])
            ->where('date', '<', $Start_date)
            ->where('type', 'credit')
            ->sum('amount');

        $prevdebitadj = DB::table('customeradjustment')->where('customer_id', $request['id'])
            ->where('date', '<', $Start_date)
            ->where('type', 'debit')
            ->sum('amount');


        //mass paid
        $prevcreditsale =  DB::table('salepayment as sp')->where('customer_id', $request['id'])
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->where('date', '<', $Start_date)
            ->sum(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END'));

        $first = DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->leftJoin('saleinventory as sin', 'sp.job_order_no', '=', 'sin.id')
            ->select("sp.id as id", "sp.date as date",
                (DB::raw("CASE WHEN sp.invoiceno iS NULL THEN sin.invoiceno ELSE sp.invoiceno END as invoice_no")),
                DB::raw("0 as debit_amount"),
                (DB::raw("CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END as credit_amount")),
                DB::raw("'CC' as formtype"),"sp.added_at as added_at", "ci.cheque_no as cheque_no",
                "ci.cheque_date as cheque_date",DB::raw("'' as created_by"),DB::raw("'' as remarks"),DB::raw("0 as net_amount"))
            ->where('sp.customer_id', $request['id'])
            ->where('sp.date', '>=', $Start_date)
            ->where('sp.date', '<=', $End_date);


        $second = DB::table('saleinventory as sin')
            ->leftJoin('employees as e', 'sin.employee_id', '=', 'e.id')
            ->select("sin.id as id", "sin.dateofsale as date", "sin.invoiceno as invoice_no",
                "sin.total_amount as debit_amount",DB::raw("0 as credit_amount"),DB::raw("'JO' as formtype"),
                "sin.added_at as added_at",DB::raw("'' as cheque_no"),DB::raw("'' as cheque_date"),"e.name as created_by",
                DB::raw("'' as remarks"),DB::raw("0 as net_amount"))
            ->where('sin.customer_id', $request['id'])
            ->where('sin.status', 1)
            ->where('sin.dateofsale', '>=', $Start_date)
            ->where('sin.dateofsale', '<=', $End_date);


        $result = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",DB::raw("0 as net_amount"))
            ->where('caf.customer_id', $request['id'])
            ->where('caf.date', '>=', $Start_date)
            ->where('caf.date', '<=', $End_date)
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $openingbalance = DB::table('customers')->where('id', $request['id'])
            ->select('prevbalance')->first();
        $result['openingbalance'] = ($openingbalance ->prevbalance + $prevdebitsale +$prevdebitadj) - ($prevcreditsale + $prevcreditadj);

        /*$temp = $result['openingbalance'] ;
        foreach ($result as $results)
        {
//            $results ->net_amount = (($results ->debit_amount) - $results->credit_amount);
//            $temp = $results->debit_amount;
            $results->nets_amount = 0;
        }*/
        return response()->json($result, 200);
    }

    public function statement()
    {
        $customer = DB::table('customers')->where('status',1)->orderBy('name', 'asc')->get();
        $walk = DB::table('customers')->where('type',0)->first();

        return view('sales.reports.customerLedgerDetail', ['customer' => $customer, 'walk' => $walk]);
    }

    public function CustomerLedgerDetailAjaxUpdate(Request $request)
    {
        $result = DB::table('saleinventory')
            ->join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
            ->join('size', 'saleinventoryitem.size_id', '=', 'size.id')
            ->join('employees', 'saleinventory.employee_id', '=', 'employees.id')
            ->select('saleinventory.*','saleinventoryitem.*', 'size.size', 'employees.name')
            ->where('saleinventory.status',1)
            ->where('saleinventory.customer_id',$request['id'])
            ->where('saleinventory.dateofsale','>=', $request['start'])
            ->where('saleinventory.dateofsale','<=', $request['end'])
            ->ORDERBY('saleinventory.dateofsale')
            ->get();

        return response()->json($result, 200);
    }

    public function WalkCustomerLedger(Request $request)
    {
        $result = DB::table('saleinventory')
            ->Join('employees as e', 'saleinventory.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'saleinventory.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'saleinventory.id', '=', 'w.saleinventory_id')
            ->leftJoin('salepayment as sp', 'saleinventory.id', '=', 'sp.job_order_no')
            ->select("saleinventory.id as id", "saleinventory.dateofsale as date", "saleinventory.invoiceno as invoice_no",
                "saleinventory.added_at as added_at","e.name as created_by",
                DB::raw("w.name as name"),"saleinventory.total_amount as amount",
                DB::raw("'Walk In Customer' as type"),
                DB::raw("CASE WHEN sp.job_order_no = saleinventory.id THEN 'paid' ELSE 'unpaid' END as status"))
            ->where('saleinventory.dateofsale', '>=',  $request['start'])
            ->where('saleinventory.dateofsale', '<=',  $request['end'])
            ->where('c.type',0)
            ->ORDERBY('added_at')
            ->get();

        return response()->json($result, 200);
    }

    public function CustomerReceivableAjaxUpdate(Request $request){
        $End_date = $request['end'];
        $customer = DB::table('customers')->orderBy('name', 'asc')->get();

        foreach ($customer as $value) {
            $data['name'] = $value->name;
            $prevdebitsale = DB::table('saleinventory')->where('customer_id', $value->id)
                ->where('status', 1)
                ->where('saleinventory.dateofsale', '<=', $End_date)
                ->sum('saleinventory.total_amount');

            //mass paid
            $prevcreditsale =  DB::table('salepayment as sp')->where('customer_id', $value->id)
                ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
                ->where('date', '<=', $End_date)
                ->sum(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END'));

            $prevcreditadj = DB::table('customeradjustment')->where('customer_id', $value->id)
                ->where('date', '<=', $End_date)
                ->where('type', 'credit')
                ->sum('amount');

            $prevdebitadj = DB::table('customeradjustment')->where('customer_id', $value->id)
                ->where('date', '<=', $End_date)
                ->where('type', 'debit')
                ->sum('amount');

            $balance = DB::table('customers')->where('id', $value->id)
                ->select('prevbalance')->first();

            $data['balance'] = ($balance ->prevbalance + $prevdebitsale +$prevdebitadj) -
                ($prevcreditsale + $prevcreditadj);

            $last = DB::table('salepayment as sp')->where('customer_id', $value->id)
                ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
                ->latest('added_at')
                ->first();
//                ->first(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END'));
//            $data['lastPaymentAmount'] = $last->amount +  $last->tax_amount;
            $data['lastPaymentAmount'] = $last->amount;
            $data['lastPaymentDate'] = $last->date;
            $data['last'] = $last;
            $result[] = $data;
        }

//        $last = DB::table('salepayment')->latest('added_at')->first();


        return response()->json($result, 200);
    }
}