<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index()
    {
        $Quotations = DB::table('sal_quotation')
            ->leftJoin('crm_customers', 'crm_customers.id', '=', 'cust_id')
            ->select('sal_quotation.*', 'crm_customers.name')
            ->orderBy('sal_quotation.month')
            ->orderBy('sal_quotation.number')
            ->get();

        return view('sale_fmcg.quotation_list', compact('Quotations'));
    }

    public function form($id = null)
    {
        $Quotation = DB::table('sal_quotation')
            ->leftJoin('crm_customers', 'crm_customers.id', '=', 'cust_id')
            ->select('sal_quotation.*', 'crm_customers.name')
            ->where('sal_quotation.id', '=', $id)
            ->orderBy('name')->first();
        $QuotationDetail = DB::table('sal_quotation_details')
            ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
            ->where('quot_id', '=', $id)
            ->get();
        // dd($QuotationDetail);
        $attachments = DB::table('sal_quotation_attachments')->where('quot_id', '=', $id)->get();
        $taxList = dbLib::getTaxes();

        //        print_r($attachments);
        //        die();
        return view('sale_fmcg.quotation', compact('taxList', 'Quotation', 'QuotationDetail', 'attachments'));
    }

    public function save(Request $request)
    {
        //dd($request->input());
        $info = dbLib::getInfo();
        $month = dbLib::getMonth($request->date);
        if ($request->id) {
            $id =  $request->id;
            $quotation =  array(
                "cust_id" => $request->customer_ID,
                "inquiry_id" => $request->inquiry_id,
                "date" => $request->date,
                "note" => $request->note,
                "updated_by" => $info['userId'],
            );
            DB::table('sal_quotation')->where('id', '=', $id)->update($quotation);
        } else {

            $id = str::uuid()->toString();
            $quotation =  array(
                "id" => $id,
                "cust_id" => $request->customer_ID,
                "inquiry_id" => $request->inquiry_id,
                "date" => $request->date,
                "month" => $month,
                //"number"=>$this->getQuotationNo($month) ,
                "note" => $request->note,
                "company_id" => $info['companyId'],
                "created_by" => $info['userId'],
            );
            DB::table('sal_quotation')->insert($quotation);
        }
        // // insert values in sal_quotation_detail
        DB::table('sal_quotation_details')->where('quot_id', '=', $id)->delete();
        $count = count($request->name_ID);

        for ($i = 0; $i < $count; $i++) {
            $quotationDetail = array(

                "quot_id" => $id,
                "prod_id" => $request->name_ID[$i],
                "qty" => $request->qty[$i],
                "rate" => $request->rate[$i],
                "amount" => $request->amount[$i],
                "tax_class" => $request->tax_class[$i],
                "tax_amount" => $request->tax_amount[$i],
                "total_amount" => $request->total_amount[$i],
                "instruction" => $request->insturction[$i],
                "required_by" => $request->required_by[$i],
            );
            DB::table('sal_quotation_details')->insert($quotationDetail);
        }

        $i = 1;
        if ($request->file) {
            foreach ($request->file as $fileData) {
                $fileName = time() . '.' . $fileData->getClientOriginalName(); //    $fileData->extension();
                $fileData->move(public_path('assets/attachments'), $fileName);

                $id = str::uuid()->toString();
                $fileData = array(
                    "quot_id" => $id,
                    "file" => $fileName,
                );
                DB::table('sal_quotation_attachments')->insert($fileData);
                $i++;
            }
        }

        session()->flash('message', 'Transaction saved successfully');
        return redirect()->route('Sales/Quotation/List');
    }

    /*private function getQuotationNo($month)
    {
        $companyId = config('app_session.companyId');
        $data = DB::table('sal_quotation')
                        ->where('month','=', $month)
                        ->where('company_id','=',$companyId)->max('number');
        if($data)
            return $data + 1;
        else
            return "0001";
    }*/
}
