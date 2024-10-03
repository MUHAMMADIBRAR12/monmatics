<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use Carbon\Carbon;
use App\Libraries\inventoryLib;
use App\Libraries\accountsLib;
class GoodReceivedController extends Controller
{
    
    public function igp_record(Request $request)
    {
        // through igp
        if($request->igp_id)
        {
            $igpDetails=DB::table('inv_igp')
                        ->join('inv_igp_details','inv_igp_details.igp_id','=','inv_igp.id') 
                        ->join('pur_purchase_orders as po','po.id','=','inv_igp.po_id')
                        ->join('inv_products','inv_products.id','=','inv_igp_details.prod_id')
                        ->join('sys_units as u1','u1.id','=','inv_products.primary_unit')
                        ->join('pa_vendors','pa_vendors.id','=','inv_igp.ven_id')
                        ->join('pur_purchase_order_details as po_detail','po_detail.id','=','inv_igp_details.po_detail_id')
                        ->select('inv_igp_details.*','inv_igp.id as igp_id','inv_igp.do_ref','inv_igp.warehouse','pa_vendors.name as vendor','pa_vendors.coa_id as ven_coa_id','inv_igp.ven_id'
                                ,'inv_igp.date as igp_date','inv_igp.month','inv_igp.number','po.id as po_id',
                                'po.month','po.number as po_num','po.date as po_date','inv_products.coa_id','inv_products.name as prod_name',
                                'u1.name as primary_unit','po_detail.rate','po_detail.discount_amount','po_detail.tax_amount','po_detail.delivery_charges')
                        ->where('inv_igp.id',$request->igp_id)
                        ->orderBy('inv_igp_details.display')
                        ->get();   
            return $igpDetails;
        }
        // through vendor
        if($request->ven_id)
        {
            $purchaseOrders=DB::table('inv_igp')
                            ->join('pur_purchase_orders','pur_purchase_orders.id','=','inv_igp.po_id')
                            ->select('pur_purchase_orders.id',DB::raw("concat(pur_purchase_orders.month,'-',LPAD(pur_purchase_orders.number,4,0)) as poNum"))
                            ->where('inv_igp.ven_id','=',$request->ven_id)
                            ->get();
            return $purchaseOrders;        
        }
    } 


    public function index()
    {
       
        $goodReceivedList=DB::table('inv_grns')
                              ->join('sys_warehouses','sys_warehouses.id','=','inv_grns.warehouse')
                              ->join('inv_igp','inv_igp.id','=','inv_grns.igp_id')
                              ->leftjoin('pa_vendors','pa_vendors.id','=','inv_grns.ven_id')
                              ->select('inv_grns.*','sys_warehouses.name','pa_vendors.name as vendor_name','inv_igp.month as igp_month','inv_igp.number as igp_number')
                              ->where('inv_grns.company_id',session('companyId'))
                              ->orderBy('inv_grns.month')
                              ->orderBy('inv_grns.number')
                              ->get();
        return view('inventory.good_received_list',compact('goodReceivedList'));
    }

    public function form($id=null)
    {
        //get warehouses
        $warehouses=dbLib::getWarehouses();
        //get units name
         $units=DB::table('sys_units')->select('id','name')->get();
        //get igp number
        $whereDateCluse = array();
        array_push($whereDateCluse,array ('inv_igp.editable', '=',1));
        $igps=dbLib::getDocumentNumbers('inv_igp',$whereDateCluse); 
        if($id)
        {
           //dd($id);
            $goodReceived=DB::table('inv_grns')
                            ->leftjoin('pa_vendors','pa_vendors.id','=','inv_grns.ven_id') 
                            ->leftjoin('inv_igp','inv_igp.id','=','inv_grns.igp_id')
                            ->leftjoin('pur_purchase_orders','pur_purchase_orders.id','=','inv_grns.po_id')
                            ->select('inv_grns.*','pa_vendors.name','pa_vendors.id as v_id','pa_vendors.coa_id as ven_coa_id','pur_purchase_orders.date as po_date','inv_igp.date as igp_date',DB::raw("concat(pur_purchase_orders.month,'-',LPAD(pur_purchase_orders.number,4,0)) as po_num"))
                            ->where('inv_grns.id','=',$id)
                            ->get()
                            ->first();
             $igp=DB::table('inv_igp')->select('id','month','number')->where('id',$goodReceived->igp_id)->first(); 
        
            $goodReceivedDetails= DB::table('inv_grn_details')
                            ->Join('inv_products', 'inv_products.id', '=','inv_grn_details.prod_id')
                            ->join('sys_units as u2','u2.id','=','inv_products.primary_unit')
                            ->Join('inv_igp_details', 'inv_igp_details.id', '=','inv_grn_details.igp_detail_id')
                            ->Join('pur_purchase_order_details as po_detail','po_detail.id', '=','inv_igp_details.po_detail_id')
                            ->select('inv_grn_details.*','inv_products.name','inv_products.coa_id','u2.name as base_unit','inv_igp_details.base_rate',
                            'po_detail.rate as po_rate','po_detail.discount_amount','po_detail.tax_amount','po_detail.delivery_charges')
                            ->where('grn_id','=',$id)
                            ->get(); 
            $attachmentRecord=dbLib::getAttachment($id);        
        return view('inventory.good_received',compact('warehouses','units','goodReceived','goodReceivedDetails','igp','attachmentRecord'));
       }
       else
       {
           return view('inventory.good_received',compact('warehouses','units','igps'));
       }
    }

