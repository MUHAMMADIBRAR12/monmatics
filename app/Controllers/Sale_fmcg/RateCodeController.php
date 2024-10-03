<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\dbLib;
class RateCodeController extends Controller
{
    public function index()
    {
        $rate_code=DB::table('sale_rate_code_fmcg')
                   ->join('crm_categories','crm_categories.category','=','sale_rate_code_fmcg.customer_category')
                   ->select('sale_rate_code_fmcg.id',DB::raw("concat(sale_rate_code_fmcg.month,'-',LPAD(sale_rate_code_fmcg.number,4,0)) as rate_code_num"),'crm_categories.category')
                   ->groupBy('sale_rate_code_fmcg.id')
                   ->orderBy('sale_rate_code_fmcg.month')
                   ->orderBy('sale_rate_code_fmcg.number')
                   ->get();
        return view('sale_fmcg.rate_code_list',compact('rate_code'));
    }

    public function form($id=null)
    {
        $cust_categories=DB::table('crm_categories')->get();
        if($id)
        {
            $rate_code=DB::table('sale_rate_code_fmcg')->select('sale_rate_code_fmcg.id','sale_rate_code_fmcg.month','sale_rate_code_fmcg.number','sale_rate_code_fmcg.customer_category')
                        ->where('sale_rate_code_fmcg.id',$id)
                        ->first();
            $finishd_products=DB::table('inv_products')
                ->leftJoin('sale_rate_code_fmcg', function($join) use($id){
                    $join->on('inv_products.id', '=', 'sale_rate_code_fmcg.prod_id')
                                ->where('sale_rate_code_fmcg.id', '=',$id);
                    })
                ->leftJoin('crm_categories','crm_categories.category','=','sale_rate_code_fmcg.customer_category')
                ->select('sale_rate_code_fmcg.*','inv_products.name as prod_name','inv_products.report_order','inv_products.id as prod_id','crm_categories.category')
                ->where('type','FINISH GOODS')
                ->orderBy('inv_products.report_order')
                ->get();
            return view('sale_fmcg.rate_code',compact('cust_categories','finishd_products','rate_code','finishd_products'));
        }
        else
        {
            $finishd_products=DB::table('inv_products')->select('id as prod_id','name as prod_name','inv_products.report_order')->where('type','FINISH GOODS') ->orderBy('inv_products.report_order')->get();
            $number= DB::table('sale_rate_code_fmcg')->select('number')->orderBy('number','DESC')->first();
            $number=isset($number->number) ? $number->number : 0;
            $number +=1;
            return view('sale_fmcg.rate_code',compact('cust_categories','number','finishd_products'));
        }
        
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if($request->id)
        {
            DB::table('sale_rate_code_fmcg')->where('id',$request->id)->delete();
        }
            $id = str::uuid()->toString();
            $companyId = session('companyId');
            $month= dbLib::getMonth(Carbon::today()->toDateString());
            $count = count($request->item_ID);
            for($i=0;$i<$count;$i++)
            {
                $rateCode = array (     
                "id"=>$id,
                "number"=>$request->number,
                "month"=>$month,
                "company_id"=>$companyId,
                "customer_category"=>$request->category,
                "prod_id"=>$request->item_ID[$i],
                "base_rate"=>$request->base_rate[$i],
                "gst_tax"=>$request->gst[$i],
                "gst_tax_amount"=>$request->gst_amount[$i],
                "fed_tax"=>$request->fed[$i],
                "fed_tax_amount"=>$request->fed_amount[$i],
                "others_tax"=>$request->others[$i],
                "others_tax_amount"=>$request->others_amount[$i],
                "total_tax_amount"=>$request->total_tax_amount[$i],
                "amount_after_tax"=>$request->price_inclusive_tax[$i],
                "fix_margin"=>$request->fix_margin[$i],
                "fix_margin_amount"=>$request->fix_margin_amount[$i],
                "lu_margin"=>$request->lu[$i],
                "lu_margin_amount"=>$request->lu_amount[$i],
                "others_margin"=>$request->others_margin[$i],
                "others_margin_amount"=>$request->others_margin_amount[$i],
                "freight"=>$request->freight[$i],
                "freight_amount"=>$request->freight_amount[$i],
                "total_margins_amount"=>$request->total_margin[$i],
                "gross_rate"=>$request->gross_rate[$i],
                "advance_payment"=>$request->advance_payment[$i],
                );
                DB::table('sale_rate_code_fmcg')->insert($rateCode);            
            }
        return redirect('Sales_Fmcg/RateCode/List'); 
    }
}
