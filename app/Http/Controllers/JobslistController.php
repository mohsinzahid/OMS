<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobslistController extends Controller
{
    public function pendingjobs(Request $request)
    {
        $result = DB::table('saleinventory')
            ->Join('employees as e', 'saleinventory.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'saleinventory.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'saleinventory.id', '=', 'w.saleinventory_id')
            ->Join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
            ->join('size', 'saleinventoryitem.size_id', '=', 'size.id')
            ->select("saleinventory.id as id", "saleinventory.dateofsale as date", "saleinventory.invoiceno as invoice_no",
                "saleinventory.added_at as added_at","e.name as created_by",
                DB::raw("CASE WHEN saleinventory.isreplace = 1 THEN 'replace' ELSE 'original' END as status"),
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as type"),
                "saleinventoryitem.*", "size.size")
            ->where('saleinventory.status',0)
            ->ORDERBY('added_at')
            ->get();

        return response()->json($result, 200);

    }

    public function printedjobs(Request $request)
    {
        $result = DB::table('saleinventory')
            ->Join('employees as e', 'saleinventory.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'saleinventory.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'saleinventory.id', '=', 'w.saleinventory_id')
            ->Join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
            ->join('size', 'saleinventoryitem.size_id', '=', 'size.id')
            ->select("saleinventory.id as id", "saleinventory.dateofsale as date", "saleinventory.invoiceno as invoice_no",
                "saleinventory.added_at as added_at","e.name as created_by",
                DB::raw("CASE WHEN saleinventory.isreplace = 1 THEN 'replace' ELSE 'original' END as status"),
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as type"),
                "saleinventoryitem.*", "size.size")
            ->where('saleinventory.status',1)
            ->where('saleinventory.dateofsale', '>=',  $request['start'])
            ->where('saleinventory.dateofsale', '<=',  $request['end'])
            ->ORDERBY('added_at')
            ->get();

        return response()->json($result, 200);

    }

    public function submittedjobs(Request $request)
    {
        $result = DB::table('saleinventory')
            ->Join('employees as e', 'saleinventory.employee_id', '=', 'e.id')
            ->leftJoin('customers as c', 'saleinventory.customer_id', '=', 'c.id')
            ->leftJoin('walkincustomer as w', 'saleinventory.id', '=', 'w.saleinventory_id')
            ->Join('saleinventoryitem', 'saleinventory.id', '=', 'saleinventoryitem.saleinventory_id')
            ->join('size', 'saleinventoryitem.size_id', '=', 'size.id')
            ->select("saleinventory.id as id", "saleinventory.dateofsale as date", "saleinventory.invoiceno as invoice_no",
                "saleinventory.added_at as added_at","e.name as created_by",
                ("saleinventory.status as status"),
                DB::raw("CASE WHEN c.type = 0 THEN w.name ELSE c.name END as name"),
                DB::raw("CASE WHEN c.type = 0 THEN 'Walk In Customer' ELSE 'Credit customer' END as type"),
                "saleinventoryitem.*", "size.size")
            ->where('saleinventory.dateofsale', '>=',  $request['start'])
            ->where('saleinventory.dateofsale', '<=',  $request['end'])
            ->ORDERBY('added_at')
            ->get();

        return response()->json($result, 200);

    }

    public function updatejobstatus(Request $request)
    {
        DB::table('saleinventory')->where('id',$request['id'])->update(['status' => 1]);
        return response()->json(200);
    }
}
