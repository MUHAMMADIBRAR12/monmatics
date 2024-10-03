<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
class DO_TrackingController extends Controller
{
    public function do_detail(Request $request)
    {
        $do_detail=DB::table('inv_delivery_order_fmcg as do_tab')
                    ->join('crm_customers as cust','cust.coa_id','=','do_tab.cust_id')
                    ->join('crm_customers_address as cust_add','cust_add.cust_id','=','cust.id')
                    ->join('inv_delivery_order_detail_fmcg as do_detail','do_detail.do_id','=','do_tab.id')
                    ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                    ->select('do_tab.*','do_detail.id as do_detail_id','do_detail.prod_id','inv_products.name as prod_name','do_detail.qty_delivery','do_detail.remarks_delivered','cust.name as cust_name','cust.coa_id as cust_id','cust_add.address as cust_address')
                    ->where('do_tab.id',$request->do_id)
                    ->orderBy('do_detail.display')
                    ->get();
        return $do_detail;
    }

    public function index()
    {
        $do_tracking=DB::table('inv_delivery_order_fmcg')
                        ->join('crm_customers','crm_customers.coa_id','inv_delivery_order_fmcg.cust_id')
                        ->join('sale_order_approvel as soa','soa.id','inv_delivery_order_fmcg.soa_id')
                        ->join('sale_orders','sale_orders.id','soa.so_id')
                        ->select('inv_delivery_order_fmcg.*',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'crm_customers.name as cust_name')
                        ->where('inv_delivery_order_fmcg.status','Delivered')
                        ->where('inv_delivery_order_fmcg.company_id',session('companyId'))
                        ->orderBy('month')
                        ->orderBy('number')
                        ->get();
        return view('sale_fmcg.do_tracking_list',compact('do_tracking'));
    }

    public function form($id=null)
    {
        $do_numbers=DB::table('inv_delivery_order_fmcg')->select('id',DB::raw("concat(month,'-',LPAD(number,4,0)) as do_num"))->whereNull('status')->orderBy('month')->orderBy('number')->get();
        if($id)
        {
            $do_tracking=DB::table('inv_delivery_order_fmcg as do')
                        ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                        ->join('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                        ->select('do.*','crm_customers.name as cust_name','cust_add.address','cust_add.location')
                        ->where('do.id',$id)
                        ->get()
                        ->first();

            $do_tracking_detail=DB::table('inv_delivery_order_detail_fmcg as do_detail')
                        ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                        ->select('do_detail.*','inv_products.name as prod_name')
                        ->where('do_detail.do_id',$id)
                        ->get();
            
            return view('sale_fmcg.do_tracking',compact('do_numbers','do_tracking','do_tracking_detail'));
        }
        else
        {
            return view('sale_fmcg.do_tracking',compact('do_numbers'));
        }
        
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if($request->id)
        {
            $do_id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save do tracking record
            $this->store($request);
        }
        //save and update sale order detail
        $this->updateDoTracking_Detail($request);
        return redirect('Sales_Fmcg/DoTracking/List');
    }

    private function store($request)
    {
        $do = array (
            "veh_no"=>$request->veh_no,
            "bilty_no"=>$request->bilty_no,
            "delivery_name"=>$request->delivery_name,
            "delivery_confirm_by"=>$request->delivery_cnfrm_by,
            "delivery_date"=>$request->delivery_date,
            "do_tracking_date"=>$request->date,
            "freight"=>$request->freight,
            "transit_loss"=>$request->transit_loss,
            "other_amount"=>$request->other_amount,
            "remarks"=>$request->remarks,
            "status"=>'Delivered',
            "editable"=>0,
        ); 
        DB::table('inv_delivery_order_fmcg')->where('id',$request->do_num)->update($do);
    }

    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date); 
        $do = array (
            "id"=>$id ,
            "month"=>$month,
            "date"=>$request->date,
            "veh_no"=>$request->veh_no,
            "bilty_no"=>$request->bilty_no,
            "delivery_name"=>$request->delivery_name,
            "delivery_confirm_by"=>$request->delivery_cnfrm_by,
            "delivery_date"=>$request->delivery_date,
            "do_tracking_date"=>$request->date,
            "freight"=>$request->freight,
            "transit_loss"=>$request->transit_loss,
            "other_amount"=>$request->other_amount,
            "remarks"=>$request->remarks,
            "updated_at"=>Carbon::now(),
        ); 
        DB::table('inv_delivery_order_fmcg')->where('id', '=',$id)->update($do);
    }

    private function updateDoTracking_Detail($request)
    {
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $do_detail = array (     
                "qty_delivered"=>$request->qty_delivered[$i],
                "remarks_delivered"=>$request->remark[$i]
            );
            DB::table('inv_delivery_order_detail_fmcg')->where('id',$request->do_detail_id[$i])->update($do_detail);  
        }
    }

    public function view($id)
    {
        $do_tracking=DB::table('inv_delivery_order_fmcg as do')
                        ->join('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                        ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                        ->join('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                        ->select('do.*','crm_customers.name as cust_name','cust_add.address','cust_add.location','sys_warehouses.name as warehouse_name')
                        ->where('do.id',$id)
                        ->get()
                        ->first();

        $do_tracking_detail=DB::table('inv_delivery_order_detail_fmcg as do_detail')
                        ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                        ->select('do_detail.*','inv_products.name as prod_name')
                        ->where('do_detail.do_id',$id)
                        ->get();
        
        return view('sale_fmcg.do_tracking_view',compact('do_tracking','do_tracking_detail'));
    }
}
