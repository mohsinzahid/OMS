<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PurchasesReportsController extends Controller
{

    /*public function date(Request $request)
    {

        $result = DB::table('purchaseinventory')->where('vendor_id', $request['id'])
                                                ->wheredate('dateofpurchase', '>=' , date($request['start']))
                                                ->wheredate('dateofpurchase', '<=' , date($request['end']))->get();

        $result['payment'] = DB::table('purchasepayment')->where('vendor_id', $request['id'])
                                                            ->wheredate('paiddate', '>=' , date($request['start']))
                                                            ->wheredate('paiddate', '<=' , date($request['end']))->get();
        return response()->json($result, 200);
    }*/

    public function SupplierLedgerAjaxUpdate(Request $request)
    {
        $result = DB::table('purchaseinventory')->where('vendor_id', $request['id'])->get();
        $result['payment'] = DB::table('purchasepayment')->where('vendor_id', $request['id'])->get();

        $Start_date = $request['start'];
        $End_date = $request['end'];

        $prevdebitsale = DB::table('purchaseinventory')->where('vendor_id', $request['id'])
            ->where('purchaseinventory.dateofpurchase', '<', $Start_date)
            ->sum('purchaseinventory.total_amount');

        $prevcreditadj = DB::table('supplieradjustment')->where('supplier_id', $request['id'])
            ->where('date', '<', $Start_date)
            ->where('type', 'credit')
            ->sum('amount');

        $prevdebitadj = DB::table('supplieradjustment')->where('supplier_id', $request['id'])
            ->where('date', '<', $Start_date)
            ->where('type', 'debit')
            ->sum('amount');


        //mass paid
        $prevcreditsale =  DB::table('purchasepayment as pp')->where('vendor_id', $request['id'])
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->where('paiddate', '<', $Start_date)
            ->sum(DB::raw('CASE WHEN ci.tax_amount iS NULL THEN pp.amount ELSE pp.amount + ci.tax_amount END'));

        $first = DB::table('purchasepayment as pp')
            ->leftJoin('chequeinfo as ci', 'pp.id', '=', 'ci.purchase_payment_id')
            ->select("pp.id as id", "pp.paiddate as date","pp.invoiceno as invoice_no",
                DB::raw("0 as debit_amount"),
                (DB::raw("CASE WHEN ci.tax_amount iS NULL THEN pp.amount ELSE pp.amount + ci.tax_amount END as credit_amount")),
                DB::raw("'CP' as formtype"),"pp.added_at as added_at", "ci.cheque_no as cheque_no",
                "ci.cheque_date as cheque_date",DB::raw("'' as received_by"),DB::raw("'' as remarks"))
            ->where('pp.vendor_id', $request['id'])
            ->where('pp.paiddate', '>=', $Start_date)
            ->where('pp.paiddate', '<=', $End_date);


        $second = DB::table('purchaseinventory as pin')
            ->select("pin.id as id", "pin.dateofpurchase as date", "pin.invoice_no as invoice_no",
                "pin.total_amount as debit_amount",DB::raw("0 as credit_amount"),DB::raw("'PO' as formtype"),
                "pin.added_at as added_at",DB::raw("'' as cheque_no"),DB::raw("'' as cheque_date"),"pin.received_by as received_by",
                "pin.remarks as remarks")
            ->where('pin.vendor_id', $request['id'])
            ->where('pin.dateofpurchase', '>=', $Start_date)
            ->where('pin.dateofpurchase', '<=', $End_date);


        $result = DB::table('supplieradjustment as saf')
            ->leftJoin('purchaseinventory as pin', 'pin.id', '=', 'saf.purchase_order_no')
            ->select("saf.id as id", "saf.date as date", "pin.invoice_no as invoice_no",
                (DB::raw("CASE WHEN saf.type = 'debit' THEN saf.amount ELSE 0 END as debit_amount")),
                (DB::raw("CASE WHEN saf.type = 'credit' THEN saf.amount ELSE 0 END as credit_amount")),
                DB::raw("'SAF' as formtype"),"saf.added_at as added_at", DB::raw("'' as cheque_no"),
                DB::raw("'' as cheque_date"),DB::raw("'' as received_by"), "saf.remarks as remarks")
            ->where('saf.supplier_id', $request['id'])
            ->where('saf.date', '>=', $Start_date)
            ->where('saf.date', '<=', $End_date)
            ->union($first)
            ->union($second)
            ->ORDERBY('date')
            ->get();

        $openingbalance = DB::table('vendor')->where('id', $request['id'])
            ->select('prevbalance')->first();
        $result['openingbalance'] = ($openingbalance ->prevbalance + $prevdebitsale +$prevdebitadj) - ($prevcreditsale + $prevcreditadj);
        return response()->json($result, 200);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SupplierLedger()
    {
        $vendors = DB::table('vendor')->select('name', 'id')->get();
        return view('purchases.reports.supplierLedger',['vendor' => $vendors]);
    }

    public function SupplierLedgerDetail()
    {
        $vendors = DB::table('vendor')->select('name', 'id')->get();
        return view('purchases.reports.supplierLedgerDetail',['vendor' => $vendors]);
    }

    public function SupplierLedgerDetailAjaxUpdate(Request $request)
    {
        $result = DB::table('purchaseinventory')
            ->join('purchaseinventoryitem', 'purchaseinventory.id', '=', 'purchaseinventoryitem.purchaseinventory_id')
            ->join('size', 'purchaseinventoryitem.size_id', '=', 'size.id')
            ->select('purchaseinventory.*','purchaseinventoryitem.*', 'size.size')
            ->where('purchaseinventory.vendor_id',$request['id'])
            ->where('purchaseinventory.dateofpurchase','>=', $request['start'])
            ->where('purchaseinventory.dateofpurchase','<=', $request['end'])
            ->ORDERBY('purchaseinventory.dateofpurchase')
            ->get();

        return response()->json($result, 200);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
