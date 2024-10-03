<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;
use App\Libraries\appLib;
use Carbon\Carbon;
use App\Libraries\inventoryLib;
use App\Libraries\accountsLib;
use Illuminate\Support\Str;

class PurchaseReturnController extends Controller
{
    public function pur_inv_detail(Request $request)
    {
        $pur_inv=DB::table('fs_purchase_invoices as pur_inv')
                    ->join('pa_vendors','pa_vendors.coa_id','=','pur_inv.ven_coa_id')
                    ->join('pa_vendor_details as ven_detail','ven_detail.pa_ven_id','=','pa_vendors.id')
                    ->leftJoin('sys_warehouses','sys_warehouses.id','=','pur_inv.warehouse_id')
                    ->select('pur_inv.date','pur_inv.grn_id','pur_inv.ven_coa_id','pa_vendors.name as ven_name','ven_detail.address','ven_detail.phone','pur_inv.warehouse_id','sys_warehouses.name as warehouse_name')
                    ->where('pur_inv.id','=',$request->pur_inv)
                    ->first();
        
        $pur_inv_detail=DB::table('fs_purchase_invoice_details')
                            ->join('inv_products','inv_products.id','=','fs_purchase_invoice_details.prod_id')
                            ->select('fs_purchase_invoice_details.*','inv_products.name as prod_name','inv_products.coa_id as prod_coa_id')
                            ->where('fs_purchase_invoice_details.pur_inv_id','=',$request->pur_inv)
                            ->get();        
    
        $pur_inv_detail_arr=array();
        foreach($pur_inv_detail as $detail)
        {
            $data=array(
                'header'=>$pur_inv,
                'prod_id'=>$detail->prod_id,
                'prod_name'=>$detail->prod_name,
                'unit'=>$detail->unit,
                'qty_received'=>$detail->qty,
                'rate'=>$detail->rate,
                'gross_amount'=>$detail->gross_amount,
                'tax_percent'=>$detail->tax_percent,
                'tax_amount'=>$detail->tax_amount,
                'disc_percent'=>$detail->disc_percent,
                'disc_amount'=>$detail->disc_amount,
                'net_amount'=>$detail->net_amount,
                'prod_coa_id'=>$detail->prod_coa_id,
            );
            array_push($pur_inv_detail_arr,$data);
        } 

        return $pur_inv_detail_arr;
        
    } 

