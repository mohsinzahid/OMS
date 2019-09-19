<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wastes = DB::table('waste')
            ->join('size', 'waste.size_id', '=', 'size.id')
            ->select('waste.quantity','waste.description','waste.id','waste.datentime','size.size')
            ->get();
        return view('stock.waste.show', ['waste' => $wastes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $size = DB::table('size')->orderBy('size', 'asc')->get();
        return view('inventory.forms.wastage.add', ['size' => $size] ,['msg'=>'']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            DB::table('waste')
                ->insert(['size_id' => $request['id'],'quantity'=>$request['quantity'],
                    'approved_by' => $request['approvedby'], 'approved_date' => $request['approveddate'],
                    'remarks' =>$request['remarks'], 'datentime'=> NOW()]);

            $size = DB::table('size')->orderBy('size', 'asc')->get();
        return view('inventory.forms.wastage.add', ['size' => $size] ,['msg'=>'Waste added Successfully']);
    }

    public function edit($id)
    {
        $result = DB::table('waste')->where('id', $id)->first();
        $size = DB::table('size')->get();
        return view('inventory.forms.wastage.edit',['waste' =>$result , 'size'=>$size]);
    }

    public function update(Request $request)
    {
        DB::table('waste')
            ->where('id',$request['id'])
            ->update(['size_id' => $request['size_id'],'quantity'=>$request['quantity'],
                    'remarks' =>$request['remarks'], 'approved_by' => $request['approvedby'],
                    'approved_date' => $request['approveddate']]
            );

        return redirect('/form/edit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*$waste = DB::table('waste')->where('id', $id)->first();
        $stock = DB::table('stock')->where('size_id', $waste->size_id)->first();
        DB::table('stock')
            ->where('size_id',$stock->size_id)
            ->update(['quantity'=>($stock->quantity + $waste->quantity)]
            );*/

        DB::table('waste')->where('id',$id)->delete();

        return redirect('/form/edit');
    }
}
