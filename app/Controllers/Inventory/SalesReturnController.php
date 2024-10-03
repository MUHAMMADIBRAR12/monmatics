<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
use Carbon\Carbon;
use App\Libraries\inventoryLib;
class SalesReturnController extends Controller
{
    public function index()
    {

        $salesReturnList=DB::table('sale_returns_fmcg')
                            ->join('sys_warehouses','sys_warehouses.id','=','sale_returns_fmcg.warehouse_id')
                            ->join('crm_customers','crm_customers.coa_id','=','sale_returns_fmcg.cust_coa_id')
                            ->select('sys_warehouses.name as warehouse_name','crm_customers.name as cust_name','sale_returns_fmcg.*')
                            ->where('sale_returns_fmcg.company_id',session('companyId'))
                            ->orderBy('sale_returns_fmcg.month')
                            ->orderBy('sale_returns_fmcg.number')
                            ->get();
    
        return view('inventory.sales_return_list',compact('salesReturnList'));
    }

    public function getDoDetail(Request $request)
    {
        $do=DB::table('inv_delivery_order_fmcg')
                    ->join('crm_customers','crm_customers.coa_id','=','inv_delivery_order_fmcg.cust_id')
                    ->join('sys_warehouses','sys_warehouses.id','inv_delivery_order_fmcg.warehouse')
                    ->select('inv_delivery_order_fmcg.date','crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id','sys_warehouses.name as warehouse_name','sys_warehouses.id as warehouse_id')
                    ->where('inv_delivery_order_fmcg.id',$request->do_id)
                    ->first();
        $do_detail=DB::table('inv_delivery_order_detail_fmcg')
                       ->join('sale_order_detail_approvel','inv_delivery_order_detail_fmcg.soa_detail_id','=','sale_order_detail_approvel.id')
                       ->join('sale_rate_code_fmcg', function($join){
                            $join->on('sale_rate_code_fmcg.id', '=', 'sale_order_detail_approvel.rate_code_id');
                            $join->on('sale_rate_code_fmcg.prod_id', '=', 'sale_order_detail_approvel.prod_id');
                        })
                        ->join('inv_products','inv_products.id','=','inv_delivery_order_detail_fmcg.prod_id')
                        ->leftJoin('sys_units','sys_units.id','=','inv_products.sale_unit')
                        ->select('sale_order_detail_approvel.*','inv_delivery_order_detail_fmcg.qty_delivery','inv_products.name as prod_name',DB::raw("concat(sale_rate_code_fmcg.month,'-',LPAD(sale_rate_code_fmcg.number,4,0)) as rate_code_num"),'sys_units.operator_value')
                        ->where('inv_delivery_order_detail_fmcg.do_id',$request->do_id)
                        ->get();
        $do_detail_arr=array();
        foreach($do_detail as $detail)
        {
            $data=array(
                'header'=>$do,
                'prod_id'=>$detail->prod_id,
                'prod_name'=>$detail->prod_name,
                'unit'=>$detail->unit,
                'operator_value'=>$detail->operator_value,
                'qty_issue'=>$detail->qty_delivery,
                'rate_code_id'=>$detail->rate_code_id,
                'rate_code_num'=>$detail->rate_code_num,
                'base_rate'=>$detail->base_rate,
                'sales_tax'=>$detail->sales_tax,
                'fed'=>$detail->fed,
                'further_tax'=>$detail->further_tax,
                'inclusive_value'=>$detail->inclusive_value,
                'lu'=>$detail->lu,
                'freight'=>$detail->freight,
                'other'=>$detail->other_discount,
                'fixed_margin'=>$detail->fixed_margin,
                'value_before_discount'=>$detail->amount_before_discount,
                'trade_offer'=>$detail->trade_offer,
                'discount'=>$detail->discount,
                'value_after_discount'=>$detail->amount_after_discount,
                'adv_payment'=>$detail->adv_payment,
            );
            array_push($do_detail_arr,$data);
        } 
        return $do_detail_arr;
    } 
    
