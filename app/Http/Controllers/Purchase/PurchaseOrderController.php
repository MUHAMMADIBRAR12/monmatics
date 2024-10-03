<?php
namespace App\Http\Controllers\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use File;
use App\Libraries\inventoryLib;

class PurchaseOrderController extends Controller
{
  //display purchase detail
  public function PrDetail(Request $request)
  {
    $prDetails=DB::table('inv_purchase_requisitions')
                     ->join('inv_purchase_requistion_details as prd','prd.pr_id','=','inv_purchase_requisitions.id')
                     ->join('inv_products','inv_products.id','=','prd.prod_id')
                     ->join('sys_units','sys_units.id','=','inv_products.purchase_unit')
                     ->select('inv_purchase_requisitions.warehouse','inv_products.*','prd.*','sys_units.operator_value')
                     ->where('inv_purchase_requisitions.id','=',$request->prNum)
                     ->get();
            
       $pr_stock_detail=array();
      foreach($prDetails as $prDetail)
      {
        $pr_detail_arr = array();
        $pr_detail_arr['warehouse'] = $prDetail->warehouse;
        $pr_detail_arr['prod_name'] = $prDetail->name;
        $pr_detail_arr['prod_id'] = $prDetail->prod_id;
        $pr_detail_arr['description'] = $prDetail->description;
        $pr_detail_arr['qty_in_stock'] = inventoryLib::getStock($prDetail->prod_id,$prDetail->warehouse)->qty;
        $pr_detail_arr['reorder_level'] = $prDetail->reorder_level;     
        $pr_detail_arr['required_by_date'] = $prDetail->required_by_date;  
        $pr_detail_arr['packing_detail'] = $prDetail->packing_detail;  
        $pr_detail_arr['details'] ='code:'.$prDetail->code.' sku:'.$prDetail->sku;  
        $pr_detail_arr['unit'] = $prDetail->unit; 
        $pr_detail_arr['qty_ordered'] = $prDetail->qty_ordered; 
        $pr_detail_arr['purchase_rate'] = $prDetail->purchase_price; 
        $pr_detail_arr['amount'] = $prDetail->purchase_price * $prDetail->qty_ordered ; 
        $pr_detail_arr['operator_value'] = $prDetail->operator_value; 
        array_push($pr_stock_detail,$pr_detail_arr);
      }
      return $pr_stock_detail;
  }
    //display the  purchase order List
  public function index()
  {
    
    $purchaseOrders=DB::table('pur_purchase_orders')
                        ->join('sys_warehouses','sys_warehouses.id','=','pur_purchase_orders.warehouse')
                        ->join('pa_vendors','pa_vendors.id','=','pur_purchase_orders.ven_id')
                        ->select('pur_purchase_orders.*','sys_warehouses.name as w_name','pa_vendors.name')
                        ->where('pur_purchase_orders.company_id',session('companyId'))
                        ->orderBy('pur_purchase_orders.date','ASC')                   
                        ->orderBy('pur_purchase_orders.number','ASC')  
                        ->get();         
    return view('purchase.purchase_order_list',compact('purchaseOrders'));
  }

    //display the purchase order Form
  public function form($id=null)
  {

    $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();
    //get warehouses 
    $warehouses=dbLib::getWarehouses();
    //get purchase requestion numbers
    $purchaseRequestions=DB::table('inv_purchase_requisitions')->select('id','number','month')->get();
    //get tax rate 
    $taxList = dbLib::getTaxes();
    if($id)
    {
      
      $purchaseOrder=DB::table('pur_purchase_orders')
                         ->join('pa_vendors','pa_vendors.id','=','ven_id')
                         ->select('pur_purchase_orders.*','pa_vendors.name','pa_vendors.id as v_id')
                         ->where('pur_purchase_orders.id','=',$id)
                         ->get()
                         ->first();
                           
      $purchaseOrderDetails= DB::table('pur_purchase_order_details')
                                  ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                                  ->leftjoin('sys_units','sys_units.id','=','inv_products.purchase_unit')
                                  ->select('pur_purchase_order_details.*','inv_products.name','sys_units.operator_value')
                                  ->where('po_id','=',$id)
                                  ->orderBy('display')
                                  ->get();             
      $attachmentRecord=dbLib::getAttachment($id);
      $oldCurrencyCode = $purchaseOrder->currency;
                               
      return view('purchase.purchase_order',compact('currencies','warehouses','purchaseRequestions','taxList','purchaseOrder','purchaseOrderDetails','attachmentRecord','oldCurrencyCode')); 
    
    }
    return view('purchase.purchase_order',compact('currencies','warehouses','purchaseRequestions','taxList'));
  }
  public function save(Request $request)
  {
    //dd($request->input());
    if($request->id)
    {
      $po_Id=$request->id;
      $this->update($request);
    }
    else
    {
      //Save Purchase Requestion Record
      $po_Id= $this->store($request);
    }

    //save and update Purchase Requestion Detail Record
    $this->updatePurchaseOrderDetail($po_Id, $request);
    if($request->file) // save and update sale purchase order Attachment Record
      dbLib::uploadDocument($po_Id,$request->file);

    DB::table('inv_purchase_requisitions')->where('id',$request->prNum)->update(['editable'=>0]);
    return redirect('Purchase/PurchaseOrder/List');

  }
  private function store($request)
  {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('pur_purchase_orders');
        $id = str::uuid()->toString();
        $purchase_order = array (
            "id"=>$id ,
            "month"=>$month ,
            "number"=>$number,
            "date"=>$request->date,
            "warehouse"=>$request->warehouse,
            "po_type"=>$request->po_type,
            "purchaser"=>$request->purchaser,
            "importance"=>$request->importance,
            "pr_id"=>$request->prNum,
            "status"=>$request->status,
            "close_status"=>0,
            "note"=>$request->note,
            "company_id"=>$companyId,
            "ven_id"=>$request->vendor_ID,
            "user_id"=>$userId,
            "currency" => $request->currency,
            "created_at"=>Carbon::now(), 
        ); 
        DB::table('pur_purchase_orders')->insert($purchase_order);
        return $id;
  }
  private function update($request)
  {
        $month = dbLib::getMonth($request->date);
        $purchase_order = array (
            "month"=>$month ,
            "date"=>$request->date,
            "warehouse"=>$request->warehouse,
            "po_type"=>$request->po_type,
            "purchaser"=>$request->purchaser,
            "importance"=>$request->importance,
            "pr_id"=>$request->prNum,
            "status"=>$request->status,
            "note"=>$request->note,
            "ven_id"=>$request->vendor_ID,
            "currency" => $request->currency,

            "updated_at"=>Carbon::now(),    
        );
        DB::table('pur_purchase_orders')->where('id', '=', $request->id)->update($purchase_order);
  }

