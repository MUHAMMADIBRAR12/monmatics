<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\inventoryLib;
use Carbon\Carbon;
class DoController extends Controller
{

    public function getInvoiceDetail(Request $request)
    {
        $invoce_record=DB::table('sal_invoices')
                        ->join('crm_customers','crm_customers.coa_id','=','sal_invoices.cst_coa_id')
                        ->select('sal_invoices.date','sal_invoices.warehouse','crm_customers.coa_id as cust_coa_id','crm_customers.name')
                        ->where('sal_invoices.id',$request->invoice_id)
                        ->first();

        $invoice_details = DB::table('sal_invoice_details')
                            ->join('inv_products as prod','prod.id','=','sal_invoice_details.prod_id')
                            ->join('sys_units as u1','u1.id','=','prod.sale_unit')           
                            ->select('sal_invoice_details.*', 'prod.name as prod_name', 'prod.sale_unit', 'u1.name as unit_name','u1.operator_value')
                            ->where('invoice_id','=',$request->invoice_id)->get();
                            //dbLib::convertUnit
        foreach($invoice_details as $invoice_detail)
        {
            $qtyInStock = inventoryLib::getStock($invoice_detail->prod_id, $invoce_record->warehouse)->qty;
            $qtyInStockSaleUnit = dbLib::convertUnit($qtyInStock,$invoice_detail->unit_name, $invoice_detail->prod_id);
            if($invoice_detail->qty_balance > 0)
            {
                $lineItems[] =  array("prod_id"=> $invoice_detail->prod_id ,
                            "prod_name"=>$invoice_detail->prod_name ,
                            "description"=>$invoice_detail->description  ,
                            "qty_inStock"=>$qtyInStockSaleUnit ,
                            "unit"=>$invoice_detail->unit_name ,
                            "operator_value"=>$invoice_detail->operator_value,
                            "qty_balance"=>$invoice_detail->qty_balance,
                            "rate"=>$invoice_detail->rate ,
                            "required_by"=>$invoice_detail->required_by,
                            "instruction"=>$invoice_detail->instruction ,
                            );
            }
            
        }
        
        
         $data = array ("main"=>  $invoce_record, "detail"=>$lineItems);
         return json_encode($data);
              
    }

    public function index()
    {
        $delivery_orders=DB::table('inv_delivery_order')
                ->join('crm_customers','crm_customers.coa_id','=','inv_delivery_order.cust_coa_id')
                ->select('inv_delivery_order.*','crm_customers.name as cust_name')
                ->get();
        return view('inventory.do_list',compact('delivery_orders'));
    }

    public function listData(Request $request)
    {
        $delivery_orders=DB::table('inv_delivery_order')
                ->join('crm_customers','crm_customers.coa_id','=','inv_delivery_order.cust_coa_id')
                ->select('inv_delivery_order.*','crm_customers.name as cust_name')
            ->get();
        return $delivery_orders;
    }

    public function form($id=null)
    {
        //dd($id);
        $warehouses= dbLib::getWareHouses();
        
        if($id)
        {

            $do = DB::table('inv_delivery_order as do')
                        ->leftJoin('crm_customers as cust', 'do.cust_coa_id', 'cust.coa_id')
                        ->select('do.*', 'cust.name')
                        ->where('do.id',$id)
                        ->whereNull('inv_id')
                        ->first();

            $do_details = DB::table('inv_delivery_order_detail as doDetail')                       
                        ->leftJoin('inv_products','inv_products.id','=','doDetail.prod_id')                       
                        ->select('inv_products.*','doDetail.*')
                        ->where('doDetail.do_id',$id)
                        ->orderBy('display')
                        ->get();
//            dd('Full Data', $do, $do_details);
            foreach($do_details as $do_detail)
            {
                $qtyInStock = inventoryLib::getStock($do_detail->prod_id, $do->warehouse)->qty;
                $qtyInStockSaleUnit = dbLib::convertUnit($qtyInStock,$do_detail->sale_unit,1);
                $OperatorValue = DB::table('sys_units')->where('id', $do_detail->sale_unit)->first('operator_value');
                
                // Balance Qty of Sale order
                $balanceQty = DB::table('sal_sale_order_details')
                                ->where('sale_orders_id',$do->sale_orders_id)
                                ->where('prod_id',$do_detail->prod_id)
                                ->select('qty_balance')
                                ->first();
                //dd($balanceQty);
                $lineItems [] = array(     
                                    "prod_id"=>$do_detail->prod_id,
                                    "prod_name"=>$do_detail->name,
                                    "description"=>$do_detail->description,                                    
                                    "primary_unit"=>$do_detail->primary_unit,
                                    "base_unit"=>$do_detail->sale_unit,         // This is sale unit of product
                                    "unit"=>$do_detail->unit,                                    
                                    "operator_value"=>$OperatorValue->operator_value,
                                    "qty_stock"=>($qtyInStockSaleUnit+$do_detail->qty),
                                    "qty_balance"=>$balanceQty->qty_balance +$do_detail->qty,
                                    "qty"=>$do_detail->qty,
                                    "require_date"=>$do_detail->required_date,
                                    "instruction"=>$do_detail->instruction,
                                   );
            }    
//            $lineItems  = (object)$lineItems;
//            dd($lineItems);
            $saleOrders=DB::table('sal_sale_orders')->select('date', DB::raw("concat(month,'-',LPAD(number,4,0)) as number"))
                    ->where('id',$do->sale_orders_id)->first();
            
            return view('inventory.do',compact('do','saleOrders','warehouses','lineItems'));   
        }
        else
        {
            $saleOrders=DB::table('sal_sale_orders')->select('id',DB::raw("concat(month,'-',LPAD(number,4,0)) as number"))
                    ->where('status','Pending')                    
                    ->orderBy('month')->orderBy('number')->get();
            return view('inventory.do',compact('warehouses','saleOrders'));
        }

    }

