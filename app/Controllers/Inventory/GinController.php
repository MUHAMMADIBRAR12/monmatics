<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use App\Libraries\inventoryLib;
use Illuminate\Support\Str;
class GinController extends Controller
{
    public function invoiceNumber(Request $request)
    {
        // through sale invoice number
        if($request->inv_num)
        {
            $inv_detail=DB::table('sal_invoices')
                          ->join('sal_invoice_details','sal_invoice_details.invoice_id','=','sal_invoices.id')
                          ->join('crm_customers','crm_customers.coa_id','=','sal_invoices.cst_coa_id')
                          ->join('inv_products','inv_products.id','=','sal_invoice_details.prod_id')
                          ->leftjoin('sys_units','sys_units.id','=','inv_products.sale_unit')
                          ->select('sal_invoices.date as inv_date','sal_invoices.warehouse','sal_invoice_details.*','inv_products.name as pname','sys_units.name as sale_unit','crm_customers.coa_id','crm_customers.name as cname')
                          ->where('sal_invoices.id','=',$request->inv_num)
                          ->get();
                $stockQtyDetail=array();
                foreach($inv_detail as $detail)
                {
                    $stockQty = array();
                    $stockQty['warehouse_id'] = $detail->warehouse;
                    $stockQty['customer'] = $detail->cname;
                    $stockQty['customer_coa_id'] = $detail->coa_id;
                    $stockQty['inv_date'] = $detail->inv_date;
                    $stockQty['inv_detail_id'] = $detail->id;
                    $stockQty['prod_id'] =$detail->prod_id; 
                    $stockQty['prod_name'] = $detail->pname;
                    $stockQty['description'] = $detail->instruction;
                    $stockQty['sale_unit'] = $detail->sale_unit;
                    $stockQty['qty_in_stock'] = inventoryLib::getStock($detail->prod_id,$detail->warehouse)->qty;
                    $stockQty['qty_issue'] = $detail->qty;
                    $stockQty['rate'] = $detail->rate;
                    $stockQty['amount'] = $detail->amount;
                    $stockQty['required_by'] = $detail->required_by;
                    $stockQty['packing_detail'] = $detail->instruction;
                    array_push($stockQtyDetail,$stockQty);
                }
               
               
            return $stockQtyDetail;
        }
        // through vendor
        if($request->cst_coa_id)
        {
           $InvoiceNums=DB::table('sal_invoices')->select('id',DB::raw("concat(month,'-',LPAD(number,4,0)) as inv_num"))->where('cst_coa_id','=',$request->cst_coa_id)->get();
           return $InvoiceNums;        
        }
    } 

  

    public function gn_sal_list()
    {
        $gins = DB::table('inv_gins')
            ->join('sys_warehouses', 'sys_warehouses.id', '=', 'inv_gins.warehouse')
            ->where('inv_gins.company_id', session('companyId'))
            ->select('inv_gins.*', 'sys_warehouses.name')
            ->get();

        return view('inventory.gin_sales_list', compact('gins'));
    }

    public function gn_sal($id=null)
    {

        $units=DB::table('sys_units')->get();
        $warehouses=dbLib::getWarehouses();
        $invoiceNumbers=dbLib::getDocumentNumbers('sal_invoices');
        if($id)
        { 
            $gin=DB::table('inv_gins')
                     ->join('crm_customers','crm_customers.coa_id','=','inv_gins.cst_coa_id')
                     ->join('sal_invoices','sal_invoices.id','=','inv_gins.inv_id')
                     ->select('inv_gins.*','crm_customers.name','sal_invoices.date as inv_date')
                     ->where('inv_gins.id','=',$id)
                     ->first();
            $ginDetail=DB::table('inv_gin_details')
                           ->join('inv_products','inv_products.id','=','inv_gin_details.prod_id')
                           ->select('inv_gin_details.*','inv_products.name')
                           ->where('inv_gin_details.gin_id','=',$id)
                           ->get();
            $attachmentRecord=dbLib::getAttachment($id);
            return view('inventory.gin_sales',compact('units','warehouses','invoiceNumbers','gin','ginDetail','attachmentRecord'));
        }
        else
        {
        return view('inventory.gin_sales',compact('units','warehouses','invoiceNumbers'));
        }
    }
    public function gn_sal_save(Request $request)
    {

        $i = 0; // Initialize the variable $i with a value

    $reqst_qty = $request->reqst_qty; // Assuming reqst_qty is a field in the request

    if ($request->id) {
        $gin_Id = $request->id;
        $this->update($request);
    } else {
        // Save GIN Record
        $gin_Id = $this->save($request);
    }

    inventoryLib::updateInventory($request->item_ID[$i], $request->warehouse, $reqst_qty, $gin_Id);
        //invoice id
          $inv_Id=$request->inv_num;
        //save and update GIN Detail Record
        $this->updateGinDetail($inv_Id,$gin_Id, $request);

        if($request->file)  // save and update GIN Attachment Record
            dbLib::uploadDocument($gin_Id,$request->file);
            
        return redirect('Inventory/GinSalesList');
    }
    private function save($request)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_gins');
        $id = str::uuid()->toString();
        $gin = array (
            "id"=>$id ,
            "inv_id"=>$request->inv_num,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "cst_coa_id"=>$request->customer_ID,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
            "note"=>$request->note,
            
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
            "inv_id"=>$request->inv_num,
            "date"=>$request->date,
            "month"=>$month,
            "cst_coa_id"=>$request->customer_ID,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
            "note"=>$request->note,
            
        ); 
        DB::table('inv_gins')->where('id', '=',$id)->update($gin);
    }
    private function updateGinDetail($inv_Id,$gin_Id, $request)
    {
        DB::table('inv_gin_details')->where('gin_id','=',$gin_Id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $ginDetail = array (     
              "id"=>$id,
              "gin_id"=>$gin_Id,
              "inv_id"=>$inv_Id, 
              "inv_detail_id"=>$request->inv_detail_ID[$i],
             "prod_id"=>$request->item_ID[$i],
              "description"=>$request->description[$i],
              "unit"=>$request->unit[$i],
              "qty_issue"=>$request->qty_issue[$i],
              "rate"=>$request->rate[$i],
              "amount"=>$request->amount[$i],
              "required_by_date"=>$request->required_date[$i],                
              "packing_detail"=>$request->packing_detail[$i],
            );
            DB::table('inv_gin_details')->insert($ginDetail); 
             
            //inventory data
            $companyId = session('companyId'); 
            $inven_id = str::uuid()->toString();
            $month = dbLib::getMonth($request->date);
            $add_inventory = array (
                "id"=>$inven_id ,
                "display"=>$i+1,
                "date"=>$request->date,
                "month"=>$month,
                "warehouse_id"=>$request->warehouse,
                "source"=>'gin_sale',
                "source_id"=>$gin_Id,
                "source_detail_id"=>$id,
                "company_id"=>$companyId,
                "status"=>$request->status,
                "prod_id"=>$request->item_ID[$i],
                "qty_in"=>0,
                "qty_out"=>$request->qty_issue[$i],
                "unit"=>$request->unit[$i],
                "rate"=>$request->rate[$i],
                "amount"=>$request->amount[$i],
                "description"=>$request->note,
            ); 
            inventoryLib::addInventory($add_inventory);  
        }
    }
    

}
