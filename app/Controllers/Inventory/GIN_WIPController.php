<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use App\Libraries\inventoryLib;
use App\Libraries\accountsLib;
use App\Libraries\wip_inventoryLib;
class GIN_WIPController extends Controller
{
    public function mirNumber(Request $request)
    { 
        $warehouse=$request->warehouse_id;
        $mir_details=DB::table('wip_material_issue_requests as wmir')
                        ->leftjoin('wip_material_issue_details as wmid','wmid.mir_id','=','wmir.id')
                        ->leftjoin('inv_inventories', function($join)  use ($warehouse)
                            {
                                $join->on('inv_inventories.prod_id','=','wmid.prod_id');
                              //  $join->on('inv_inventories.warehouse_id','=',DB::raw("'".$warehouse."'"));
                            })
                        ->leftjoin('inv_products','inv_products.id','=','wmid.prod_id')
                        ->join('sys_departments','sys_departments.id','=','wmir.department')
                        ->leftjoin('sys_units as u1','u1.id','=','inv_products.sale_unit')
                        ->leftjoin('sys_units as u2','u2.id','=','u1.base_unit')
                        ->select('wmir.date','wmir.warehouse','wmir.department','sys_departments.name as department_name','wmid.*','inv_products.name as prod_name','inv_products.coa_id as prod_coa_id','u1.operator_value','u2.name as base_unit',DB::raw('sum(inv_inventories.balance) as stock'))
                        ->where('wmir.id','=',$request->mir_num)
                        ->groupBy('wmid.prod_id')
                        ->orderBy('wmid.display')
                        ->get();
        return $mir_details;
    }
    public function index()
    {
        $gin_wip=DB::table('inv_gins')
                    ->join('wip_material_issue_requests as mir','mir.id','=','inv_gins.wip_id')
                    ->join('sys_warehouses','sys_warehouses.id','inv_gins.warehouse')
                    ->select('inv_gins.*','sys_warehouses.name as warehouse','mir.batch',DB::raw("concat(mir.month,'-',LPAD(mir.number,4,0)) as mir_num"))
                    ->where('inv_gins.company_id',session('companyId'))
                    ->orderBy('inv_gins.month', 'ASC')
                    ->orderBy('inv_gins.number', 'ASC')
                    ->get();
        return view('inventory.gin_wip_list',compact('gin_wip'));
    }

    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        
        $whereDateCluse = array();
        array_push($whereDateCluse,array ('wip_material_issue_requests.close_status', '=',0));
        $mirNumbers=dbLib::getDocumentNumbers('wip_material_issue_requests',$whereDateCluse);
        $accounts=dbLib::wipAccounts();
        if($id)
        {
            $gin_wip=DB::table('inv_gins')
                        ->join('wip_material_issue_requests as mir','mir.id','=','inv_gins.wip_id')
                        ->join('sys_departments','sys_departments.id','=','mir.department')
                        ->select('inv_gins.*','mir.date as mir_date','mir.month as mir_month','mir.number as mir_number','sys_departments.name as department')
                        ->where('inv_gins.id',$id)->first();
            $gin_wip_details=DB::table('inv_gin_details')
                                ->join('inv_products','inv_products.id','inv_gin_details.prod_id')
                                ->join('sys_units','sys_units.id','=','inv_products.primary_unit')
                                ->select('inv_gin_details.*','inv_products.name as prod_name','sys_units.name as unit_name','sys_units.operator_value')
                                ->where('gin_id',$id)
                                ->orderBy('display')
                                ->get();
            $attachmentRecord=dbLib::getAttachment($id);
            return view('inventory.gin_wip',compact('warehouses','accounts','mirNumbers','gin_wip','gin_wip_details','attachmentRecord'));
        }
        else
        {

            return view('inventory.gin_wip',compact('warehouses','accounts','mirNumbers'));
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());

        $transMains=array(
            "id"=>$request->trans_id,
            "date"=>$request->date,
            "voucher_type"=>10,
            "note"=>$request->note,
        );
        $trm_id=accountsLib::saveTransMain($transMains);

        if($request->id)
        {
            $gin_id=$request->id;
            $this->update($request);
        }
        else
        {
            $gin_id= $this->store($request,$trm_id);   
        }
    
        $this->updateGinDetail($gin_id, $request,$trm_id);

        if($request->file)  // save and update GIN Attachment Record
            dbLib::uploadDocument($gin_id,$request->file);
        
