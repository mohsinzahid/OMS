<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;


class InventoryReportsController extends Controller
{
    public function InventoryReport()
    {
        $size = DB::table('size')->get();

        return view('inventory.reports.inventory',['size'=>$size]);
    }

    public function InventoryReportAjaxUpdate(Request $request)
    {

        $start = $request['start'];
        $end = $request['end'];
        $openingStock = DB::table('stock')
            ->select('quantity')
            ->where('size_id',$request['id'])
            ->first();

        $prePurchaseQuantity = DB::table('purchaseinventory')
            ->join('purchaseinventoryitem', 'purchaseinventory.id', '=', 'purchaseinventoryitem.purchaseinventory_id')
            ->where('purchaseinventoryitem.size_id',$request['id'])
            ->where('purchaseinventory.dateofpurchase','<', $request['start'])
            ->sum('quantity');

        $preSaleQuantity = DB::table('saleinventory')
            ->join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
            ->where('saleinventory.status',1)
            ->where('saleinventoryitem.size_id',$request['id'])
            ->where('saleinventory.dateofsale','<', $request['start'])
            ->sum(DB::raw('saleinventoryitem.color * saleinventoryitem.set'));

        $preWasteQuantity = DB::table('waste')
            ->where('waste.size_id',$request['id'])
            ->where('waste.approved_date','<', $request['start'])
            ->sum('quantity');

        $preSupplierAdjDebitQuantity = DB::table('supplieradjustment')
            ->where('type','debit')
            ->where('supplieradjustment.size_id',$request['id'])
            ->where('supplieradjustment.date','<', $request['start'])
            ->sum('quantity');
        $preSupplierAdjCreditQuantity = DB::table('supplieradjustment')
            ->where('type','credit')
            ->where('supplieradjustment.size_id',$request['id'])
            ->where('supplieradjustment.date','<', $request['start'])
            ->sum('quantity');

        $openingbalance = ($openingStock->quantity + $prePurchaseQuantity + $preSupplierAdjCreditQuantity) -
                            $preSaleQuantity - $preWasteQuantity - $preSupplierAdjDebitQuantity;

        $PurchaseQuantity = DB::table('purchaseinventory as po')
            ->join('purchaseinventoryitem as poi', 'po.id', '=', 'poi.purchaseinventory_id')
            ->select(DB::raw("po.id as id, po.dateofpurchase as date, 'PO' as formtype, po.invoice_no as invoice_no,
                              SUM(poi.quantity) as debit_quantity, 0 as credit_quantity, po.remarks as remarks, 
                              po.added_at as added_at, po.received_by as rca_by"))
            ->where('po.dateofpurchase','>=', $start)
            ->where('po.dateofpurchase','<=', $end)
            ->where('poi.size_id',$request['id'])
            ->groupby('size_id','id','date','formtype', 'invoice_no','credit_quantity','remarks','added_at', 'rca_by');

        $SaleQuantity = DB::table('saleinventory as so')
            ->join('saleinventoryitem as soi', 'so.id', '=', 'soi.saleinventory_id')
            ->join('employees as e', 'so.employee_id', '=', 'e.id')
            ->select(DB::raw("so.id as id, so.dateofsale as date, 'JO' as formtype, so.invoiceno as invoice_no,
                              0 as debit_quantity, SUM(soi.color * soi.set) as credit_quantity, '' as remarks, 
                              so.added_at as added_at, e.name as rca_by"))
            ->where('so.status',1)
            ->where('so.dateofsale','>=', $start)
            ->where('so.dateofsale','<=', $end)
            ->where('soi.size_id',$request['id'])
            ->groupby('size_id','id','date','formtype', 'invoice_no','debit_quantity','remarks','added_at',
                    'rca_by');


        $WasteQuantity = DB::table('waste')
            ->select(DB::raw("id as id, approved_date as date, 'WG' as formtype, '' as invoice_no,
                              0 as debit_quantity, quantity as credit_quantity, remarks as remarks, 
                              datentime as added_at, approved_by as rca_by"))
            ->where('size_id',$request['id'])
            ->where('approved_date','>=', $request['start'])
            ->where('approved_date','<=', $request['end'])
            ->groupby('size_id','id','date','formtype', 'invoice_no','debit_quantity','credit_quantity',
                    'remarks','added_at', 'rca_by');

        $SupplierAdjQuantity = DB::table('supplieradjustment as saf')
            ->join('purchaseinventory as po', 'saf.purchase_order_no', '=', 'po.id')
            ->select(DB::raw("saf.id as id, saf.date as date, 'SAF' as formtype, po.invoice_no as invoice_no,
                              CASE WHEN saf.type = 'credit' THEN saf.quantity ELSE 0 END as debit_quantity,
                              CASE WHEN saf.type = 'debit' THEN saf.quantity ELSE 0 END as credit_quantity,
                               saf.remarks as remarks, saf.added_at as added_at, '' as rca_by"))
            ->groupby('size_id','id','date','formtype', 'invoice_no','debit_quantity','credit_quantity',
                'remarks','added_at', 'rca_by')
            ->where('saf.size_id',$request['id'])
            ->where('saf.date','>=', $request['start'])
            ->where('saf.date','<=', $request['end'])
            ->union($PurchaseQuantity)
            ->union($SaleQuantity)
            ->union($WasteQuantity)
            ->ORDERBY('date')
            ->get();

        $result = $SupplierAdjQuantity;
        $result['openingbalance'] = $openingbalance;

        return response()->json($result, 200);
    }


    public function InventorySummaryReportAjaxUpdate(Request $request)
    {
        $size = DB::table('size')->get();


        foreach ($size as $sizes)
        {
            $openingStock = DB::table('stock')
                ->where('size_id',$sizes->id)
                ->first();

            $prePurchaseQuantity = DB::table('purchaseinventory')
                ->join('purchaseinventoryitem', 'purchaseinventory.id', '=', 'purchaseinventoryitem.purchaseinventory_id')
                ->where('purchaseinventoryitem.size_id',$sizes->id)
                ->where('purchaseinventory.dateofpurchase','<', $request['start'])
                ->sum('quantity');

            $preSaleQuantity = DB::table('saleinventory')
                ->join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
                ->where('saleinventory.status',1)
                ->where('saleinventoryitem.size_id',$sizes->id)
                ->where('saleinventory.dateofsale','<', $request['start'])
                ->sum(DB::raw('saleinventoryitem.color * saleinventoryitem.set'));

            $preWasteQuantity = DB::table('waste')
                ->where('waste.size_id',$sizes->id)
                ->where('waste.approved_date','<', $request['start'])
                ->sum('quantity');

            $preSupplierAdjDebitQuantity = DB::table('supplieradjustment')
                ->where('type','debit')
                ->where('supplieradjustment.size_id',$sizes->id)
                ->where('supplieradjustment.date','<', $request['start'])
                ->sum('quantity');
            $preSupplierAdjCreditQuantity = DB::table('supplieradjustment')
                ->where('type','credit')
                ->where('supplieradjustment.size_id',$sizes->id)
                ->where('supplieradjustment.date','<', $request['start'])
                ->sum('quantity');

            $openingbalance = ($openingStock->quantity + $prePurchaseQuantity + $preSupplierAdjCreditQuantity) -
                $preSaleQuantity - $preWasteQuantity - $preSupplierAdjDebitQuantity;

            $PurchaseQuantity = DB::table('purchaseinventory')
                ->join('purchaseinventoryitem', 'purchaseinventory.id', '=', 'purchaseinventoryitem.purchaseinventory_id')
                ->where('purchaseinventoryitem.size_id',$sizes->id)
                ->where('purchaseinventory.dateofpurchase','>=', $request['start'])
                ->where('purchaseinventory.dateofpurchase','<=', $request['end'])

                ->sum('quantity');

            $SaleQuantity = DB::table('saleinventory')
                ->join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
                ->where('saleinventory.status',1)
                ->where('saleinventoryitem.size_id',$sizes->id)
                ->where('saleinventory.dateofsale','>=', $request['start'])
                ->where('saleinventory.dateofsale','<=', $request['end'])
                ->sum(DB::raw('saleinventoryitem.color * saleinventoryitem.set'));

            $WasteQuantity = DB::table('waste')
                ->where('waste.size_id',$sizes->id)
                ->where('waste.approved_date','>=', $request['start'])
                ->where('waste.approved_date','<=', $request['end'])
                ->sum('quantity');

            $SupplierAdjDebitQuantity = DB::table('supplieradjustment')
                ->where('type','debit')
                ->where('supplieradjustment.size_id',$sizes->id)
                ->where('supplieradjustment.date','>=', $request['start'])
                ->where('supplieradjustment.date','<=', $request['end'])
                ->sum('quantity');

            $SupplierAdjCreditQuantity = DB::table('supplieradjustment')
                ->where('type','credit')
                ->where('supplieradjustment.size_id',$sizes->id)
                ->where('supplieradjustment.date','>=', $request['start'])
                ->where('supplieradjustment.date','<=', $request['end'])
                ->sum('quantity');

            $netbalance = ($openingbalance +$PurchaseQuantity + $SupplierAdjCreditQuantity) -
                $SaleQuantity - $WasteQuantity - $SupplierAdjDebitQuantity;

            $result[$sizes->id] = array('size'=> $sizes->size,
                                                'opening' => $openingbalance,
                                                'net' => $netbalance,
                                                'saleprice' =>$openingStock->saleprice);

        }
        return response()->json($result, 200);
    }
}
