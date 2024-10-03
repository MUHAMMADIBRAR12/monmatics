<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\inventoryLib;
use App\Libraries\accountsLib;
use App\Libraries\wip_inventoryLib;
class GIN_WIP_ReturnController extends Controller
{
    public function index()
    {
        $gin_wip_return_list=DB::table('inv_gins')
                                ->join('sys_warehouses','sys_warehouses.id','=','inv_gins.warehouse')
                                ->join('fs_transmains','fs_transmains.id','=','inv_gins.inv_id')
                                ->select('inv_gins.*','sys_warehouses.name as warehouse_name','fs_transmains.post_status')
                                ->where('inv_gins.company_id',session('companyId'))
                                ->get();
        return view('inventory.gin_wip_return_list',compact('gin_wip_return_list'));
    }

    public function getWipStock(Request $request)
    {
        $stock=wip_inventoryLib::get_wip_inventory_balance($request->id);
        $rate=DB::table('wip_inventories')->select('rate')->where('prod_id',$request->id)->orderBy('date','desc')->first();
        $result=array(
            'stock'=>$stock,
            'rate'=>(isset($rate)) ? $rate->rate : 0
        );
        return $result;
    }
    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        $accounts=dbLib::wipAccounts();
        if($id)
        {
            $gin_wip_return=DB::table('inv_gin_wip_returns')
                            ->join('sys_warehouses','sys_warehouses.id','=','inv_gin_wip_returns.warehouse')
                            ->join('fs_coas','fs_coas.id','=','inv_gin_wip_returns.account')
                            ->select('inv_gin_wip_returns.*','sys_warehouses.name as warehouse_name','fs_coas.name as account_name')
                            ->where('inv_gin_wip_returns.id',$id)
                            ->first();
            $result=DB::table('inv_gin_wip_return_details')
                                    ->join('inv_products','inv_products.id','=','inv_gin_wip_return_details.prod_id')
                                    ->select('inv_gin_wip_return_details.*','inv_products.name as prod_name','inv_products.coa_id as prod_coa_id')
                                    ->where('inv_gin_wip_return_details.ginwr_id',$id)
                                    ->get();
            $gin_wip_return_details=array();
            foreach($result as $data)
            {
                $row=array(
                    'prod_name'=>$data->prod_name,
                    'prod_id'=>$data->prod_id,
                    'prod_coa_id'=>$data->prod_coa_id,
                    'description'=>$data->description,
                    'unit'=>$data->unit,
                    'rate'=>$data->rate,
                    'qty_in_stock'=>wip_inventoryLib::get_wip_inventory_balance($data->prod_id),
                    'qty_return'=>$data->qty_return,
                );
                array_push($gin_wip_return_details,$row);
            }

            $attachmentRecord=dbLib::getAttachment($id);
            return view('inventory.gin_wip_return',compact('gin_wip_return','warehouses','accounts','gin_wip_return_details','attachmentRecord'));
        }
        else
        {
            $whereDateCluse=array();
            array_push($whereDateCluse,array ('inv_gins.gin_wip_return_status', '=',1));
            array_push($whereDateCluse,array ('inv_gins.wip_id','<>',''));
            $gin_numbers=dbLib::getDocumentNumbers('inv_gins',$whereDateCluse);
            return view('inventory.gin_wip_return',compact('gin_numbers','warehouses','accounts'));
        }
    }

    public function getGin_wipDetail(Request $request)
    {
        $gin=DB::table('inv_gins')
                    ->join('wip_material_issue_requests as mir','mir.id','=','inv_gins.wip_id')
                    ->join('sys_warehouses','sys_warehouses.id','inv_gins.warehouse')
                    ->join('sys_departments','sys_departments.id','=','mir.department')
                    ->select('mir.date as mir_date',DB::raw("concat(mir.month,'-',LPAD(mir.number,4,0)) as mir_num"),'sys_warehouses.name as warehouse_name','sys_warehouses.id as warehouse_id','sys_departments.name as department_name','sys_departments.id as department_id')
                    ->where('inv_gins.id',$request->gin_id)
                    ->first();
        $gin_detail=DB::table('inv_gin_details')
                        ->join('inv_products','inv_products.id','=','inv_gin_details.prod_id')
                        ->leftJoin('sys_units','sys_units.name','=','inv_gin_details.unit')
                        ->select('inv_gin_details.*','inv_products.name as prod_name','sys_units.operator_value')
                        ->where('inv_gin_details.gin_id',$request->gin_id)
                        ->get();
        $gin_detail_arr=array();
        foreach($gin_detail as $detail)
        {
            $data=array(
                'header'=>$gin,
                'prod_id'=>$detail->prod_id,
                'prod_name'=>$detail->prod_name,
                'description'=>$detail->description,
                'unit'=>$detail->unit,
                'qty_issue'=>$detail->qty_issue,
                'operator_value'=>$detail->operator_value,
            );
            array_push($gin_detail_arr,$data);
        } 
        return $gin_detail_arr;
    }

    public function save(Request $request)
    {
        
        //************************************************** Financial Transaction ****************************************
        $transMains=array(
            "id"=>$request->trm_id,
            "date"=>$request->date,
            "voucher_type"=>7,
            "note"=>$request->note,
        );
        $trm_id=accountsLib::saveTransMain($transMains);

        $gin_return=array(
            'date'=>$request->date,
            'warehouse'=>$request->warehouse,
            'account'=>$request->wip_coa_id,
            'note'=>$request->note,
        );
        if($request->id)
        {
            $ginwr=$request->id;
            $gin_return['updated_at']=Carbon::now();
            DB::table('inv_gins')->where('id', '=',$request->id)->update($gin_return);
        }
        else
        {
            //Save Sales Return  Record
            $companyId = session('companyId'); 
            $userId = Auth::id(); 
            $month = dbLib::getMonth($request->date);
            $number = dbLib::getNumber('inv_gins');
            $id = str::uuid()->toString();
            $gin_return['id']=$id;
            $gin_return['company_id']=$companyId;
            $gin_return['created_by']=$userId;
            $gin_return['month']=$month ;
            $gin_return['number']=$number;
            $gin_return['trm_id']=$trm_id;
            $gin_return['created_at']=Carbon::now();
            DB::table('inv_gins')->insert($gin_return);
            $ginwr= $id; 
        }
        //save and update sale return details Record
        $this->updateGinWipReturnDetail($ginwr, $request,$trm_id);

        if($request->file)  // save and update Gin wip return Attachment
            dbLib::uploadDocument($ginwr,$request->file);
        
        // //Also update delivery order table.if delivery order is return it will not show to in dropdown in sale return form.
        // DB::table('inv_gins')->where('id',$request->gin_id)->update(['gin_wip_return_status'=>0]);
        
        return redirect('Inventory/GIN_WIP_Return/List');
    }

    private function updateGinWipReturnDetail($ginwr,$request,$trm_id)
    {
        DB::table('inv_gin_wip_return_details')->where('ginwr_id','=',$ginwr)->delete();
        DB::table('inv_inventories')->where('source_id','=',$ginwr)->delete();
        $total_amount=0;
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $ginWipReturnDetail = array (     
              "id"=>$id,
              "ginwr_id"=>$ginwr,
              "prod_id"=>$request->item_ID[$i],
              "description"=>$request->description[$i],
              "unit"=>$request->unit[$i],
              "rate"=>$request->rate[$i],
              "qty_return"=>$request->qty_return[$i],
            );
            DB::table('inv_gin_wip_return_details')->insert($ginWipReturnDetail); 
            
            //inventory data
            $base_qty=dbLib::convertUnit($request->qty_return[$i],$request->unit[$i],$request->item_ID[$i]);
            $amount=$base_qty * $request->rate[$i];
            $total_amount +=$amount;
            $add_inventory = array(
                "date"=>$request->date,
                "warehouse_id"=>$request->warehouse,
                "source"=>'sales_return',
                "source_id"=>$ginwr,
                "source_detail_id"=>$id,
                //"status"=>$request->status,
                "prod_id"=>$request->item_ID[$i],
                "qty_in"=>$base_qty,
                "qty_out"=>0,
                "unit"=>$request->unit[$i],
                "rate"=>$request->rate[$i],
                "amount"=>$amount,
                "description"=>$request->note,
            ); 
            inventoryLib::addInventory($add_inventory);
        }

        //************************************************** Financial Transaction Detail ****************************************
        //First Delete Previous transaction from table if eixst and then add new transaction.This is help full when we edit transaction.
        DB::table('fs_transdetails')->where('trm_id',$trm_id)->delete();

        //Debit Inventory
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->prod_coa_id,
            "description"=>'GIN-WIP Return',
            "debit"=>$total_amount,
            "credit"=>0,
        );
        accountsLib::saveTransDetail($transDetails);

        //Credit Account(wip_accounts show on form)
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->wip_coa_id,
            "description"=>'GIN-WIP Return',
            "debit"=>0,
            "credit"=>$total_amount,
        );
        accountsLib::saveTransDetail($transDetails);
    }
}