    public function form($id=null)
    {
        $whereDateCluse=array();
        array_push($whereDateCluse,array ('inv_delivery_order_fmcg.do_return_status', '=',1));
        $do_numbers=dbLib::getDocumentNumbers('inv_delivery_order_fmcg',$whereDateCluse);
        if($id)
        {
            
              $salesReturn=DB::table('sale_returns_fmcg')
                             ->join('crm_customers','crm_customers.coa_id','=','sale_returns_fmcg.cust_coa_id')
                             ->join('sys_warehouses','sys_warehouses.id','=','sale_returns_fmcg.warehouse_id')
                             ->join('inv_delivery_order_fmcg','inv_delivery_order_fmcg.id','sale_returns_fmcg.do_id')
                             ->select('sale_returns_fmcg.*','crm_customers.name as cust_name','sys_warehouses.name as warehouse_name','inv_delivery_order_fmcg.date as do_date',DB::raw("concat(inv_delivery_order_fmcg.month,'-',LPAD(inv_delivery_order_fmcg.number,4,0)) as do_num"))
                             ->where('sale_returns_fmcg.id','=',$id)
                             ->get()
                             ->first();
               $salesReturnDetail=DB::table('sale_return_detail_fmcg')
                                      ->join('inv_products', 'inv_products.id', '=', 'sale_return_detail_fmcg.prod_id')
                                      ->leftJoin('sys_units','sys_units.id','=','inv_products.sale_unit')
                                      ->join('sale_rate_code_fmcg', function($join){
                                        $join->on('sale_rate_code_fmcg.id', '=', 'sale_return_detail_fmcg.rate_code_id');
                                        $join->on('sale_rate_code_fmcg.prod_id', '=', 'sale_return_detail_fmcg.prod_id');
                                        })
                                      ->select('sale_return_detail_fmcg.*','inv_products.name as prod_name','sys_units.operator_value',DB::raw("concat(sale_rate_code_fmcg.month,'-',LPAD(sale_rate_code_fmcg.number,4,0)) as rate_code_num"))
                                      ->where('sale_return_detail_fmcg.srn_id','=',$id)
                                      ->get();
                $attachmentRecord=dbLib::getAttachment($id);
                //dd( $salesReturnDetail);
            return view('inventory.sales_return',compact('do_numbers','salesReturn','salesReturnDetail','attachmentRecord'));
        }
        else
        {
            return view('inventory.sales_return',compact('do_numbers'));
        }
    }
    public function save(Request $request)
    {
        //dd($request->input());
        //sale return data
        $salesReturn = array (
            "date"=>$request->date,
           // "soa_id"=>$request->date,
            "note"=>$request->note,
        ); 
        if($request->id)
        {
            $srn_Id=$request->id;
            $salesReturn['updated_at']=Carbon::now();
            DB::table('sale_returns_fmcg')->where('id', '=',$request->id)->update($salesReturn);
        }
        else
        {
            //Save Sales Return  Record
            $companyId = session('companyId'); 
            $userId = Auth::id(); 
            $month = dbLib::getMonth($request->date);
            $number = dbLib::getNumber('sale_returns_fmcg');
            $id = str::uuid()->toString();
            $salesReturn['id']=$id;
            $salesReturn['company_id']=$companyId;
            $salesReturn['user_id']=$userId;
            $salesReturn['month']=$month ;
            $salesReturn['number']=$number;
            $salesReturn['created_at']=Carbon::now();
            $salesReturn['cust_coa_id']=$request->cust_coa_id;
            $salesReturn['warehouse_id']=$request->warehouse_id;
            $salesReturn['do_id']=$request->do_num;
            DB::table('sale_returns_fmcg')->insert($salesReturn);
            $srn_Id= $id; 
        }
        //save and update sale return details Record
        $this->updateSalesReturnDetail($srn_Id, $request);

        if($request->file)  // save and update Sale Return Attachment
            dbLib::uploadDocument($srn_Id,$request->file);
        
        //Also update delivery order table.if delivery order is return it will not show to in dropdown in sale return form.
        DB::table('inv_delivery_order_fmcg')->where('id',$request->do_num)->update(['do_return_status'=>0]);
        return redirect('Inventory/SalesReturn/List');
    }

    private function updateSalesReturnDetail($srn_Id,$request)
    {
        DB::table('sale_return_detail_fmcg')->where('srn_id','=',$srn_Id)->delete();
        DB::table('inv_inventories')->where('source_id','=',$srn_Id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $salesReturnDetail = array (     
              "id"=>$id,
              "srn_id"=>$srn_Id,
              "prod_id"=>$request->item_ID[$i],
              "do_id"=>$request->do_num,
              "unit"=>$request->unit[$i],
              "qty_issue"=>$request->qty_issue[$i],
              "qty_return"=>$request->qty_return[$i],
              "rate_code_id"=>$request->rate_code_id[$i],               
              "base_rate"=>$request->base_rate[$i], 
              "sales_tax"=>$request->sales_tax[$i],              
              "fed"=>$request->fed[$i],              
              "further_tax"=>$request->further_tax[$i],
              "inclusive_value"=>$request->inclusive_value[$i],
              "lu"=>$request->lu[$i],
              "freight"=>$request->freight[$i],
              "other"=>$request->other[$i],
              "fixed_margin"=>$request->fixed_margin[$i],
              "amount_before_discount"=>$request->value_before_discount[$i],
              "trade_offer"=>$request->trade_offer[$i],
              "discount"=>$request->discount[$i],
              "amount_after_discount"=>$request->value_after_discount[$i],
              "adv_payment"=>$request->adv_payment[$i],
            );
            DB::table('sale_return_detail_fmcg')->insert($salesReturnDetail); 
            
            //inventory data
            $base_qty=$request->qty_return[$i] * $request->operator_value[$i];
            $add_inventory = array(
                "date"=>$request->date,
                "warehouse_id"=>$request->warehouse_id,
                "source"=>'sales_return',
                "source_id"=>$srn_Id,
                "source_detail_id"=>$id,
                //"status"=>$request->status,
                "prod_id"=>$request->item_ID[$i],
                "qty_in"=>$base_qty,
                "qty_out"=>0,
                "unit"=>$request->unit[$i],
                "rate"=>$request->base_rate[$i],
                "amount"=>$base_qty * $request->base_rate[$i],
                "description"=>$request->note,
            ); 
            inventoryLib::addInventory($add_inventory);
        }

    }
    
}
