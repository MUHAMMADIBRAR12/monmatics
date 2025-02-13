<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index()
    {
        $Inquiries = DB::table('sal_inquiery')
            ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cust_id')
            ->select('sal_inquiery.*', 'crm_customers.name')->orderBy('name')->get();
        //dd($Inquiries);
        return view('sale_fmcg.inquiry_list', compact('Inquiries'));
    }

    public function form($id = null)
    {
        $Inquiry = DB::table('sal_inquiery')
            ->leftJoin('crm_customers', 'crm_customers.id', '=', 'cust_id')
            ->select('sal_inquiery.*', 'crm_customers.name')
            ->where('sal_inquiery.id', '=', $id)
            ->orderBy('name')->first();
        $InquiryDetail = DB::table('sal_inquiry_details')
            ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
            ->where('inquiry_id', '=', $id)
            ->get();
        //dd($InquiryDetail);
        return view('sale_fmcg.inquiry', compact('Inquiry', 'InquiryDetail'));
    }

    public function save(Request $request)
    {
        //dd($request->input());

        if ($request->id) {
            $id =  $request->id;
        } else {
            $info = dbLib::getInfo();
            $month = dbLib::getMonth($request->date);
            $id = str::uuid()->toString();
            $inquiry =  array(
                "id" => $id,
                "cust_id" => $request->customer_ID,
                "date" => $request->date,
                "month" => $month,
                "number" => $this->getInquiryNo($month),
                "note" => $request->note,
                "company_id" => $info['companyId'],
                "created_by" => $info['userId'],
            );
            //dd($inquiry);
            DB::table('sal_inquiery')->insert($inquiry);
        }
        // // insert values in sal_inquiry_detail
        DB::table('sal_inquiry_details')->where('inquiry_id', '=', $id)->delete();
        $count = count($request->name_ID);
        for ($i = 0; $i < $count; $i++) {
            $inquiryDetail = array(
                "id" => str::uuid()->toString(),
                "inquiry_id" => $id,
                "prod_id" => $request->name_ID[$i],
                "qty" => $request->qty[$i],
                "instruction" => $request->insturction[$i],
                "required_by" => $request->required_by[$i],
            );
            DB::table('sal_inquiry_details')->insert($inquiryDetail);
        }
        session()->flash('message', 'Transaction saved successfully');
        return redirect()->route('Sales/Inquiry/List');
    }

    private function getInquiryNo($month)
    {
        $companyId = config('app_session.companyId');
        $data = DB::table('sal_inquiery')
            ->where('month', '=', $month)
            ->where('company_id', '=', $companyId)->max('number');
        if ($data)
            return $data + 1;
        else
            return "0001";
    }
}
