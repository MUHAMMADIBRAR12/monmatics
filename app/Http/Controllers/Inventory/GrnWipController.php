<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Carbon\Carbon;
use App\Libraries\inventoryLib;
use App\Libraries\accountsLib;
use App\Libraries\wip_inventoryLib;
class GrnWipController extends Controller
{
    public function index()
    {
        $grn_wip_list=DB::table('inv_grns')
                        ->join('inv_grn_wip_outsource as grn_wip','grn_wip.grn_id','=','inv_grns.id')
                        ->join('inv_inventories as inv','inv.source_id','=','inv_grns.id')
                        ->join('inv_products','inv_products.id','=','inv.prod_id')
                        ->join('sys_warehouses','sys_warehouses.id','=','inv_grns.warehouse')
                        ->select('inv_grns.id','inv_grns.date','inv_grns.editable','inv_grns.number','inv_grns.month','inv_grns.note','grn_wip.batch','sys_warehouses.name as warehouse_name','inv_products.name as product','inv.qty_in as qty','inv.unit')
                        ->distinct('grn_wip.grn_id')
                        ->where('inv_grns.company_id',session('companyId'))
                        ->orderBy('inv_grns.month', 'ASC')
                        ->orderBy('inv_grns.number', 'ASC')
                        ->get();
      
        return view('inventory.grn_wip_list',compact('grn_wip_list'));
    }

    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        $batch_numbers=DB::table('inv_gins')->select('batch')->distinct()->get();
        $projects=dbLib::getProjects();
        $accounts=dbLib::wipAccounts();
        if($id)
        {
          // dd($id);
            $grn=DB::table('inv_grns')
                    ->join('inv_grn_wip_outsource as grn_wip','grn_wip.grn_id','=','inv_grns.id')
                    ->join('inv_inventories','inv_inventories.source_id','=','inv_grns.id')
                    ->join('inv_products','inv_products.id','=','inv_inventories.prod_id')
                    ->select('inv_grns.*','grn_wip.batch','grn_wip.project','inv_inventories.prod_id','inv_products.name as prod_name','inv_inventories.unit','inv_inventories.qty_in','inv_inventories.rate','inv_inventories.amount')
                    ->where('inv_grns.id',$id)
                    ->distinct('grn_wip.grn_id')
                    ->get()
                    ->first();
            
            //products issue detail
            $pi_detail=DB::table('inv_grns')
                            ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                                    ->where('grn_wip.name', '=', 'Own Product');
                            })
                            ->join('inv_products','inv_products.id','=','grn_wip.prod_id')
                        ->select('grn_wip.*','inv_products.name as prod_name')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();
            $pi_detail_arr=array();
            foreach($pi_detail as $pi)
            {
                $data=array(
                    'prod_name'=>$pi->prod_name,
                    'prod_id'=>$pi->prod_id,
                    'description'=>$pi->description,
                    'unit'=>$pi->unit,
                    'pre_qty'=>$pi->pre_qty,
                    'qty'=>$pi->qty,
                    'rate'=>$pi->rate,
                    'amount'=>$pi->amount,
                    'balance'=>wip_inventoryLib::get_wip_inventory_balance($pi->prod_id),
                );
                array_push($pi_detail_arr,$data);
            }
            //products  used by vendor detail
            $pv_detail=DB::table('inv_grns')
                        ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                            $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                            ->where('grn_wip.name', '=', 'Ven Product');
                        })
                        ->join('inv_products','inv_products.id','=','grn_wip.prod_id')
                        ->select('grn_wip.*','inv_products.name as prod_name')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();

            //services  used by vendor detail
            $sv_detail=DB::table('inv_grns')
                            ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                                    ->where('grn_wip.category', '=', 'Service');
                            })
                        ->select('grn_wip.*')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();
          
            //dd($pi_detail_arr);
            return view('inventory.grn_wip',compact('warehouses','accounts','batch_numbers','projects','grn','pi_detail_arr','pv_detail','sv_detail'));
        }
        else
        {
            return view('inventory.grn_wip',compact('warehouses','accounts','batch_numbers','projects'));
        }
    }
    /*
    // batch detail from wip material issue
    public function batch_record(Request $request)
    {
        $pi_detail=DB::table('inv_gins')
                       ->join('inv_gin_details as gd','gd.gin_id','=','inv_gins.id')
                       ->join('inv_products','inv_products.id','=','gd.prod_id')
                       ->join('sys_units','sys_units.id','=','inv_products.primary_unit')
                       ->select('inv_products.name as prod_name','gd.prod_id','gd.description','sys_units.name as base_unit',DB::raw('sum((gd.qty_issue*gd.operator_val)) as qty '),'gd.base_rate')
                       ->where('inv_gins.batch',$request->batch)
                       ->groupBy('gd.prod_id')
                       ->orderBy('gd.display')
                       ->get();
        return $pi_detail;

        //->join('inv_inventories as inven','inven.source_detail_id','=','gd.id')
                       //->select('inv_products.name as prod_name','gd.prod_id','gd.description','sys_units.name as base_unit','inven.qty_out as qty','inven.rate','inven.amount')
    } */

    // Reciepe Detail
    public function reciepe_record(Request $request)
    {
        //return $request->reciepe;
        $reciepe_detail_arr=array();
        $rows=DB::table('wip_reciepes as wr')
                            ->join('inv_products','inv_products.id','wr.prod_id')
                            ->select('wr.prod_id','wr.description','wr.unit as base_unit','wr.qty','wr.type','inv_products.name as prod_name')
                            ->where('wr.name',$request->reciepe)
                            ->get();
        //return $rows;
        foreach($rows as $row)
        {
            $reciepe=array(
                'type'=>$row->type,
                'prod_name'=>$row->prod_name,
                'prod_id'=>$row->prod_id,
                'description'=>$row->description,
                'base_unit'=>$row->base_unit,
                'balance'=>wip_inventoryLib::get_wip_inventory_balance($row->prod_id),
                'qty'=>$row->qty,
                'rate'=>wip_inventoryLib::get_wip_inventory_rate($row->prod_id),
            );
            array_push($reciepe_detail_arr,$reciepe);
        }
        return $reciepe_detail_arr;
    }

    public function reciepeSearch(Request $request)
    {
        
        $searchStr = $request->name;
        $result = DB::table('wip_reciepes')
                    ->join('inv_products','inv_products.name','=','wip_reciepes.name')
                    ->select(array('inv_products.id', 'inv_products.name'))
                    ->where('wip_reciepes.name', 'like', '%'.$searchStr.'%')
                    ->groupBy('wip_reciepes.name')
                    ->orderby('name')
                    ->get();
        $response = array();
        foreach($result as $record){
           $response[] = array("value"=>$record->id,"label"=>$record->name);
        } 
        return response()->json($response);        
    }

    public function save(Request $request)
    {
        //dd($request->input());
        //*********************************************** transaction ************************************************
        $transMains=array(
            "id"=>$request->trans_id,
            "date"=>$request->date,
            "voucher_type"=>10,
            "note"=>$request->note,
        );
        $trm_id=accountsLib::saveTransMain($transMains);
        //*************************************************** trnsaction ******************************************
        if($request->id)
        {
           // dd($request->input());
            $grn_Id=$request->id;
            $this->update($request);
            DB::table('inv_grn_wip_outsource')->where('grn_id',$grn_Id)->delete();
            DB::table('inv_inventories')->where('source_id',$grn_Id)->delete();
            DB::table('wip_inventories')->where('grn_id',$grn_Id)->delete();
        }
        else
        {
            $grn_Id= $this->store($request,$trm_id);
        }

        //**************************************************** transaction detail ******************************************
        $description='Transaction against grn No: '.dbLib::getSpecialDocument('inv_grns',$grn_Id);
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>DB::table('inv_products')->select('coa_id')->where('id',$request->item_ID)->first()->coa_id,
            "description"=>$description,
            "debit"=>$request->production_cost,
            "credit"=>0,
        );
        accountsLib::saveTransDetail($transDetails);
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->wip_coa_id,
            "description"=>$description,
            "debit"=>0,
            "credit"=>$request->production_cost,
        );
        accountsLib::saveTransDetail($transDetails);
        //**************************************************** transaction complete  *******************************************

        //save products issue 
        $this->savePiDetail($grn_Id, $request);

        //save products used by vendor 
        $this->savePvDetail($grn_Id, $request);
        
        //save services used by vendor 
        $this->saveSvDetail($grn_Id, $request);

        //inventory data
        $add_inventory = array (
            "date"=>$request->date,
            "warehouse_id"=>$request->warehouse,
            "source"=>'grn_wip',
            "source_id"=>$grn_Id,
            "batch_no"=>$request->batch,
            "status"=>$request->status,
            "prod_id"=>$request->item_ID,
            "qty_in"=>$request->qty_received,
            "balance"=>$request->qty_received,
            "unit"=>$request->unit,
            "rate"=>$request->unit_production_cost,
            "amount"=>$request->production_cost,
        ); 
        inventoryLib::addInventory($add_inventory);

        return redirect('Inventory/GRN_WIP/List');
    }

    private function store($request,$trm_id)
    {
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_grns');
        $id = str::uuid()->toString();
        $inv_grn = array (
            "id"=>$id ,
            "trm_id"=>$trm_id,
            "wip_coa_id"=>$request->wip_coa_id,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "company_id"=>$companyId,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "user_id"=>$userId,
            "created_at"=>Carbon::now(), 
            
        ); 
        DB::table('inv_grns')->insert($inv_grn);
        return $id;
    }
    private function update($request)
    {
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $inv_grn = array (
            "date"=>$request->date,
            "month"=>$month,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "update_user_id"=>$userId,
            "updated_at"=>Carbon::now(),
            
        ); 
        DB::table('inv_grns')->where('id',$request->id)->update($inv_grn);
    }

    private function savePiDetail($grn_Id,$request)
    {
        if(is_array($request->item_pi_ID))
            $count = count($request->item_pi_ID);
        else
            $count=1;
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $pi_detail=array(
                'id'=>$id,
                'grn_id'=>$grn_Id,
                'batch'=>$request->batch,
                'project'=>$request->project,
                'category'=>'Product',
                'name'=>'Own Product',
                'prod_id'=>$request->item_pi_ID[$i],
                'description'=>$request->description_pi[$i],
                'unit'=>$request->unit_pi[$i],
                'pre_qty'=>$request->pre_qty_pi[$i],
                'qty'=>$request->qty_pi[$i],
                'rate'=>$request->rate_pi[$i],
                'amount'=>$request->amount_pi[$i],
                'display'=>$i+1,
            );
            DB::table('inv_grn_wip_outsource')->insert($pi_detail);

            //This wip_invenotry array add wip_inventories table to maintain stock 
            $base_qty=dbLib::convertUnit($request->qty_pi[$i],$request->unit_pi[$i]);
            //dd($base_qty);
            //dd($request->rate_pi[$i]);
            $base_rate=$request->rate_pi[$i] / $base_qty;
            $wip_inventory=array(
                'date'=>$request->date,
                'grn_id'=>$grn_Id,
                //'dept_id'=>$request->department,
                'prod_id'=>$request->item_pi_ID[$i],
                "qty_in"=>0,
                "qty_out"=>$base_qty,
                "rate"=>$base_rate,
                "amount"=>$base_qty * $base_rate,

            );
            wip_inventoryLib::add_wip_inventory($wip_inventory);
        }
        
    }

    private function savePvDetail($grn_Id,$request)
    {
        if($request->item_pv_ID)
        {
            $count = count($request->item_pv_ID);
            for($i=0;$i<$count;$i++)
            {
                $id = str::uuid()->toString();
                $pv_detail=array(
                    'id'=>$id,
                    'grn_id'=>$grn_Id,
                    'batch'=>$request->batch,
                    'project'=>$request->project,
                    'category'=>'Product',
                    'name'=>'Ven Product',
                    'prod_id'=>$request->item_pv_ID[$i],
                    'description'=>$request->description_pv[$i],
                    'unit'=>$request->unit_pv[$i],
                    'pre_qty'=>$request->pre_qty_pv[$i],
                    'qty'=>$request->qty_pv[$i],
                    'rate'=>$request->rate_pv[$i],
                    'amount'=>$request->amount_pv[$i],
                    'display'=>$i+1,
                );
                DB::table('inv_grn_wip_outsource')->insert($pv_detail);

            }
        }
    }

    private function saveSvDetail($grn_Id,$request)
    {
        if($request->item_sv)
        {
            if(is_array($request->item_sv))
                $count = count($request->item_sv);
            else
                $count=1;
            
            for($i=0;$i<$count;$i++)
            {
                $id = str::uuid()->toString();
                $sv_detail=array(
                    'id'=>$id,
                    'grn_id'=>$grn_Id,
                    'batch'=>$request->batch,
                    'project'=>$request->project,
                    'category'=>'Service',
                    'name'=>$request->item_sv[$i],
                    'prod_id'=>0,
                    'description'=>$request->description_sv[$i],
                    'unit'=>$request->unit_sv[$i],
                    'qty'=>$request->qty_sv[$i],
                    'rate'=>$request->rate_sv[$i],
                    'amount'=>$request->amount_sv[$i],
                    'display'=>$i+1,
                );
                DB::table('inv_grn_wip_outsource')->insert($sv_detail);

            }
        }
    }

    public function view($id)
    {
        
          // dd($id);
            $grn=DB::table('inv_grns')
                    ->join('sys_warehouses','sys_warehouses.id','=','inv_grns.warehouse')
                    ->join('inv_grn_wip_outsource as grn_wip','grn_wip.grn_id','=','inv_grns.id')
                    ->join('inv_inventories','inv_inventories.source_id','=','inv_grns.id')
                    ->join('inv_products','inv_products.id','=','inv_inventories.prod_id')
                    ->select('inv_grns.*','grn_wip.batch','sys_warehouses.name as warehouse_name','inv_products.name as prod_name','inv_inventories.unit','inv_inventories.qty_in','inv_inventories.rate','inv_inventories.amount')
                    ->where('inv_grns.id',$id)
                    ->distinct('grn_wip.grn_id')
                    ->get()
                    ->first();
            
            //products issue detail
            $pi_detail=DB::table('inv_grns')
                            ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                                    ->where('grn_wip.name', '=', 'Own Product');
                            })
                            ->join('inv_products','inv_products.id','=','grn_wip.prod_id')
                        ->select('grn_wip.*','inv_products.name as prod_name')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();
            //products  used by vendor detail
            $pv_detail=DB::table('inv_grns')
                        ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                            $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                            ->where('grn_wip.name', '=', 'Ven Product');
                        })
                        ->join('inv_products','inv_products.id','=','grn_wip.prod_id')
                        ->select('grn_wip.*','inv_products.name as prod_name')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();

            //services  used by vendor detail
            $sv_detail=DB::table('inv_grns')
                            ->join('inv_grn_wip_outsource as grn_wip', function ($join) {
                                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                                    ->where('grn_wip.category', '=', 'Service');
                            })
                        ->select('grn_wip.*')
                        ->where('inv_grns.id',$id)
                        ->orderBy('grn_wip.display')
                        ->get();
          
            //dd($pi_detail_arr);
            return view('inventory.grn_wip_view',compact('grn','pi_detail','pv_detail','sv_detail'));
    }
}
