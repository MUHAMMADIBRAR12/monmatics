<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
use App\Libraries\inventoryLib;
use App\Libraries\tradeOfferLib;
use App\Libraries\customerLib;
class SaleOrderController extends Controller
{
    public function index()
    {
        //,'sale_order_details.qty_ordered','sale_order_details.trade_offer','sale_order_details.discount','sale_order_details.amount_after_discount'
        $result=DB::table('sale_orders')
                    ->join('crm_customers','crm_customers.coa_id','=','sale_orders.cust_id')
                    ->select('sale_orders.*','crm_customers.name as cust_name')
                    ->where('sale_orders.company_id',session('companyId'))
                    ->orderBy('month')
                    ->orderBy('number')
                    ->get();
        $sale_orders=array();
        foreach($result as $data)
        {
            $so_detail=DB::table('sale_order_details')
                            ->select(DB::raw("SUM(sale_order_details.qty_ordered) as total_qty"),
                            DB::raw("SUM(sale_order_details.amount_after_discount) as total_gross_amount"),
                            DB::raw("SUM(sale_order_details.trade_offer) as total_trade_offer"),
                            DB::raw("SUM(sale_order_details.discount) as total_discount"),
                            DB::raw("SUM(sale_order_details.amount_after_discount) as total_net_amount"))
                            ->where('so_id','=',$data->id)
                            ->first();
            $row=array(
                'id'=>$data->id,
                'editable'=>$data->editable,
                'month'=>$data->month,
                'number'=>$data->number,
                'date'=>$data->date,
                'cust_name'=>$data->cust_name,
                'remarks'=>$data->remarks,
                'total_qty'=>$so_detail->total_qty,
                'total_gross_amount'=>$so_detail->total_gross_amount,
                'total_trade_offer'=>$so_detail->total_trade_offer,
                'total_discount'=>$so_detail->total_discount,
                'total_net_amount'=>$so_detail->total_net_amount,
            );
            array_push($sale_orders,$row);
        }
        return view('sale_fmcg.sales_order_list',compact('sale_orders'));
    }

    public function form($id=null)
    {
        //$rate_codes=DB::table('sale_rate_code_fmcg')->select('id',DB::raw("concat(month,'-',LPAD(number,4,0)) as code"))->groupBY('id')->where('company_id',session('companyId'))->get();
        $rate_codes=DB::table('sale_rate_code_fmcg')->select('id',DB::raw("concat(month,'-',LPAD(number,4,0)) as code"))->where('company_id',session('companyId'))->get();
        if($id)
        {
            $sale_order=DB::table('sale_orders')
                        ->join('crm_customers as cust','cust.coa_id','=','sale_orders.cust_id')
                        ->join('crm_customers_address as cust_add','cust_add.cust_id','=','cust.id')
                        ->select('sale_orders.*','cust.name as cust_name','cust_add.phone as cust_phone','cust_add.address as cust_address','cust_add.location as cust_town')
                        ->where('sale_orders.id',$id)
                        ->get()
                        ->first();
            $sale_order_detail=DB::table('sale_order_details as so_detail')
                               ->join('sale_orders','sale_orders.id','=','so_detail.so_id')
                               ->join('inv_products','inv_products.id','=','so_detail.prod_id')
                               ->select('so_detail.*','inv_products.name as prod_name')
                               ->where('so_detail.so_id',$id)
                               ->orderBy('display')
                               ->get();
            $attachmentRecord=dbLib::getAttachment($id);
            
            return view('sale_fmcg.sales_order',compact('sale_order','sale_order_detail','rate_codes','attachmentRecord'));
        }
        else
        {
            return view('sale_fmcg.sales_order',compact('rate_codes'));
        }
    }

    public function view($id)
    {
        $sale_order=DB::table('sale_orders')
                        ->join('crm_customers as cust','cust.coa_id','=','sale_orders.cust_id')
                        ->join('crm_customers_address as cust_add','cust_add.cust_id','=','cust.id')
                        ->select('sale_orders.*','cust.name as cust_name','cust_add.phone as cust_phone','cust_add.address as cust_address','cust_add.location as cust_town')
                        ->where('sale_orders.id',$id)
                        ->get()
                        ->first();
        //dd($sale_order);
        $soDetails=DB::table('sale_order_details as so_detail')
                    ->join('sale_orders','sale_orders.id','=','so_detail.so_id')
                    ->join('inv_products','inv_products.id','=','so_detail.prod_id')
                    ->select('so_detail.*','inv_products.name as prod_name')
                    ->where('so_detail.so_id',$id)
                    ->orderBy('display')
                    ->get();
        $attachmentRecord=dbLib::getAttachment($id);
        return view('sale_fmcg.sales_order_view',compact('sale_order','soDetails','attachmentRecord'));
    }


