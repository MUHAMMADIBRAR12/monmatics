<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\dbLib;
use App\Libraries\inventoryLib;
class OpeningBalanceController extends Controller
{
    public function form()
    {
      /*  $opening_balance=DB::table('inv_products')
            ->leftjoin('inv_inventories',function($join){
                $join->on('inv_inventories.prod_id','inv_products.id');
                $join->on('inv_inventories.source','>=',DB::raw("'Opening Balance'"));
            })
            ->select('inv_products.id','inv_products.name','inv_inventories.balance','inv_inventories.rate','inv_inventories.warehouse_id')
            ->get(); */
        $warehouses=dbLib::getWarehouses();
        return view('inventory.opening_balance',compact('warehouses'));
    }

    public function save(Request $request)
    {
        //first delete all rows of opening balance then add new 
        DB::table('inv_inventories')->where('source','Opening Balance')->where('warehouse_id',$request->warehouse)->delete();
       // dd($request->input());
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $opening_balance=array(
                "date"=>$request->date,
                "warehouse_id"=>$request->warehouse,
                "source"=>'Opening Balance',
                "prod_id"=>$request->item_ID[$i],
                "qty_in"=>$request->qty[$i],
                "balance"=>$request->qty[$i],
                "rate"=>$request->rate[$i],
                "amount"=>$request->qty[$i] * $request->rate[$i],
                "description"=>'Opening Balance',
            );

            inventoryLib::addInventory($opening_balance);
        }
        return back();
        
    }

    public function openingBalance(Request $request)
    {
        $companyId = session('companyId');
        $warehouse_id=$request->warehouse_id;
        $opening_balance=DB::table('inv_products')
            ->leftjoin('inv_inventories',function($join) use ($warehouse_id,$companyId) {
                $join->on('inv_inventories.prod_id','inv_products.id');
                $join->on('inv_inventories.source','>=',DB::raw("'Opening Balance'"));
                $join->on('inv_inventories.warehouse_id','=',DB::raw("'".$warehouse_id."'"));
                $join->on('inv_inventories.company_id','=',DB::raw("'".$companyId."'"));
            })
            ->select('inv_products.id','inv_products.name', 'inv_products.code','inv_inventories.balance','inv_inventories.rate','inv_inventories.warehouse_id','inv_inventories.date')
            ->orderBy('inv_products.name')
            ->get();
        return $opening_balance;
    }
}