        DB::table('wip_material_issue_requests')->where('id',$request->mir_num)->update(['editable'=> 0]);
        $balance=DB::table('wip_material_issue_details')->select(DB::raw('SUM(qty_order)-SUM(qty_issue) as balance'))->where('mir_id',$request->mir_num)->get();
        if($balance[0]->balance==0)
        {
            DB::table('wip_material_issue_requests')->where('id',$request->mir_num)->update(['close_status'=>1]);
        }
        return redirect('Inventory/GIN_WIP/List');
    }

    private function store($request,$trm_id)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_gins');
        $id = str::uuid()->toString();
        $gin = array (
            "id"=>$id ,
            "trans_id"=>$trm_id,
            "wip_coa_id"=>$request->wip_coa_id,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "user_id"=>$userId,
            "note"=>$request->note,
            "wip_id"=>$request->mir_num,
            
        ); 
        DB::table('inv_gins')->insert($gin);
        return $id;
    }

    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $gin = array (
            "id"=>$id ,
            "date"=>$request->date,
            "month"=>$month,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "update_user_id"=>$userId,
            "note"=>$request->note,  
        ); 
        DB::table('inv_gins')->where('id', '=',$id)->update($gin);
    }

    private function updateGinDetail($gin_id, $request,$trm_id)
    {
        $count = count($request->item_ID);
        //this section execute only when we update gin.
        if($request->id)
        {
            for($i=0;$i<$count;$i++)
            {
                $data=DB::table('inv_gin_details')->select('qty_issue')->where('inv_gin_details.gin_id',$request->id)->where('inv_gin_details.mir_detail_id',$request->mir_detail_id[$i])->first();
                DB::table('wip_material_issue_details')->where('id',$request->mir_detail_id[$i])->decrement('qty_issue',$data->qty_issue);
            }

            //add issue qnty in stock 
            $issue_stocks=DB::table('inv_inventories')->where('source_id',$request->id)->get();
            foreach($issue_stocks as $row)
            {
                $data= array(
                    "date"=>$row->date,
                    "warehouse_id"=>$row->warehouse_id,
                    "source"=>'gin_wip update',
                    "source_id"=>$request->id,
                    "source_detail_id"=>$request->id,
                    "status"=>$row->status,  
                    "prod_id"=>$row->prod_id,  
                    "qty_in"=>$row->qty_out,   
                    "balance"=>$row->qty_out,   
                    "unit"=>$row->unit,   
                    "rate"=>$row->rate,   
                    "amount"=>$row->amount,
                    "description"=>'Stock adjustment while D.O edit'   
                );
                inventoryLib::addInventory($data);
            }
        }
        DB::table('inv_gin_details')->where('gin_id','=',$gin_id)->delete();
        DB::table('fs_transdetails')->where('trm_id','=',$trm_id)->delete();
        
        $total_amount=0;
        for($i=0;$i<$count;$i++)
        {
            //this check is for if we issue not qty or 0 then record will not save
            if($request->qty_issue[$i] > 0)
            {
                $id = str::uuid()->toString();
                //inventory data
                $inventory = array (
                    "date"=>$request->date,
                    "warehouse_id"=>$request->warehouse,
                    "source"=>'gin_wip',
                    "source_id"=>$gin_id,
                    "source_detail_id"=>$id,
                    "status"=>$request->status,
                    "prod_id"=>$request->item_ID[$i],
                    "qty_in"=>0,
                    "qty_out"=>$request->qty_issue[$i] * $request->operator_value[$i],
                    "unit"=>$request->base_unit[$i],
                    "description"=>$request->note,
                ); 
                $inventoryData = inventoryLib::issueInventory($inventory); 
                
                $ginDetail = array (     
                    "id"=>$id,
                    "display"=>$i+1,
                    "gin_id"=>$gin_id,
                    "mir_detail_id"=>$request->mir_detail_id[$i],
                    "prod_id"=>$request->item_ID[$i],
                    "description"=>$request->description[$i],
                    "unit"=>$request->unit[$i],
                    "qty_order"=>$request->qty_order[$i],
                    "qty_issue"=>$request->qty_issue[$i],
                    "base_rate"=>$inventoryData['rate'],
                    "operator_val"=> $request->operator_value[$i],

                );
                DB::table('inv_gin_details')->insert($ginDetail);
                DB::table('wip_material_issue_details')->where('id',$request->mir_detail_id[$i])->where('prod_id',$request->item_ID[$i])->increment('qty_issue',$request->qty_issue[$i]); 
                
                //This wip_invenotry array add in wip_inventories table to maintain stock 
                $wip_inventory=array(
                    'date'=>$request->date,
                    'gin_id'=>$gin_id,
                    'dept_id'=>$request->department,
                    'prod_id'=>$request->item_ID[$i],
                    "qty_in"=>$request->qty_issue[$i] * $request->operator_value[$i],
                    "qty_out"=>0,
                    "rate"=>$inventoryData['rate'],
                    "amount"=>$inventoryData['amount'],
                );
                wip_inventoryLib::add_wip_inventory($wip_inventory); 
            }
            $total_amount +=$inventoryData['amount'];
        }

        // ********************************************** Transactions Detail ***************************************************** //
        $description='GIN-WIP Against: '.dbLib::getSpecialDocument('inv_gins',$gin_id);
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->wip_coa_id,
            "description"=>$description,
            "debit"=>$total_amount,
            "credit"=>0,
        );
        accountsLib::saveTransDetail($transDetails);

        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->prod_coa_id,
            "description"=>$description,
            "debit"=>0,
            "credit"=>$total_amount,
        );
        accountsLib::saveTransDetail($transDetails);
    
    }
 
}
