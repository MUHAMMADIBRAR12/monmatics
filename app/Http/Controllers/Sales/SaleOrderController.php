<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Sales\SaleOrder;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use App\Libraries\accountsLib;
use App\Libraries\inventoryLib;

use App\Libraries\swPDF;
use PDF;
use TCPDF;
use  TCPDF_FONTS;

class SaleOrderController extends Controller
{
    public function index()
    {
        $whareClause=appLib::whereData('sal_sale_orders');
        $saleOrders = SaleOrder::join('crm_customers', 'cst_coa_id', '=', 'coa_id')
                    ->where($whareClause)
                    ->get(['sal_sale_orders.*', 'crm_customers.name']);
        
        return view ('sales.sale_order_list', compact('saleOrders'));
    }
    public function form($id=null)
    {
        $taxList = dbLib::getTaxes();
        $discountList = dbLib::getDiscounts();
        $paymentTerms = dbLib::getPaymentTerms();        
        $projects = dbLib::getProjects(1);
        $warehouses = dbLib::getWarehouses($status=1);
        if($id)
        {
            $saleOrder = SaleOrder::join('crm_customers', 'cst_coa_id', '=', 'coa_id')
                        ->where('sal_sale_orders.id',$id)
                        ->first(['sal_sale_orders.*', 'crm_customers.name']);
            
            $lineItems = DB::table('sal_sale_order_details')
                        ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                        ->select('sal_sale_order_details.*', 'inv_products.name')
                        ->where('sale_orders_id',$id)->orderBy('id')->get();


            $taxNDiscount =  DB::table('sal_sale_order_tax')->where('sale_orders_id','=',$id)->first();
           
            return view ('sales.sale_order',compact( 'paymentTerms', 'taxList','discountList', 'projects','warehouses','saleOrder','lineItems','taxNDiscount'));
        }        
        return view ('sales.sale_order',compact( 'paymentTerms', 'taxList','discountList', 'projects','warehouses'));
    }
    
    public function view($source)
    {
        
    }
    
   
    
    public function save(Request $request )
    {
        
   
         // Validation 
        $id = ($request->id)?$request->id:NULL;
        $arrValidation = array ('customer_ID'=>'required');
        $Validation = $request->validate($arrValidation);
        
        $info = dbLib::getInfo();
        $month = dbLib::getMonth($request->date);
        
        $id = $request->id;        
        $saleOrderData = array (           
            "cst_coa_id"=>$request->customer_ID,
            "payment_terms"=>$request->payment_terms ,            
            "customer_ref"=>$request->customer_ref ,
            "cur_id"=>$info['cur_id'] ,
            "cur_rate"=>$info['cur_rate'],            
            "cost_center_id"=>$request->cost_center ,
            "warehouse"=>$request->warehouse ,
            "note"=>$request->note ,
            "quot_id"=>$request->quotation_id ,           
            "company_id"=>$info['companyId'],
            "updated_by"=>$info['userId'] ,            
            "status"=>'Pending',
            "total_amount"=>$request->sub_total,
            "delete"=>0 ,            
        );
        if($request->id)
        {
            $id = $request->id;
            SaleOrder::where('id',$id)->update($saleOrderData);
        }
        else
        {
            $id = str::uuid()->toString();
            $saleOrderData['id']=$id;
            $saleOrderData['date']=$request->date;
            $saleOrderData['month']=$month;
            $saleOrderData['number']=dbLib::getNumber('sal_sale_orders');           
            SaleOrder::create ($saleOrderData);
            
        }
        // update value of Sale order details table. 
        $this->updateSaleOrderDetail($id, $request);
        $this->updateSaleOrderTaxDetail($id, $request);
        
        
       return redirect()->route('Sales/SaleOrder/List');
    }
    private function updateSaleOrderDetail($saleOrderId, $request)
    {
         // // insert values in sal_invoice_detail         
        DB::table('sal_sale_order_details')->where('sale_orders_id','=',$saleOrderId)->delete();
        $count = count($request->name_ID);
       // dd($request);
        $totalAmount = 0;
        for($i=0;$i<$count;$i++)
        {
            
            $lineItem = array (               
                "sale_orders_id"=>$saleOrderId,
                "prod_id"=>$request->name_ID[$i],
                "description"=>$request->description[$i],
                "qty_in_stock"=>$request->qty_in_stock[$i],
                "prod_unit"=>$request->unit[$i],
                "qty"=>$request->qty[$i],                
                "qty_issue"=>0,                
                "qty_balance"=>$request->qty[$i],                
                "rate"=>$request->rate[$i],
                "amount"=>$request->amount[$i],
                "tax_class"=>$request->tax_class[$i],
                "tax_amount"=>$request->tax_amount[$i],
                "total_amount"=>$request->total_amount[$i],                
                "instruction"=>$request->insturction[$i],
                "required_by"=>$request->required_by[$i],
            );
            DB::table('sal_sale_order_details')->insert($lineItem);
           
        }
        
    }
    private function updateSaleOrderTaxDetail($saleOrderId, $request)
    {
                
        DB::table('sal_sale_order_tax')->where('sale_orders_id','=',$saleOrderId)->delete();
            
        $invoiceTax = array (     

            "sale_orders_id"=>$saleOrderId,
            "tax_coa_id"=>$request->tax_coa_id,
            "tax_rate"=>$request->tax_rate,
            "tax_amount"=>$request->tax_amount_total,
            "disc_coa_id"=>$request->discount_coa_id,
            "disc_rate"=>$request->discount_rate,
            "disc_amount"=>$request->discount_amount,            
            "net_payable"=>$request->net_payable_amount,
        );
        DB::table('sal_sale_order_tax')->insert($invoiceTax);
    }
    
    public function getSaleOrder(Request $request)
    {
        $id = $request->id;
        $saleOrder = SaleOrder::join('crm_customers', 'cst_coa_id', '=', 'coa_id')
                        ->where('sal_sale_orders.id',$id)
                        ->first(['sal_sale_orders.*', 'crm_customers.name']);
            
        $lineItems = DB::table('sal_sale_order_details')
                    ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                    ->leftJoin('sys_units as u1','u1.id','=','inv_products.sale_unit')
                    ->select('sal_sale_order_details.*', 'inv_products.name', 'inv_products.sale_unit','base_unit', 'u1.name as unit_name','u1.operator_value')
                    ->where('sale_orders_id',$id)->orderBy('id')->get();
        $arrQtyInStock=array();
        foreach($lineItems as $lineItem)
        {
            $qtyInStock = inventoryLib::getStock($lineItem->prod_id, $saleOrder->warehouse)->qty;
            $qtyInStockSaleUnit = dbLib::convertUnit($qtyInStock,$lineItem->sale_unit, 1);
            $arrQtyInStock[$lineItem->prod_id]=$qtyInStockSaleUnit;
            
        }
        $arrSaleOrder = array ($saleOrder, $lineItems, $arrQtyInStock);
        return response()->json($arrSaleOrder);
    }
}
