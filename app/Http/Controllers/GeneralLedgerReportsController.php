<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GeneralLedgerReportsController extends Controller
{
    public function GeneralLedgerReports()
    {
        $gl = DB::table('glaccounts')->orderBy('name', 'asc')->get();

        return view('general ledger.reports.generalLedger',['gl' => $gl],['msg'=>'']);

    }

    public function GetGeneralLedgerAjax(Request $request)
    {
        if($request['id'] == 1)
        {
            $result = $this->sales($request);
        }
        elseif ($request['id'] == 2)
        {
            $result = $this->cash($request);
        }

        elseif ($request['id'] == 3)
        {
            $result = $this->bank($request);
        }

        elseif ($request['id'] == 4)
        {
            $result = $this->purchases($request);
        }
        elseif ($request['id'] == 5)
        {
            $result = $this->wastage($request);
        }
        elseif ($request['id'] == 6)
        {
            $result = $this->pettycash($request);
        }
        elseif ($request['id'] == 7)
        {
            $result = $this->capital($request);
        }
        elseif ($request['id'] == 8)
        {
            $result = $this->discount($request);
        }
        elseif ($request['id'] == 9)
        {
            $result = $this->incometax($request);
        }
        elseif ($request['id'] == 10)
        {
            $result = $this->baddebt($request);
        }

       return response()->json($result, 200);

    }

    public function sales(Request $request)
    {
        $prevsale = DB::table('saleinventory')
            ->where('status', 1)
            ->where('saleinventory.dateofsale', '<', $request['start'])
            ->sum('saleinventory.total_amount');

        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $openingbalance = ($prevdebitcadj + $prevdebitgl) - $prevsale - $prevcreditcadj - $prevcreditgl;


        $first = DB::table('saleinventory as sin')
            ->leftJoin('employees as e', 'sin.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("sin.id as id", "sin.dateofsale as date", "sin.invoiceno as invoice_no",
                DB::raw("0 as debit_amount"),"sin.total_amount as credit_amount",DB::raw("'JO' as formtype"),
                "sin.added_at as added_at",DB::raw("'' as cheque_no"),DB::raw("'' as cheque_date"),"e.name as created_by",
                DB::raw("'' as remarks"),DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('sin.status', 1)
            ->where('sin.dateofsale', '>=', $request['start'])
            ->where('sin.dateofsale', '<=', $request['end']);

        $second = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                    $result->where('glaf.debit_gl', $request['id'])
                        ->orwhere('glaf.credit_gl', $request['id']);
            });
//            ->where('glaf.debit_gl', $request['id'])
//            ->orwhere('glaf.credit_gl', $request['id']);

        $result = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'caf.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id'])
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;

        return response($result);
    }

    public function cash(Request $request)
    {
        $prevdebitsalepayment =  DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->where('date', '<', $request['start'])
            ->sum(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END'));

        $prevcreditpurchasepayment =  DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->where('paiddate', '<', $request['start'])
            ->sum(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN pp.amount ELSE pp.amount + ci.tax_amount END'));

        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');

        $prevdebitsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'credit')
            ->sum('supplieradjustment.amount');

        $prevcreditpettycash = DB::table('pettycashpayment')
            ->where('pettycashpayment.date', '<', $request['start'])
            ->sum('pettycashpayment.amount');

        $openingbalance = ($prevdebitsalepayment + $prevdebitgl +$prevdebitcadj +$prevdebitsadj)-
                            ($prevcreditpurchasepayment + $prevcreditgl +$prevcreditcadj +$prevcreditsadj + $prevcreditpettycash);

        $first = DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->leftJoin('saleinventory as sin', 'sp.job_order_no', '=', 'sin.id')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("sp.id as id", "sp.date as date",
                (DB::raw("CASE WHEN sp.invoiceno iS NULL THEN sin.invoiceno ELSE sp.invoiceno END as invoice_no")),
                (DB::raw("CASE WHEN ci.tax_amount iS NULL THEN sp.amount ELSE sp.amount + ci.tax_amount END as debit_amount")),
                DB::raw("0 as credit_amount"), DB::raw("'CC' as formtype"),"sp.added_at as added_at", "ci.cheque_no as cheque_no",
                "ci.cheque_date as cheque_date",DB::raw("'' as created_by"),DB::raw("'' as remarks"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN w.name WHEN sp.invoiceno iS NULL AND 
                    c.type != 0 THEN c.name ELSE '' END as customer_name"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN 'Walk In Customer' WHEN sp.invoiceno iS NULL
                    AND c.type != 0 THEN 'Credit customer' ELSE '' END as customer_type"))
            ->where('sp.date', '>=', $request['start'])
            ->where('sp.date', '<=', $request['end']);

        $second = DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->select("pp.id as id", "pp.paiddate as date","pp.invoiceno as invoice_no",
                DB::raw("0 as debit_amount"),
                (DB::raw("CASE WHEN ci.tax_amount iS NULL THEN pp.amount ELSE pp.amount + ci.tax_amount END as credit_amount")),
                DB::raw("'CP' as formtype"),"pp.added_at as added_at", "ci.cheque_no as cheque_no",
                "ci.cheque_date as cheque_date",DB::raw("'' as created_by"),DB::raw("'' as remarks"),
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('pp.paiddate', '>=', $request['start'])
            ->where('pp.paiddate', '<=', $request['end']);

        $third = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            });
//            ->where('glaf.date', '>=', $request['start'])
//            ->where('glaf.date', '<=', $request['end'])
//            ->where('glaf.debit_gl', $request['id'])
//            ->orwhere('glaf.credit_gl', $request['id']);

        $fourth = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end']);

        $fifth = DB::table('pettycashpayment as pcp')
            ->select("pcp.id as id", "pcp.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("0 as debit_amount")), (DB::raw("pcp.amount as credit_amount")), DB::raw("'PCF' as formtype"),
                "pcp.added_at as added_at", DB::raw("'' as cheque_no"), DB::raw("'' as cheque_date"),
                DB::raw("'' as created_by"), "pcp.remarks as remarks", DB::raw("'' as customer_name"),
                DB::raw("'' as customer_type"))
            ->where('pcp.date', '>=', $request['start'])
            ->where('pcp.date', '<=', $request['end']);

        $result = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'caf.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id'])
            ->union($first)
            ->union($second)
            ->union($third)
            ->union($fourth)
            ->union($fifth)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;
        return response($result);
    }

    public function bank(Request $request)
    {
        $prevdebitsalepayment =  DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->where('date', '<', $request['start'])
            ->where('type', 'cheque')
            ->sum(DB::raw('sp.amount'));

        $prevcreditpurchasepayment =  DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->where('paiddate', '<', $request['start'])
            ->where('type', 'cheque')
            ->sum(DB::raw('pp.amount'));

        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');

        $prevdebitsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'credit')
            ->sum('supplieradjustment.amount');

        $openingbalance = ($prevdebitsalepayment + $prevdebitgl +$prevdebitcadj +$prevdebitsadj)-
            ($prevcreditpurchasepayment + $prevcreditgl +$prevcreditcadj +$prevcreditsadj);

        $first = DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->leftJoin('saleinventory as sin', 'sp.job_order_no', '=', 'sin.id')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("sp.id as id", "sp.date as date", (DB::raw(" sp.invoiceno as invoice_no")),
                "sp.amount as debit_amount", DB::raw("0 as credit_amount"),
                DB::raw("'CC' as formtype"),"sp.added_at as added_at", "ci.cheque_no as cheque_no",
                "ci.cheque_date as cheque_date",DB::raw("'' as created_by"),DB::raw("'' as remarks"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN w.name WHEN sp.invoiceno iS NULL AND 
                    c.type != 0 THEN c.name ELSE '' END as customer_name"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN 'Walk In Customer' WHEN sp.invoiceno iS NULL
                    AND c.type != 0 THEN 'Credit customer' ELSE '' END as customer_type"))
            ->where('sp.type', 'cheque')
            ->where('sp.date', '>=', $request['start'])
            ->where('sp.date', '<=', $request['end']);

        $second = DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->select("pp.id as id", "pp.paiddate as date","pp.invoiceno as invoice_no", DB::raw("0 as debit_amount"),
                "pp.amount as credit_amount", DB::raw("'CP' as formtype"),
                "pp.added_at as added_at", "ci.cheque_no as cheque_no", "ci.cheque_date as cheque_date",
                DB::raw("'' as created_by"),DB::raw("'' as remarks"), DB::raw("'' as customer_name"),
                DB::raw("'' as customer_type"))
            ->where('pp.type', 'cheque')
            ->where('pp.paiddate', '>=', $request['start'])
            ->where('pp.paiddate', '<=', $request['end']);

        $third = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
                ->where('glaf.date', '>=', $request['start'])
                ->where('glaf.date', '<=', $request['end'])
                ->where(function($result) use ($request) {
                    $result->where('glaf.debit_gl', $request['id'])
                        ->orwhere('glaf.credit_gl', $request['id']);
                });
        /*->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where('glaf.debit_gl', $request['id'])
            ->orwhere('glaf.credit_gl', $request['id']);*/
        $fourth = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id']);

        $result = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end'])
            ->union($first)
            ->union($second)
            ->union($third)
            ->union($fourth)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;
        return response($result);
    }

    public function purchases(Request $request)
    {
        $prevdebitpurchase = DB::table('purchaseinventory')
            ->where('dateofpurchase', '<', $request['start'])
            ->sum('total_amount');

        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');

        $prevdebitsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'credit')
            ->sum('supplieradjustment.amount');

        $openingbalance = ($prevdebitsadj + $prevdebitgl + $prevdebitpurchase) - $prevcreditsadj - $prevcreditgl;


        $first = DB::table('purchaseinventory as pin')
            ->select("pin.id as id", "pin.dateofpurchase as date", "pin.invoice_no as invoice_no",
                "pin.total_amount as debit_amount",DB::raw("0 as credit_amount"),DB::raw("'PO' as formtype"),
                "pin.added_at as added_at",DB::raw("'' as cheque_no"),DB::raw("'' as cheque_date"),"pin.received_by as created_by",
                "pin.remarks as remarks", DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('pin.dateofpurchase', '>=', $request['start'])
            ->where('pin.dateofpurchase', '<=', $request['end']);

        $second = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end']);

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            })
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;

        return response($result);

    }

    public function wastage(Request $request)
    {
        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');

        $prevdebitsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'credit')
            ->sum('supplieradjustment.amount');

        $openingbalance = ( $prevdebitgl +$prevdebitcadj +$prevdebitsadj)-
            ($prevcreditgl +$prevcreditcadj +$prevcreditsadj);

        $first = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id']);

        $second = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end']);

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            })
            /*->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where('glaf.debit_gl', $request['id'])
            ->orwhere('glaf.credit_gl', $request['id'])*/
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;

        return response($result);
    }

    public function pettycash(Request $request)
    {
        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevpettycash = DB::table('pettycashpayment')
            ->where('pettycashpayment.date', '<', $request['start'])
            ->sum('pettycashpayment.amount');

        $openingbalance = ($prevdebitgl + $prevpettycash) - $prevcreditgl;

        $first = DB::table('pettycashpayment as pcp')
            ->select("pcp.id as id", "pcp.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("pcp.amount as debit_amount")),
                (DB::raw("0 as credit_amount")), DB::raw("'PCF' as formtype"),"pcp.added_at as added_at",
                DB::raw("'' as cheque_no"), DB::raw("'' as cheque_date"),DB::raw("'' as created_by"),
                "pcp.remarks as remarks", DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('pcp.date', '>=', $request['start'])
            ->where('pcp.date', '<=', $request['end']);

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            })
            ->union($first)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;
        return response($result);
    }

    public function capital(Request $request)
    {
        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');


        $openingbalance = $prevdebitgl - $prevcreditgl;

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where('glaf.debit_gl', $request['id'])
            ->orwhere('glaf.credit_gl', $request['id'])
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;
        return response($result);
    }

    public function discount(Request $request)
    {
        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');


        $openingbalance = ( $prevdebitgl +$prevdebitcadj ) -
            ($prevcreditgl +$prevcreditsadj);

        $first = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                "caf.amount as debit_amount",  (DB::raw("0 as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.type', 'credit')
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id']);

        $second = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("0 as debit_amount")), "saf.amount as credit_amount",
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.type','debit')
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end']);

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            })
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;

        return response($result);
    }

    public function incometax(Request $request)
    {
        $prevdebitsalepayment =  DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->where('date', '<', $request['start'])
            ->where('type', 'cheque')
            ->sum('ci.tax_amount');

        $prevcreditpurchasepayment =  DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->where('paiddate', '<', $request['start'])
            ->where('type', 'cheque')
            ->sum('ci.tax_amount');

        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $prevcreditsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'debit')
            ->sum('supplieradjustment.amount');

        $prevdebitsadj = DB::table('supplieradjustment')
            ->where('supplieradjustment.date', '<', $request['start'])
            ->where('supplieradjustment.general_ledger_id', $request['id'])
            ->where('supplieradjustment.type', 'credit')
            ->sum('supplieradjustment.amount');

        $openingbalance = ($prevdebitsalepayment + $prevdebitgl +$prevdebitcadj +$prevdebitsadj)-
            ($prevcreditpurchasepayment + $prevcreditgl +$prevcreditcadj +$prevcreditsadj);

        $first = DB::table('salepayment as sp')
            ->leftJoin('chequeinfo as ci', 'sp.id', '=', 'ci.sale_payment_id')
            ->leftJoin('saleinventory as sin', 'sp.job_order_no', '=', 'sin.id')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("sp.id as id", "sp.date as date", (DB::raw(" sp.invoiceno as invoice_no")),
                "ci.tax_amount as debit_amount", DB::raw("0 as credit_amount"), DB::raw("'CC' as formtype"),
                "sp.added_at as added_at", "ci.cheque_no as cheque_no", "ci.cheque_date as cheque_date",
                DB::raw("'' as created_by"),DB::raw("'' as remarks"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN w.name WHEN sp.invoiceno iS NULL AND 
                    c.type != 0 THEN c.name ELSE '' END as customer_name"),
                DB::raw("CASE WHEN sp.invoiceno iS NULL AND c.type = 0 THEN 'Walk In Customer' WHEN sp.invoiceno iS NULL
                    AND c.type != 0 THEN 'Credit customer' ELSE '' END as customer_type"))
            ->where('sp.type', 'cheque')
            ->where('sp.date', '>=', $request['start'])
            ->where('sp.date', '<=', $request['end']);

        $second = DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->select("pp.id as id", "pp.paiddate as date","pp.invoiceno as invoice_no", DB::raw("0 as debit_amount"),
                "ci.tax_amount as credit_amount", DB::raw("'CP' as formtype"),
                "pp.added_at as added_at", "ci.cheque_no as cheque_no", "ci.cheque_date as cheque_date",
                DB::raw("'' as created_by"),DB::raw("'' as remarks"), DB::raw("'' as customer_name"),
                DB::raw("'' as customer_type"))
            ->where('pp.type', 'cheque')
            ->where('pp.paiddate', '>=', $request['start'])
            ->where('pp.paiddate', '<=', $request['end']);

        $third = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            });

        $fourth = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "saf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('saf.general_ledger_id',$request['id'])
            ->where('saf.date', '>=', $request['start'])
            ->where('saf.date', '<=', $request['end']);

        $result = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id'])
            ->union($first)
            ->union($second)
            ->union($third)
            ->union($fourth)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;
        return response($result);
    }

    public function baddebt(Request $request)
    {
        $prevdebitgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.debit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditgl = DB::table('gladjustment')
            ->where('gladjustment.date', '<', $request['start'])
            ->where('gladjustment.credit_gl', $request['id'])
            ->sum('gladjustment.amount');

        $prevcreditcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'debit')
            ->sum('customeradjustment.amount');

        $prevdebitcadj = DB::table('customeradjustment')
            ->where('customeradjustment.date', '<', $request['start'])
            ->where('customeradjustment.general_ledger_id', $request['id'])
            ->where('customeradjustment.type', 'credit')
            ->sum('customeradjustment.amount');

        $openingbalance = ( $prevdebitgl +$prevdebitcadj)-
            ($prevcreditgl +$prevcreditcadj);

        $first = DB::table('customeradjustment as caf')
            ->leftJoin('saleinventory as sin', 'sin.id', '=', 'caf.invoice_no')
            ->leftJoin('customers as c', 'sin.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'sin.id', '=', 'w.saleinventory_id')
            ->select("caf.id as id", "caf.date as date", "sin.invoiceno as invoice_no",
                (DB::raw("CASE WHEN caf.type = 'credit' THEN caf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN caf.type = 'debit' THEN caf.amount ELSE 0 END as credit_amount")),
                DB::raw("'CAF' as formtype"),"caf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "caf.remarks as remarks",
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as customer_name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as customer_type"))
            ->where('caf.date', '>=', $request['start'])
            ->where('caf.date', '<=', $request['end'])
            ->where('caf.general_ledger_id',$request['id']);

        $result = DB::table('gladjustment as glaf')
            ->select("glaf.id as id", "glaf.date as date", DB::raw("'' as invoice_no"),
                (DB::raw("CASE WHEN glaf.debit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN glaf.credit_gl = ".$request['id']." THEN glaf.amount ELSE 0 END as credit_amount")),
                DB::raw("'GLAF' as formtype"),"glaf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as created_by"), "glaf.remarks as remarks",
                DB::raw("'' as customer_name"), DB::raw("'' as customer_type"))
            ->where('glaf.date', '>=', $request['start'])
            ->where('glaf.date', '<=', $request['end'])
            ->where(function($result) use ($request) {
                $result->where('glaf.debit_gl', $request['id'])
                    ->orwhere('glaf.credit_gl', $request['id']);
            })
            ->union($first)
            ->ORDERBY('date')
            ->get();

        $result['openingbalance'] = $openingbalance;

        return response($result);
    }
}