    public function save(Request $request)
    { 
        //dd($request->input());
        

        if($request->id)
        {
            $grn_Id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save Good Record Record
            $grn_Id= $this->store($request);
            DB::table('inv_igp')->where('id',$request->igp_id)->update(['editable'=>0]);  
        }
        //save and update Good Received Detail Record
        $this->updateGoodReceivedDetail($grn_Id,$request);
         
        
        if($request->file)  // save and update Purchase Requestion Attachment Record
            dbLib::uploadDocument($grn_Id,$request->file);
        
        return redirect('Inventory/GoodReceived/List');
    }
    private function store($request)
    {
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_grns');
        //dd($number);
        $id = str::uuid()->toString();
        $inv_grn = array (
            "id"=>$id ,
            "po_id"=>$request->po_id,
            "igp_id"=>$request->igp_id,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "ven_id"=>$request->vendor_ID,
            "company_id"=>$companyId,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "do_ref"=>$request->do_ref,
            "note"=>$request->note,
            "user_id"=>$userId,
            "editable"=>1,
            "created_at"=>Carbon::now(),  
        ); 
        //dd($inv_grn);
        DB::table('inv_grns')->insert($inv_grn);
        return $id;
    }
    private function update($request)
    {
        $month = dbLib::getMonth($request->date);
        $userId = Auth::id(); 
        $inv_grn = array (
            "igp_id"=>$request->igp_id,
            "date"=>$request->date,
            "month"=>$month,
            "ven_id"=>$request->vendor_ID,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "do_ref"=>$request->do_ref,
            "note"=>$request->note,
            "update_user_id"=>$userId,
            "updated_at"=>Carbon::now(),
             
        );
        DB::table('inv_grns')->where('id', '=',$request->id)->update($inv_grn);
    }
    private function updateGoodReceivedDetail($grn_Id, $request)
    {
        //dd($request->input());
        DB::table('inv_grn_details')->where('grn_id','=',$grn_Id)->delete();
        DB::table('inv_inventories')->where('source_id',$grn_Id)->delete();
        if (is_array($request->item_ID)) {
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            //dd($request->item_ID[$i]);
            if($request->qty_approved[$i]>0)
            {
                $id = str::uuid()->toString();
                $goodReceivedDetail = array (     
                "id"=>$id,              
                "grn_id"=>$grn_Id,
                "po_id"=>$request->po_id,
                "prod_id"=>$request->item_ID[$i],
                "igp_detail_id"=>$request->igp_detail_id[$i],
                "description"=>$request->description[$i],
                "unit"=>$request->unit[$i],
                "qty_received"=>$request->qty_received[$i],
                "rate"=>$request->rate[$i],
                "amount"=>$request->rate[$i] * $request->qty_approved[$i],
                "qty_approved"=>$request->qty_approved[$i],
                "qty_rejected"=>$request->qty_rejected[$i],
                "packing_detail"=>$request->packing_detail[$i],
                "display"=>$i+1,
                );
                DB::table('inv_grn_details')->insert($goodReceivedDetail);

                //****************************************************   inventory data   **************************************
                $base_qty=   dbLib::convertUnit($request->qty_approved[$i],$request->unit[$i], 2);  // Converting into primary unit for inventories table.
                $add_inventory = array (
                    "date"=>$request->date,
                    "warehouse_id"=>$request->warehouse,
                    "source"=>'grn_purchase',
                    "source_id"=>$grn_Id,
                    "source_detail_id"=>$id,
                    "status"=>$request->status,
                    "prod_id"=>$request->item_ID[$i],
                    "qty_in"=>$base_qty,
                    "balance"=> $base_qty,
                    "unit"=>$request->base_unit[$i],
                    "rate"=>$request->base_rate[$i],
                    "amount"=>$base_qty * $request->base_rate[$i],
                    "description"=>$request->note,
                ); 
                inventoryLib::addInventory($add_inventory);
            }
        }
    }
    }
    
    public function view($id)
    {
        $goodReceived=DB::table('inv_grns')
                            ->join('pa_vendors','pa_vendors.id','=','inv_grns.ven_id')
                            ->join('sys_warehouses','sys_warehouses.id','inv_grns.warehouse') 
                            ->leftjoin('pur_purchase_orders','pur_purchase_orders.id','=','inv_grns.po_id')
                            ->join('inv_igp','inv_igp.id','=','inv_grns.igp_id')
                            ->select('inv_grns.*','pa_vendors.name as vendor_name',DB::raw("concat(pur_purchase_orders.month,'-',LPAD(pur_purchase_orders.number,4,0)) as po_num"),'pur_purchase_orders.date as po_date',DB::raw("concat(inv_igp.month,'-',LPAD(inv_igp.number,4,0)) as igp_num"),'inv_igp.date as igp_date','sys_warehouses.name as warehouse_name')
                            ->where('inv_grns.id','=',$id)
                            ->get()
                            ->first();
        //dd( $goodReceived);
        $goodReceivedDetails= DB::table('inv_grn_details')
                            ->Join('inv_products', 'inv_products.id', '=', 'prod_id')
                            ->select('inv_grn_details.*','inv_products.name')
                            ->where('grn_id','=',$id)
                            ->get();   
        $attachmentRecord=dbLib::getAttachment($id); 
        return view('inventory.good_received_view',compact('goodReceived','goodReceivedDetails','attachmentRecord'));
    }
}
