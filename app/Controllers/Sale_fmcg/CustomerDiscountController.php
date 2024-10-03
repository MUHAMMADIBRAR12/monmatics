<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\dbLib;
use DB;
class CustomerDiscountController extends Controller
{
    public function index()
    {
        $discount_list=DB::table('sale_customer_discount_fmcg')
                        ->join('crm_customers','crm_customers.coa_id','=','sale_customer_discount_fmcg.cust_id')
                        ->select('sale_customer_discount_fmcg.*','crm_customers.name as cust_name')
                        ->orderBy('sale_customer_discount_fmcg.month')
                        ->orderBy('sale_customer_discount_fmcg.number')
                        ->get();
        return view('sale_fmcg.customer_discount_list',compact('discount_list'));
    }

    public function form($id=null)
    {
        if($id)
        {
            $discount=DB::table('sale_customer_discount_fmcg')
                        ->join('crm_customers','crm_customers.coa_id','=','sale_customer_discount_fmcg.cust_id')
                        ->select('sale_customer_discount_fmcg.*','crm_customers.name as cust_name')
                        ->where('sale_customer_discount_fmcg.id',$id)
                        ->first();    
            return view('sale_fmcg.customer_discount',compact('discount'));
        }
        else
        {
            return view('sale_fmcg.customer_discount');
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());
        $cust_discount=array(
            "cust_id"=>$request->customer_ID,
            "date_from"=>$request->date_from,
            "date_to"=>$request->date_to,
            "qty_discount"=>$request->discount_per_qty,
            "amount_discount_percent"=>$request->discount_gross_amount,
        );

        if($request->id)
        {
            DB::table('sale_customer_discount_fmcg')->where('id',$request->id)->update($cust_discount);
        }
        else
        {
            $cust_discount['id']= str::uuid()->toString();
            $cust_discount['month'] = dbLib::getMonth(Carbon::today()->toDateString());
            $cust_discount['company_id']= session('companyId');
            DB::table('sale_customer_discount_fmcg')->insert($cust_discount);
        }
        
        return redirect('Sales_Fmcg/CustomerDiscount/List');
    }
}