    public function save(Request $request)
    {
       
        $do = array (
            "date"=>$request->date,
            "sale_orders_id"=>$request->saleOrderNo,
            "cust_coa_id"=>$request->customer_id,
            "warehouse"=>$request->warehouse,
            "veh_no"=>$request->veh_no,
            "delivery_name"=>$request->delivery_name,
            "trucking_company"=>$request->trucking_company,
            "bilty_no"=>$request->bilty_no,
            "remarks"=>$request->remarks,
            "receiving_detail"=>$request->receiving_detail,
            //"delivery_status"=>$request->delivery_man, 
        );
        //dd($request->id); 
        if($request->id)
        {
            $do_id=$request->id;
            $do['updated_at']= Carbon::now();
            DB::table('inv_delivery_order')->where('id', '=',$request->id)->update($do); 
            
            ////////////////////// inv_inventories reversal transaction //////////////
            // In edit mode first we add all issue stock against this do with same rate
            // Remaing issue process will remain same as of new D.O
            $inventoriesStock = DB::table('inv_inventories')
                                    ->where('source_id', $request->id)
                                    ->where('qty_out', '>', '0')
                                    ->get();
            
            foreach($inventoriesStock as $stock)
            {
                $dataQuery = array(
                            'id'=>str::uuid()->toString(), 
                            'date'=>$stock->date, 
                            'month'=>$stock->month, 
                            'warehouse_id'=>$stock->warehouse_id, 
                            'source'=>$stock->source . '-update', 
                            'source_id'=>$stock->source_id, 
                            'source_detail_id'=>$stock->source_detail_id, 
                            'company_id'=>$stock->company_id, 
                            'status'=>$stock->status, 
                            'approvel'=>$stock->approvel, 
                            'batch'=>$stock->batch, 
                            'project_id'=>$stock->project_id, 
                            'prod_id'=>$stock->prod_id, 
                            'qty_in'=>$stock->qty_out, 
                            'qty_out'=>0, 
                            'qty_issue'=>0, 
                            'balance'=>$stock->qty_out, 
                            'unit'=>$stock->unit, 
                            'rate'=>$stock->rate, 
                            'amount'=>$stock->amount, 
                            'description'=>$stock->description.'Record is updated.', 
                            'updated_by'=>$stock->updated_by, 
                            'created_at'=>$stock->created_at, 
                            'updated_at'=>$stock->updated_at,                             
                            'batch_no'=>$stock->batch_no,
                            'number'=>$stock->number,
                            
                        );
                DB::table('inv_inventories')->insert($dataQuery);
            }
            ////////////////////// END OF inv_inventories reversal transaction //////////////
            ///////////////// Sale Order Detail table adjuestment(Only in Edit mode) //////////////////
            $previousDoDatas = DB::table('inv_delivery_order_detail')->where('do_id','=',$do_id)->get();
            foreach($previousDoDatas as $previousDoData)
            {
                DB::table('sal_sale_order_details')                            
                    ->where('prod_id',$previousDoData->prod_id)
                    ->where('sale_orders_id',$request->saleOrderNo)
                    ->decrement('qty_issue',$previousDoData->qty, ['qty_balance'=> DB::raw('qty-qty_issue')])   ; 
            }
            
            ///////////////// Sale Order Detail table adjuestment(Only in Edit mode) //////////////////
            
            
        }
        else
        {
            $id = str::uuid()->toString();
            $do['id']=$id ;
            $do['company_id']= session('companyId'); 
            $do['user_id'] = Auth::id(); 
            $do['month']= dbLib::getMonth($request->date);
            $do['number']= dbLib::getNumber('inv_delivery_order');
            $do['created_at']= Carbon::now();
            //insert
            DB::table('inv_delivery_order')->insert( $do);
            $do_id= $id;
        }
        
        //this function is used to store valu in inv_delivery_order_detail        
        $this->updateDoDetail($do_id, $request);

        return redirect('Inventory/Do');
    }

