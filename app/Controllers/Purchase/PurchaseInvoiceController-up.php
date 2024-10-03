<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\dbLib;
use App\Libraries\accountsLib;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $purchase_invoices = DB::table('fs_purchase_invoices')
            ->join('inv_grns', 'inv_grns.id', 'fs_purchase_invoices.grn_id')
            ->join('fs_transmains', 'fs_transmains.id', '=', 'fs_purchase_invoices.trm_id')
            ->select('fs_purchase_invoices.*', DB::raw("concat(inv_grns.month,'-',LPAD(inv_grns.number,4,0)) as grn_num"), 'fs_transmains.post_status')
            ->where('fs_purchase_invoices.company_id', session('companyId'))
            ->orderBy('fs_purchase_invoices.month')
            ->orderBy('fs_purchase_invoices.number')
            ->get();
        return view('purchase.purchase_invoice_list', compact('purchase_invoices'));
    }

    public function form($id = null)
    {

        if ($id) {
            $purchase_invoice = DB::table('fs_purchase_invoices')
                ->join('inv_grns', 'inv_grns.id', '=', 'fs_purchase_invoices.grn_id')
                ->join('sys_warehouses', 'sys_warehouses.id', '=', 'inv_grns.warehouse')
                ->join('pa_vendors', 'pa_vendors.id', '=', 'inv_grns.ven_id')
                ->join('pa_vendor_details as v_detail', 'v_detail.pa_ven_id', '=', 'pa_vendors.id')
                ->select(
                    'fs_purchase_invoices.*',
                    'inv_grns.date as grn_date',
                    DB::raw("concat(inv_grns.month,'-',LPAD(inv_grns.number,4,0)) as grn_num"),
                    'sys_warehouses.name as warehouse_name',
                    'pa_vendors.name as ven_name',
                    'pa_vendors.coa_id as ven_coa_id',
                    'v_detail.address',
                    'v_detail.phone'
                )
                ->where('fs_purchase_invoices.id', $id)
                ->first();
            $purchase_invoice_detail = DB::table('fs_purchase_invoice_details as detail')
                ->join('inv_products', 'inv_products.id', '=', 'detail.prod_id')
                ->select('detail.*', 'inv_products.name as prod_name', 'inv_products.coa_id as prod_coa_id')
                ->where('detail.pur_inv_id', $id)
                ->orderBy('display')
                ->get();
            $attachmentRecord = dbLib::getAttachment($id);
            return view('purchase.purchase_invoice', compact('purchase_invoice', 'purchase_invoice_detail', 'attachmentRecord', 'transDetail'));
        } else {
            $companyId = session('companyId');
            $grns = DB::table('inv_grns')
                ->select('id', DB::raw("concat(month,'-',LPAD(number,4,0)) as doc_number"))
                ->whereNull('pur_inv_id')
                ->whereNotNull('igp_id')
                ->whereNull('wip_coa_id')
                ->where('company_id', $companyId)
                ->orderBy('month')
                ->orderBy('number')
                ->get();
            return view('purchase.purchase_invoice', compact('grns'));
        }
    }

    public function grn_detail(Request $request)
    {
        $result = array();
        $grn = DB::table('inv_grns')
            ->join('sys_warehouses', 'sys_warehouses.id', '=', 'inv_grns.warehouse')
            ->join('pa_vendors', 'pa_vendors.id', '=', 'inv_grns.ven_id')
            ->join('pa_vendor_details as v_detail', 'v_detail.pa_ven_id', '=', 'pa_vendors.id')
            ->select(
                'inv_grns.date as grn_date',
                'inv_grns.warehouse',
                'sys_warehouses.name as warehouse_name',
                'pa_vendors.name as ven_name',
                'pa_vendors.coa_id',
                'v_detail.address',
                'v_detail.phone'
            )
            ->where('inv_grns.id', $request->grn_id)
            ->first();
        $grn_details = DB::table('inv_grns')
            ->join('inv_grn_details', 'inv_grn_details.grn_id', '=', 'inv_grns.id')
            ->join('inv_products', 'inv_products.id', '=', 'inv_grn_details.prod_id')
            ->select('inv_grn_details.*', 'inv_products.name as prod_name', 'inv_products.coa_id as prod_coa_id')
            ->where('inv_grn_details.grn_id', $request->grn_id)
            ->get();
        foreach ($grn_details as $detail) {
            $q = DB::table('pur_purchase_order_details')->select('rate')->where('po_id', $detail->po_id)->where('prod_id', $detail->prod_id)->first();
            $rate = isset($q) ? $q->rate : 1;
            $row = array(
                'header' => $grn,
                'grn_detail_id' => $detail->id,
                'prod_id' => $detail->prod_id,
                'prod_name' => $detail->prod_name,
                'prod_coa_id' => $detail->prod_coa_id,
                'unit' => $detail->unit,
                'qty' => $detail->qty_approved,
                'rate' => $rate,
                'amount' => $detail->qty_approved * $rate,
            );
            array_push($result, $row);
        }
        return $result;
    }

    public function save(Request $request)
    {
        //dd($request->all());
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

        //Debit Inventory
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $request->prod_coa_id,
            "description" => 'Purchase invoice',
            "debit" => $request->gross_amount,
            "credit" => 0,
        );
        accountsLib::saveTransDetail($transDetails);

        //debit purchase tax
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => DB::table('sys_taxes')->select('coa_id')->where('name', 'Purchase Tax')->first()->coa_id,
            "description" => 'purchase invoice',
            "debit" => $request->total_tax,
            "credit" => 0,
        );
        accountsLib::saveTransDetail($transDetails);

        //Debit purchase Discount
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => DB::table('sys_taxes')->select('coa_id')->where('name', 'Purchase Discount')->first()->coa_id,
            "description" => 'purchase invoice',
            "debit" => $request->total_discount,
            "credit" => 0,
        );
        accountsLib::saveTransDetail($transDetails);

        //credit vendor
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $request->supp_coa_id,
            "description" => 'purchase invoice',
            "debit" => 0,
            "credit" => $request->total_net_amount,
        );
        accountsLib::saveTransDetail($transDetails);
       
        // $id = ($request->id)?$request->id:NULL;


    

        //************************************************* End Financial Transaction ***********************************

        if ($request->id) {
            $pur_inv_Id = $request->id;
            $this->update($request);
        } else {
            $pur_inv_Id = $this->store($request, $trm_id);
        }
        //update purchase invoice
        $this->updatePurchaseInvoiceDetail($pur_inv_Id, $request);


        $i=0;
        $peId = Str::uuid()->toString();

        // delete from where pru_id = $pur_inv_Id;
        DB::table('pur_purchase_invoice_input')->where('pur_id', $pur_inv_Id)->delete();

        DB::table('pur_purchase_invoice_input')->insert([
            [
                "id" => $peId,
                'pur_id' => $pur_inv_Id,
                "coa_id"=>$request->name_ID[$i],
                'narration' => $request->description[$i],
                'amount' => $request->amount[$i],
                
            ],
        ]);

        if ($request->file)  // save  Attachment Record
            dbLib::uploadDocument($pur_inv_Id, $request->file);
        return redirect('Purchase/PurchaseInvoice/List');
    }

    private function store($request, $trm_id)
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
            "number" => $number,
            "date" => $request->date,
            "grn_id" => $request->grn_id,
            "ven_coa_id" => $request->supp_coa_id,
            "warehouse_id" => $request->warehouse_id,
            "approve_by" => $userId,
            "approved_date" => $request->date,
            "company_id" => $companyId,
            "total_inv_amount" => $request->total_net_amount,
            "note" => $request->note,
            "created_at" => Carbon::now(),
        );
        DB::table('fs_purchase_invoices')->insert($purchase_invoice);
        DB::table('inv_grns')->where('id', $request->grn_id)->update(['pur_inv_id' => $id]);
        return $id;
    }


    private function update($request)
    {
        $month = dbLib::getMonth($request->date);
        $purchase_invoice = array(
            "month" => $month,
            "date" => $request->date,
            "total_inv_amount" => $request->total_net_amount,
            "note" => $request->note,
            "updated_at" => Carbon::now()
        );
        DB::table('fs_purchase_invoices')->where('id', $request->id)->update($purchase_invoice);
    }

    private function updatePurchaseInvoiceDetail($pur_inv_Id, $request)
    {
        DB::table('fs_purchase_invoice_details')->where('pur_inv_id', '=', $pur_inv_Id)->delete();
        $count = count($request->item_ID);
        for ($i = 0; $i < $count; $i++) {
            $id = str::uuid()->toString();
            $purchaseInvoiceDetail = array(
                "id" => $id,
                "pur_inv_id" => $pur_inv_Id,
                "grn_detail_id" => $request->grn_detail_id[$i],
                "prod_id" => $request->item_ID[$i],
                "unit" => $request->unit[$i],
                "qty" => $request->qty[$i],
                "rate" => $request->rate[$i],
                "gross_amount" => $request->gross_amount[$i],
                "tax_percent" => $request->tax[$i],
                "tax_amount" => $request->tax_amount[$i],
                "disc_percent" => $request->discount[$i],
                "delivery_charges" => $request->delivery_charges[$i],
                "disc_amount" => $request->discount_amount[$i],
                "net_amount" => $request->net_amount[$i],
                "display" => $i + 1,
            );
            DB::table('fs_purchase_invoice_details')->insert($purchaseInvoiceDetail);
        }
    }
}