  private function updatePurchaseOrderDetail($po_Id, $request)
  {

      DB::table('pur_purchase_order_details')->where('po_id','=',$po_Id)->delete();
      $count = count($request->item_ID);
      for($i=0;$i<$count;$i++)
      {
          $id = str::uuid()->toString();
          $base_qty=dbLib::convertUnit($request->qty[$i],$request->unit[$i],$request->item_ID[$i]);
          $base_qty = ($base_qty) ?$base_qty:1; 
          
          $purchaseOrderDetail = array (     
              "id"=>$id,
              "po_id"=>$po_Id,
              "prod_id"=>$request->item_ID[$i],
              "detail"=>$request->detail[$i],
              "description"=>$request->description[$i],
              "unit"=>$request->unit[$i],
              "qty"=>$request->qty[$i],
              "base_qty"=>$base_qty,
              "rate"=>$request->rate[$i],
              "base_rate"=>$request->net_amount[$i] / $base_qty,
              "amount"=>$request->amount[$i],
              "discount"=>$request->discount[$i],
              "discount_amount"=>$request->discount_amount[$i],
              "tax_percent"=>$request->sales_tax[$i],
              "tax_amount"=>$request->further_tax[$i],                
              "delivery_charges"=>$request->delivery_charges[$i],                
              "net_amount"=>$request->net_amount[$i], 
              "required_by_date"=>$request->required_date[$i],             
              "packing_detail"=>$request->packing_detail[$i],
              "display"=>$i+1,
          );
          //dd($purchaseOrderDetail);
          DB::table('pur_purchase_order_details')->insert($purchaseOrderDetail);  
      }
  }

  public function view($id)
  {
    $purchaseOrder=DB::table('pur_purchase_orders')
                          ->join('sys_warehouses','sys_warehouses.id','=','pur_purchase_orders.warehouse')
                         ->join('pa_vendors','pa_vendors.id','=','ven_id')
                         ->select('pur_purchase_orders.*','pa_vendors.name','pa_vendors.id as v_id','sys_warehouses.name as warehouse_name')
                         ->where('pur_purchase_orders.id','=',$id)
                         ->get()
                         ->first();
                           
      $purchaseOrderDetails= DB::table('pur_purchase_order_details')
                                  ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                                  ->leftjoin('sys_units','sys_units.id','=','inv_products.purchase_unit')
                                  ->select('pur_purchase_order_details.*','inv_products.name','sys_units.operator_value')
                                  ->where('po_id','=',$id)
                                  ->orderBy('display','asc')
                                  ->get();             
                        
      $attachmentRecord=dbLib::getAttachment($id);
      return view('purchase.purchase_order_view',compact('purchaseOrder','purchaseOrderDetails','attachmentRecord')); 
    
  }




public function destroy(Request $request,$id)
{
   $id = $request->route('id'); // Try this line to explicitly access the 'id' parameter

  // dd($request->all());
  DB::table('pur_purchase_order_details')->where('po_id', $id)->delete();

  // Delete from "pur_purchase_orders"
  DB::table('pur_purchase_orders')->where('id', $id)->delete();

  return redirect('Purchase/PurchaseOrder/List');
}



  
    
    
}