    public function pur_return_list()
    {
        $purchaseReturns=DB::table('fs_purchase_inv_returns')
                            ->join('sys_warehouses','sys_warehouses.id','=','fs_purchase_inv_returns.warehouse_id')
                            ->select('fs_purchase_inv_returns.*','sys_warehouses.name')
                            ->where('fs_purchase_inv_returns.company_id',session('companyId'))
                            ->orderBy('fs_purchase_inv_returns.month')
                            ->orderBy('fs_purchase_inv_returns.number')
                            ->get();
        return view('inventory.purchase_return_list',compact('purchaseReturns'));
    }

    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        $purchase_invoices=DB::table('fs_purchase_invoices')
                                ->join('fs_transmains','fs_transmains.id','=','fs_purchase_invoices.trm_id')
                                ->select('fs_purchase_invoices.id',DB::raw("concat(fs_purchase_invoices.month,'-',LPAD(fs_purchase_invoices.number,4,0)) as inv_num"))
                                ->whereNull('fs_transmains.post_status')
                                ->get();
        if($id)
        {

            $purchaseReturn=DB::table('fs_purchase_inv_returns')
                                ->join('fs_purchase_invoices as pur_inv','pur_inv.id','=','fs_purchase_inv_returns.pur_inv_id')
                                ->join('sys_warehouses','sys_warehouses.id','=','fs_purchase_inv_returns.warehouse_id')
                                ->join('pa_vendors','pa_vendors.coa_id','=','fs_purchase_inv_returns.ven_coa_id')
                                ->join('pa_vendor_details','pa_vendor_details.pa_ven_id','=','pa_vendors.id')
                                ->select('fs_purchase_inv_returns.*','pa_vendors.name as v_name','pa_vendor_details.address','pa_vendor_details.phone','pur_inv.grn_id','sys_warehouses.name as warehouse_name','pur_inv.date as pur_inv_date',DB::raw("concat(pur_inv.month,'-',LPAD(pur_inv.number,4,0)) as pur_inv_num"))
                                ->where('fs_purchase_inv_returns.id','=',$id)
                                ->first();
            //dd($purchaseReturn);
           $purchaseReturnDetails= DB::table('fs_purchase_inv_return_details')
                                        ->join('inv_inventories', function($join){
                                            $join->on('fs_purchase_inv_return_details.pur_inv_return_id', '=', 'inv_inventories.source_id');
                                            $join->on('fs_purchase_inv_return_details.prod_id', '=', 'inv_inventories.prod_id');
                                        })
                                    ->Join('inv_products', 'inv_products.id', '=', 'fs_purchase_inv_return_details.prod_id')
                                    ->select('fs_purchase_inv_return_details.*','inv_products.name','inv_products.coa_id as prod_coa_id','inv_inventories.qty_out')
                                    ->where('fs_purchase_inv_return_details.pur_inv_return_id','=',$id)
                                    ->get();
            $attachmentRecord=dbLib::getAttachment($id); 
           return view('inventory.purchase_return',compact('warehouses','purchaseReturn','purchaseReturnDetails','attachmentRecord','purchase_invoices'));
        }
        else
        {
            return view('inventory.purchase_return',compact('warehouses','purchase_invoices'));
        }
       
    }
    public function save(Request $request)
    {
        //dd($request->input());

        //************************************************* Start Financial Transaction ***********************************
        $transMains=array(
            "id"=>$request->trm_id,
            "date"=>$request->date,
            "voucher_type"=>7,
            "note"=>$request->note,
        );
       // dd($transMains);
        $trm_id=accountsLib::saveTransMain($transMains);
    
        //First Delete Previous transaction from table if eixst and then add new transaction.This is help full when we edit transaction.
        DB::table('fs_transdetails')->where('trm_id',$trm_id)->delete();

        //debit Discount
         $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>DB::table('sys_taxes')->select('coa_id')->where('name','Purchase Discount')->first()->coa_id,
            "description"=>'purchase invoice',
            "debit"=>$request->total_discount,
            "credit"=>0,
        );
        accountsLib::saveTransDetail($transDetails);

        //debit vendor
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->vendor_ID,
            "description"=>'purchase invoice',
            "debit"=>$request->total_net_amount,
            "credit"=>0,
        );
        accountsLib::saveTransDetail($transDetails);

        //Credit Inventory
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->prod_coa_id,
            "description"=>'Purchase invoice',
            "debit"=>0,
            "credit"=>$request->total_gross_amount,
        );
        accountsLib::saveTransDetail($transDetails);

        //Credit purchase tax
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>DB::table('sys_taxes')->select('coa_id')->where('name','Purchase Tax')->first()->coa_id,
            "description"=>'purchase invoice',
            "debit"=>0,
            "credit"=>$request->total_tax,
        );
        accountsLib::saveTransDetail($transDetails);
        

        //************************************************* End Financial Transaction ***********************************
        $month = dbLib::getMonth($request->date);
        $purchase_return = array (
            "date"=>$request->date,
            "month"=>$month,
            "note"=>$request->note,
        );

        if($request->id)
        {
            $pur_inv_return_id=$request->id;
            $purchase_return['updated_at']=Carbon::now();
            DB::table('fs_purchase_inv_returns')->where('id',$request->id)->update($purchase_return);
            
        }
        else
        {
           //Save purchase return  Record
           $pur_inv_return_id=str::uuid()->toString();
           $purchase_return['id']=$pur_inv_return_id;
           $purchase_return['trm_id']=$trm_id;
           $purchase_return['pur_inv_id']=$request->pur_inv;
           $purchase_return['ven_coa_id']=$request->vendor_ID;
           $purchase_return['warehouse_id']=$request->warehouse_ID;
           $purchase_return['company_id']= session('companyId');
           $purchase_return['user_id']= Auth::id();
           $purchase_return['number']= dbLib::getNumber('fs_purchase_inv_returns');;
           DB::table('fs_purchase_inv_returns')->insert($purchase_return);
        }
        //save and update Good Received Detail Record
        $this->updatePurchaseReturnDetail($pur_inv_return_id, $request);
        
        if($request->file)  // save and update Sale Return Attachment
            dbLib::uploadDocument($pur_inv_return_id,$request->file);

        // //Also update purchase order table.if purchase order is return it will not show to in dropdown in purchase return form.
        // DB::table('pur_purchase_orders')->where('id',$request->po_id)->update(['po_return_status'=>0]);
        return redirect('Inventory/PurchaseReturnList');
    }

    private function updatePurchaseReturnDetail($pur_inv_return_id, $request)
    {
        DB::table('fs_purchase_inv_return_details')->where('pur_inv_return_id','=',$pur_inv_return_id)->delete();
       
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $purchaseReturnDetail = array (     
              "id"=>$id,
              "pur_inv_return_id"=>$pur_inv_return_id,
              "prod_id"=>$request->item_ID[$i],
              "unit"=>$request->unit[$i],
              "qty_received"=>$request->qty_received[$i],
              "qty_return"=>$request->qty_return[$i],
              "rate"=>$request->rate[$i],
              "gross_amount"=>$request->gross_amount[$i],
              "tax_percent"=>$request->tax[$i],
              "tax_amount"=>$request->tax_amount[$i],
              "disc_percent"=>$request->discount[$i],
              "disc_amount"=>$request->disc_amount[$i],
              "net_amount"=>$request->net_amount[$i],
            );

            DB::table('fs_purchase_inv_return_details')->insert($purchaseReturnDetail);  

            if($request->id)
            {
                $old_qty=$request->qty_out[$i];
                DB::table('inv_inventories')
                ->where('source_id',$request->grn_id)
                ->where('prod_id',$request->item_ID[$i])
                ->update([
                    'qty_issue' => DB::raw('qty_issue -'.$old_qty),
                    'balance' => DB::raw( 'qty_in - qty_issue'),                    
                ]);   
            }
            //inventory data
            //first we get rate from inventory of particular grn_id
            //$rate=DB::table('inv_inventories')->select('rate')->where('source_id',$request->grn_id)->where('prod_id',$request->item_ID[$i])->first()->rate;
             // before adding purchase return record in inventory check if particular purchase return id has reocord then it delete and add new record
             DB::table('inv_inventories')->where('source_id',$pur_inv_return_id)->where('prod_id',$request->item_ID[$i])->delete();
            
            $add_inventory = array(
                "date"=>$request->date,
                "warehouse_id"=>$request->warehouse_ID,
                "source"=>'purchase_return',
                "source_id"=>$pur_inv_return_id,
                "source_detail_id"=>$id,
                "prod_id"=>$request->item_ID[$i],
                "qty_in"=>0,
                "qty_out"=>$request->qty_return[$i],
               // "unit"=>$request->unit[$i],
                "rate"=>$request->rate[$i],
                "amount"=>$request->rate[$i] * $request->qty_return[$i],
                "description"=>$request->note,
            ); 
            inventoryLib::addInventory($add_inventory);

            //now update that qty return into qty issue for particular grn
            //$rate=DB::table('inv_inventories')->where('source_id',$request->grn_id)->where('prod_id',$request->item_ID[$i])->update(['qty_issue'=>$request->qty_return[$i]]);
            DB::table('inv_inventories')
                ->where('source_id',$request->grn_id)
                ->where('prod_id',$request->item_ID[$i])
                ->update([
                    'qty_issue' =>$request->qty_return[$i],
                    'balance' => DB::raw( 'qty_in - qty_issue'),                    
                ]);                            
        }
    }
}