    private function updateDoDetail($do_id,$request)
    {
        //dd($request->input());
        DB::table('inv_delivery_order_detail')->where('do_id','=',$do_id)->delete();
        $count = count($request->item_ID);

        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $doDetail = array (     
              "id"=>$id,
              "do_id"=>$do_id,
              "prod_id"=>$request->prd_id[$i],
              "description"=>$request->description[$i],
              "unit"=>$request->unit[$i],
              //"qty_stock"=>$request->qty_in_stock[$i],
              "qty"=>$request->qty[$i],
              "required_date"=>$request->required_date[$i],
              "instruction"=>$request->instruction[$i],
              "display"=>$i+1,
            );
            DB::table('inv_delivery_order_detail')->insert($doDetail);

            //inventory data
            $inventory = array (
                "date"=>$request->date,
                "warehouse_id"=>$request->warehouse,
                "source"=>'inv_delivery_order',
                "source_id"=>$do_id,
                "source_detail_id"=>$id,
                "prod_id"=>$request->prd_id[$i],
                //"qty_in"=>$request->qty[$i],
                "qty_out"=>$request->qty[$i] * $request->operator_value[$i],
                "unit"=>$request->unit[$i],
                "description"=>$request->remarks,
            );
         
            ////////////// This section is commneted and can be used first we create Invoice and then issue Delivery order. 
            // The following commented code is not useless.    
            // set Invoice detail qty
            /*
            DB::table('sal_invoice_details')                            
                ->where('prod_id',$request->prd_id[$i])
                ->where('invoice_id',$request->invoice_no)
                ->increment('qty_issue',$request->qty[$i], ['qty_balance'=> DB::raw('qty-qty_issue')])   ; 
               
            
            $blance_qty = DB::table('sal_invoice_details')
                    ->select(DB::raw('SUM(qty_balance) as balance'))
                    ->where('invoice_id',$request->invoice_no)
                    ->first();
           
            if($blance_qty->balance == 0)
            {
                DB::table('sal_invoices')                
                ->where('id',$request->invoice_no)
                ->update(['stock_issue_status'=>'completed']);
                
            }    
            */
            DB::table('sal_sale_order_details')                            
                ->where('prod_id',$request->prd_id[$i])
                ->where('sale_orders_id',$request->saleOrderNo)
                ->increment('qty_issue',$request->qty[$i], ['qty_balance'=> DB::raw('qty-qty_issue')])   ; 
               
            
            $blance_qty = DB::table('sal_sale_order_details')
                    ->select(DB::raw('SUM(qty_balance) as balance'))
                    ->where('sale_orders_id',$request->saleOrderNo)
                    ->first();
           
            if($blance_qty->balance == 0)
            {
                DB::table('sal_sale_orders')                
                ->where('id',$request->saleOrderNo)
                ->update(['status'=>'Completed']);
                
            }    
            //DB::table('sale_invoe')->increment(Field and value)->where(prod_id and invoice_id )->update(balnce qty=qty - issue); 
            //dd($inventory);
            $inventoryData = inventoryLib::issueInventory($inventory);
            
            // Set Inventory Rate in Delivery order details which help while creating invoice. 
            // The Rate recv from $inventoryData is base rate, to keep in inv_delivery_order_detail
            // we need to convert it into sale rate by multiplying by operator value. 
            $updateRate = array (
                "inventory_rate"=>$inventoryData['rate'] * $request->operator_value[$i],
            );
            DB::table('inv_delivery_order_detail')
                    ->where('do_id','=',$do_id)
                    ->where('prod_id', $request->prd_id[$i])
                    ->update($updateRate);
            
          //  dd($inventoryData);
        }  // end of foreach 
    }

    public function report(Request $request)
    {
        $companyId = session('companyId');
        $month= session('month');
        $whereDateCluse = array();
        if($request->customer_id)
        {
            $arrCustomer = array ('inv_delivery_order.cust_coa_id', '=',$request->customer_id);
            array_push($whereDateCluse,$arrCustomer);
        }
        if($request->delivery_status)
        {
            $arrDelivery = array ('inv_delivery_order.delivery_status', '=', $request->delivery_status);
            array_push($whereDateCluse,$arrDelivery);
        }
        $delivery_orders=DB::table('inv_delivery_order')
                ->join('crm_customers','crm_customers.coa_id','=','inv_delivery_order.cust_coa_id')
                ->select('inv_delivery_order.*','crm_customers.name as cust_name',DB::raw("concat(inv_delivery_order.month,'-',LPAD(inv_delivery_order.number,4,0)) as do_num"))
                ->where('inv_delivery_order.company_id','=',$companyId)
                ->where('inv_delivery_order.month','=',$month)
                ->where($whereDateCluse)
                ->get();
        return $delivery_orders;
    }

}
