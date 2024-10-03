<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Libraries\accountsLib;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Libraries\appLib;
use App\Libraries\purchaseLib;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseExpensesController extends Controller
{
    //
    public function index()
    {
//       $PoLists = DB::table('pur_purchase_expenses')
//               ->leftJoin ('pur_purchase_orders', 'pur_purchase_orders.id', '=', 'pur_purchase_expenses.po_id')
//               ->leftJoin('pa_vendors','pa_vendors.id','=','pur_purchase_orders.ven_id')
//               ->select('pur_purchase_expenses.id as pe_id', 'po_number', 'pur_purchase_orders.date as po_date', 'pa_vendors.name as ven_name')
//               ->where('pur_purchase_orders.status', '!=', '7')
//               ->orderBy('')
//               ->get();
       $PoLists = DB::table('pur_purchase_expenses')               
               ->select('pur_purchase_expenses.id as pe_id', 'po_number')
               ->get();        
    //   dd($PoLists);
       return view('purchase.purchase_expense_list', compact('PoLists'));
     //  return view('purchase.purchase_expense_list', compact());
    }

     //
     public function form()
     {  
        $count = appLib::padingZero(4);
        $purchaseExpenses = DB::table('pur_purchase_orders')->select('*') ->get();
        $coalist = DB::table('fs_coas')->select('*')->get();
        return view('purchase.purchase_expenses',compact('purchaseExpenses','count','coalist'));
     }

    public function saveExpenses(Request $request)
    {

        //adding vauues to the purcahse expense table      
        $results = dbLib::getDocumentNumbers('pur_purchase_orders', array('id'=> $request->pur_ord));
        $id = str::uuid()->toString();
        foreach($results as $result)
        {
           $doc_number = $result->doc_number;
        }
         
        //Creating the coa account
        $datas = array(            
            "coa_id" => request('parent_id'),
            "trans_group" => 1,
            "name" => $doc_number,
            "level" => 5,
            "order" => 3,
            "coa_display" => 1,
            "status" => 1,
            "editable" => 0,
            "company_id" => (request('trans_group') == 0) ? -1 : session('companyId'),
            "branch_id" => -1,
        );
        $trmId = DB::table('fs_coas')->insertGetId($datas);     
   
        $data =  [
            'id' =>$id,
            'po_id' => $request->pur_ord,
            'coa_id' =>  $trmId,
            'po_number' => $doc_number,
            'date' => $request->exp_date,
        ];

        $res = DB::table('pur_purchase_expenses')->insert($data);
        $selectedValue = $request->input('pur_ord');
        
        return redirect()->route('PurchaseExpenses',['id'=>$id]);

     }

    public function viewExpenses($peId)
    {
        $poNumbers =  DB::table('pur_purchase_expenses')->select('po_number')->where('id','=', $peId)->first();       
        $poNumber = $poNumbers->po_number;     
        $purchaseInvoices = DB::table('fs_purchase_invoices')
                            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_purchase_invoices.ven_coa_id')
                            ->leftJoin('sys_currencies', 'sys_currencies.id', '=', 'fs_purchase_invoices.cur_id')
                            ->select('fs_purchase_invoices.*', 'fs_coas.name', 'sys_currencies.name as cur_name')
                            ->where('pe_id','=', $peId)
                            ->get();
        return view('purchase.purchase_view_expense', compact('peId', 'poNumber', 'purchaseInvoices'));
    }
     
     public function bookExpenses($peId, $expId=null)
     {   
        $poNumbers =  DB::table('pur_purchase_expenses')->select('po_number', 'coa_id')->where('id','=', $peId)->first();  
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();
        $poNumber = $poNumbers->po_number;       
        $poCoaId = $poNumbers->coa_id;       
        if($expId)
        {
            $purchaseInvoice = DB::table('fs_purchase_invoices')
                            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_purchase_invoices.ven_coa_id')                            
                            ->select('fs_purchase_invoices.*', 'fs_coas.name')                            
                            ->where('fs_purchase_invoices.id','=', $expId)
                            ->first();            
            return view('purchase.purchase_expense_booking',compact('peId', 'expId', 'poNumber', 'poCoaId', 'purchaseInvoice', 'currencies'));              
        }
        return view('purchase.purchase_expense_booking',compact('peId', 'expId', 'poNumber', 'poCoaId', 'currencies'));
     }

     public function saveInvoice(Request $request)
     {
         
        //************************************************* Start Financial Transaction ***********************************
        $transMains = array(
            "id" => $request->trm_id,
            "date" => $request->date,
            "voucher_type" => 6,
            "note" => $request->note,
        );

        $trm_id = accountsLib::saveTransMain($transMains);
       
        //First Delete Previous transaction from table if eixst and then add new transaction.This is help full when we edit transaction.
        DB::table('fs_transdetails')->where('trm_id', $trm_id)->delete();
        
        //Stock Account
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $request->poCoaId,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->cur_rate,
            "description" => $request->description,
            "debit" => $request->amount,           
            "credit" => 0,
        );
        
        accountsLib::saveTransDetail($transDetails);
        
        //credit vendor
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $request->vendor_ID,
            "cur_id" => $request->cur_id,
            "cur_rate" =>  $request->cur_rate,
            "description" => $request->description,
            "debit" => 0,            
            "credit" => $request->amount,
        );
        accountsLib::saveTransDetail($transDetails);
        ///////////////////////////////////////// End of financial tranaction ////////////////////////////
        
        ///////////////////////////////// Purchase Invoice 
        //$results = dbLib::getDocumentNumbers('pur_purchase_orders', array('id'=> $request->pur_ord));
        
        
        $purchaseInvoice = array (            
            "cur_id"=> $request->cur_id  ,
            "cur_rate"=>$request->cur_rate   ,
            "trm_id"=> $trm_id  ,
            "pe_id"=> $request->peId  ,
            "vendor_reference"=>$request->vendor_reference,
           // "month"=>   ,
            "date"=>   $request->date,
           // "number"=>   ,
            "ven_coa_id"=> $request->vendor_ID,                                  
            "company_id"=>(request('trans_group') == 0) ? -1 : session('companyId'),
            "total_inv_amount"=>   $request->amount ,                                    
          //  "created_at"=>   ,            
            "note"=>  $request->description ,
            
        );  
        if($request->id)
        {
            DB::table('fs_purchase_invoices')->where("id", "=", $request->id)->update($purchaseInvoice);
        }
        else{
            $id = str::uuid()->toString();
            $purchaseInvoice['id'] =  $id;
            DB::table('fs_purchase_invoices')->insert($purchaseInvoice);
        }
        ///////////////////////////////// End of Purchase ///////////////////////////////////////////
        return redirect()->route('PurchaseExpenses',['id'=>$request->peId]);
         
     }

     public function store($request, $trm_id)
    {
        $companyId = session('companyId');
        $userId = Auth::id();
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('fs_purchase_invoices');
        //dd($number);
        $id = str::uuid()->toString();
        $purchase_invoice = array(
            "id" => $id,
            "trm_id" => $trm_id,
            "month" => $month,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->cur_rate,
            "po_id" => $request->pur_ord,
            "vendor_reference"=>$request->vendor_reference,
            "number" => $number,
            "date" => $request->exp_date,
            "grn_id" => $request->grn_id,
            "ven_coa_id" => $request->supp_coa_id, // need to disscuss
            "warehouse_id" => $request->warehouse_id, // need to disscuss
            "approve_by" => $userId,
            "approved_date" => $request->date,
            "company_id" => $companyId,
            "total_inv_amount" => $request->total_net_amount, // need to disscuss
            "note" => $request->note,
            "created_at" => Carbon::now(),
        );
        DB::table('pur_purchase_expenses_detail')->insert($purchase_invoice);
        DB::table('inv_grns')->where('id', $request->grn_id)->update(['pur_inv_id' => $id]);
        return $id;
    }

     public function edit($id)
     {
        
         return view('purchase.purchase_order_detail_edit');
     }

     public function updateDetail($request)
     {
         $month = dbLib::getMonth($request->date);
         $purchase_expense = array(
            "pe_id" =>'',// need to disscuss
            "pe_po_id" =>$request->pur_ord,
            "pe_date" =>$request->exp_date,
            "pe_refrence" =>$request->vendor_ID,
            "pe_coa_id" =>$request->cur_id,
            "pe_description" =>$request->description,
            "pe_cur_id" => $request->cur_id,
            "pe_cur_rate" => $request->cur_rate,
            "pe_amount" => $request->amount,
            "pe_trm_id" =>'',// need to disscuss

            //  "cur_id" => $request->cur_id,
            //  "cur_rate" => $request->cur_rate,
            //  "month" => $month,
            //  "date" => $request->exp_date,
            //  "total_inv_amount" => $request->total_net_amount,
            //  "note" => $request->note,
            //  "updated_at" => Carbon::now()
         );
         DB::table('pur_purchase_expenses_detail')->where('id', $request->id)->update($purchase_expense);
     }
     
 
    public function applyCostsView( Request  $request)
    {

        $peId = $request->peId;
        $poNumbers =  DB::table('pur_purchase_expenses')->select('*')->where('id','=', $peId)->first();       
        $poNumber = $poNumbers->po_number;     
        $poId = $poNumbers->po_id;     
        $purchaseInvoices = DB::table('fs_purchase_invoices')
                            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_purchase_invoices.ven_coa_id')
                            ->leftJoin('sys_currencies', 'sys_currencies.id', '=', 'fs_purchase_invoices.cur_id')
                            ->select('fs_purchase_invoices.*', 'fs_coas.name', 'sys_currencies.name as cur_name')
                            ->where('pe_id','=', $peId)
                            ->get();
        
        $grnProductDetails =  purchaseLib::getGrnProducts($poId);       // Grn Product list All products of seleted po. 
        
        return view('purchase.purchase_costdeployment', compact('peId', 'poNumber', 'purchaseInvoices', 'grnProductDetails'));
    }

   public function closePurchaseOrder(Request  $request)
   {
       
       //Update Inventory Item Prices
       $purchaseExpense =  DB::table('pur_purchase_expenses')->select('*')->where('id','=', $request->peId)->first();    
       $purchaseExpense->po_id;
       $itemIds = $request->item_ID;
       // Get All GRN Ids against this PO
       $grnDatas = DB::table('inv_grns')->select('id')->where('po_id', '=', $purchaseExpense->po_id)->get();
       $grnIds = array();
       foreach($grnDatas as $grnData)
       {
           $grnIds[] = $grnData->id ;
       }
      
       // Update item prices in inv_inventories
       $i=0;
       $totalValues = 0;
       foreach($itemIds as $itemId)
       {
           $invData = array();
           if($request->qty[$i]>0)
           {
                $invData = array(
                    "rate" =>( $request->net_amount[$i]/$request->qty[$i]),
                );
                DB::table('inv_inventories')->where('prod_id','=', $itemId)->whereIn('source_id', $grnIds)->update($invData);
           }
           //DB::inv_inventories
           $totalValues +=  $request->net_amount[$i];
           $i++;
       }
       $updateAmount = array ("amount"=>DB::raw("rate*qty_in"));
       DB::table('inv_inventories')->whereIn('source_id', $grnIds)->update($updateAmount);
       ////////////////////////////////// End of Inventory Cost Deployment
       
       //
       ////Move Purchase Order to Inventory Transaction
       //************************************************* Start Financial Transaction ***********************************
        $transMains = array(
            "id" => $purchaseExpense->trm_id,
            "date" => Carbon::now(),
            "voucher_type" => 6,
            "note" => $request->note,
        );

        $trm_id = accountsLib::saveTransMain($transMains);
       
        //First Delete Previous transaction from table if eixst and then add new transaction.This is help full when we edit transaction.
        DB::table('fs_transdetails')->where('trm_id', $trm_id)->delete();
        
        //Stock Account
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => 20, //$request->vendor_ID,  // this value need to reset/automized by adding dropdwon on purchase expense page. 
            "cur_id" => 1,
            "cur_rate" => 1,
            "description" => "Purchase Order is completed and amount debited to stock",
            "debit" => $totalValues ,           
            "credit" => 0,
        );
        
        accountsLib::saveTransDetail($transDetails);
        
        //Purchase Order /LC will be credited here. 
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $purchaseExpense->coa_id,
            "cur_id" => 1,
            "cur_rate" =>  1,
            "description" => "Purchase Order is completed and amount debited to stock",
            "debit" => 0,            
            "credit" => $totalValues ,
        );
        accountsLib::saveTransDetail($transDetails);
       
        ////////////// Close Purchase Expenses and Purchase order //////////////////////
        $closePurExp = array(
            "trm_id" => $trm_id,
            "status" => "closed",
            "closed_by" => Auth::id(),
            "closed_at" => Carbon::now(),
            
        );
        DB::table('pur_purchase_expenses')->where('id', '=', $request->peId)->update($closePurExp);
        
        
        /////////////// Close Purchase Order /////////////////////
        $purchase_order = array (            
            "close_status"=>1,
       );
       DB::table('pur_purchase_orders')->where('id','=', $purchaseExpense->po_id)->update($purchase_order);
       
       return redirect()->route('PurchaseExpList');
   }

}