    // This function is to calculate disount on "Repeat" of trade offer. 
    // $from = From value of trade offer
    // $repeat = Repetat value of trade offer
    // $total: total qty/amount from Sale order (if repeat is set on qty then total will be 
    // qty and if repeat set on amount total will be amount)
    // $disocunt_percent = discount percentage of trade offer, if offer is set as percentage.
    // $discount_amount = discount amount of trader offer, if offer is set as amount.
    // $sku = Free sku unit in trade offer\
    // $freeunit = Free sku qty in trade offer.
    
    public function getOffers(Request $request)
    {
    
        $data= tradeOfferLib::getOffers($request);
        return $data;
                                                     
    }

    public function getCustomerBalance(Request $request)
    {
        $balance= customerLib::customerAccountDetail($request->coa_id);
        return response()->json($balance);
    }

    public function getCustomerDiscount(Request $request)
    {
        $discount=DB::table('sale_customer_discount_fmcg')->where('cust_id',$request->coa_id)->where('date_from','<=',$request->date)->where('date_to','>=',$request->date)->first();
        $result = $discount ? $discount :0 ;
        return response()->json($result );
    }

    public function getRateCode(Request $request)
    {
        if($request->code_id)
        {
            $result=DB::table('sale_rate_code_fmcg')->where('id',$request->code_id)->where('prod_id',$request->prod_id)->first();
            return response()->json($result);
        }
        $coa_id=$request->coa_id;
        $result=DB::table('sale_rate_code_fmcg as rate_code')
            ->join('crm_customers',function($join) use ($coa_id){
                $join->on('crm_customers.category', '=','rate_code.customer_category');
                $join->on('crm_customers.coa_id','=',DB::raw("'".$coa_id."'"));
            })
            ->select('rate_code.*',DB::raw("concat(rate_code.month,'-',LPAD(rate_code.number,4,0)) as rate_num"))
            ->where('rate_code.prod_id',$request->prod_id)
            ->orderBy('number', 'DESC')
            ->get();

        return response()->json($result);
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if($request->id)
        {
            $so_id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save Purchase Requestion Record
            $so_id= $this->store($request);
        }

        //save and update sale order detail
        $this->updateSaleOrderDetail($so_id, $request);

        if($request->file)  // save and update sale order Attachment Record
            dbLib::uploadDocument($so_id,$request->file);

        return redirect('Sales_Fmcg/SaleOrder/List');
    }

    private function store($request)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('sale_orders');
        $id = str::uuid()->toString();
        $sale_order = array (
            "id"=>$id ,
            "month"=>$month ,
            "number"=>$number ,
            "date"=>$request->date,
            "cust_id"=>$request->customer_ID,
            "cust_balance"=>$request->cust_balance,
            "remarks"=>$request->remarks,
            "company_id"=>$companyId,
            "created_by"=>$userId,
            "created_at"=>Carbon::now(),

        ); 
        DB::table('sale_orders')->insert($sale_order);
        return $id;
    }

    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $sale_order = array (
            "id"=>$id ,
            "month"=>$month ,
            "date"=>$request->date,
            "cust_id"=>$request->customer_ID,
            "cust_balance"=>$request->cust_balance,
            "remarks"=>$request->remarks,
            "company_id"=>$companyId,
            "updated_by"=>$userId,
            "updated_at"=>Carbon::now(),
        );
        DB::table('sale_orders')->where('id', '=',$id)->update($sale_order);
    }

    private function updateSaleOrderDetail($so_id, $request)
    {
        DB::table('sale_order_details')->where('so_id','=',$so_id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            if($request->qty_ordered[$i] > 0)
            {
                $id = str::uuid()->toString();
                $sale_order_detail = array (     
                    "id"=>$id,
                    "so_id"=>$so_id,
                    "prod_id"=>$request->item_ID[$i],
                    "unit"=>$request->unit[$i],
                    "qty_stock"=>$request->qty_stock[$i],
                    "qty_ordered"=>$request->qty_ordered[$i],
                    "rate_code_id"=>$request->rate_code_id[$i],
                    "base_rate"=>$request->base_rate[$i],
                    "sales_tax"=>$request->sales_tax[$i],
                    "fed"=>$request->fed[$i],
                    "further_tax"=>$request->further_tax[$i],
                    "inclusive_value"=>$request->inclusive_value[$i],
                    "lu"=>$request->lu[$i],
                    "freight"=>$request->freight[$i],
                    "other_discount"=>$request->other_discount[$i],
                    "rate"=>$request->rate[$i],
                    "fixed_margin"=>$request->fixed_margin[$i],
                    "amount_before_discount"=>$request->amount_before_discount[$i],
                    "trade_offer"=>$request->trade_offer[$i],
                    "discount"=>$request->discount[$i],
                    "amount_after_discount"=>$request->amount_after_discount[$i],
                    "adv_payment"=>$request->adv_payment[$i],
                    "display"=>$i+1,
                );
                DB::table('sale_order_details')->insert($sale_order_detail);  
            }
        }
    }

}
