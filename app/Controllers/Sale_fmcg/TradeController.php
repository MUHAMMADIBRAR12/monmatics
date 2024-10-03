<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
class TradeController extends Controller
{
    public function index()
    {
        $trade_list=DB::Table('sale_trade_offers')
                    ->leftjoin('inv_products','inv_products.id','=','sale_trade_offers.free_prod_id')
                    ->select('sale_trade_offers.*','inv_products.name as prod_name')
                    ->where('sale_trade_offers.company_id',session('companyId'))
                    ->groupBy('id')
                    ->orderBy('number','ASC')
                    ->get();
        return view('sale_fmcg.trade_list',compact('trade_list'));
    }

    public function form($id=null)
    {
        $customer_categories=DB::table('crm_categories')->get();
        if($id)
        {
            $trade=DB::table('sale_trade_offers')
                        ->leftjoin('crm_customers','crm_customers.coa_id','=','sale_trade_offers.cust_id')
                        ->leftjoin('inv_products','inv_products.id','=','sale_trade_offers.free_prod_id')
                        ->leftjoin('geo_locations','geo_locations.id','=','sale_trade_offers.town')
                        ->select('sale_trade_offers.*','crm_customers.name as cust_name','inv_products.name as prod_name','geo_locations.name as location_name')
                        ->where('sale_trade_offers.id',$id)
                        ->first();
            //dd($trade);
            $item_list=DB::table('sale_trade_offers')
                            ->join('inv_products','inv_products.id','=','sale_trade_offers.prod_id')
                            ->select('sale_trade_offers.prod_id','inv_products.name')
                            ->where('sale_trade_offers.id',$id)
                            ->get();
            
            $products=DB::table('inv_products')
                            ->select('inv_products.id','inv_products.name')
                            ->whereNotIn('inv_products.id', function ($query) use ($id) {
                                $query->select('prod_id')->from('sale_trade_offers')->where('sale_trade_offers.id',$id)->get();
                            })
                            ->where('inv_products.coa_id',72)
                            ->get();
            
            return view('sale_fmcg.trade',compact('customer_categories','trade','item_list','products'));
        }
        else
        {
            $products=DB::table('inv_products')->select('id','name')->where('coa_id',72)->get();
            return view('sale_fmcg.trade',compact('customer_categories','products'));
        }
    }

    public function save(Request $request)
    {
        // dd($request->input());
        if($request->id)
            DB::table('sale_trade_offers')->where('id',$request->id)->delete();
            

      
            
        $id = str::uuid()->toString();
        $companyId = session('companyId'); 
        $month = dbLib::getMonth(Carbon::today()->toDateString());
        $number=dbLib::getNumber('sale_trade_offers');
        $count = count($request->item_list);
        for($i=0;$i<$count;$i++)
        {
            $sale_trade = array(     
            "id"=>$id,
            "prod_id"=>$request->item_list[$i],
            "number"=>$number,
            "month"=>$month,
            "from_date"=>$request->from_date,
            "to_date"=>$request->to_date,
            "importance"=>$request->importance,
            "status"=>$request->status,
            "customer_category"=>$request->customer_category,
            "type"=>$request->type,
            "apply_on"=>$request->apply_on,
            "from_qty"=>$request->from,
            "to_qty"=>$request->to,
            "repeat_on"=>$request->repeat_on,
            "disc_percent"=>$request->discount,
            "disc_amount"=>$request->discount_amount,
            "cust_id"=>$request->customer_ID,
            "town"=>$request->location_ID,
            "free_prod_id"=>$request->free_prod_id,
            "free_qty"=>$request->free_qty,
            "note"=>$request->note,
            "company_id"=>$companyId,
            );
            DB::table('sale_trade_offers')->insert($sale_trade);
            
        }

        return redirect('Sales_Fmcg/TradeOffer/List');
    }
}
