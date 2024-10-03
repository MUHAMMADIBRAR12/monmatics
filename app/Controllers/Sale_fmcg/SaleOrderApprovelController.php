<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
use App\Libraries\tradeOfferLib;
class SaleOrderApprovelController extends Controller
{
    //this function is called 2 times.first when we select sale order number it first get record of sale orders and sale order detail then 2nd time 
    //this function is called when it has customer coa_id and get rate code numbers of that customer category
    public function so_detail(Request $request)
    {
        
        if($request->coa_id)
        {
            $coa_id=$request->coa_id;
            $result=DB::table('sale_rate_code_fmcg as rate_code')
            ->join('crm_customers',function($join) use ($coa_id){
                $join->on('crm_customers.category', '=','rate_code.customer_category');
                $join->on('crm_customers.coa_id','=',DB::raw("'".$coa_id."'"));
            })
            ->select('rate_code.id',DB::raw("concat(rate_code.month,'-',LPAD(rate_code.number,4,0)) as rate_num"))
            ->groupBy('rate_code.id')
            ->orderBy('number', 'DESC')
            ->get();
            return response()->json($result);
        }
        $sale_order=DB::table('sale_orders')
                        ->leftJoin('sys_attachments','sys_attachments.source_id','=','sale_orders.id')
                        ->join('sale_order_details as so_detail','so_detail.so_id','=','sale_orders.id')
                        ->join('inv_products','inv_products.id','=','so_detail.prod_id')
                        ->join('sale_rate_code_fmcg',function($join){
                            $join->on('sale_rate_code_fmcg.id','=','so_detail.rate_code_id');
                            $join->on('sale_rate_code_fmcg.prod_id', '=', 'so_detail.prod_id');
                        })
                        ->join('users','users.id','=','sale_orders.created_by')
                        ->leftJoin('crm_customers','crm_customers.coa_id','=','sale_orders.cust_id')
                        ->leftJoin('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                        ->leftjoin('crm_customer_extends','crm_customer_extends.cust_id','=','crm_customers.id')
                        ->select('so_detail.*','sale_orders.remarks','sale_orders.date','sale_orders.cust_balance','sale_orders.cust_id',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name',
                        'crm_customers.credit_limit','crm_customers.credit_amount','cust_add.address','cust_add.location','crm_customer_extends.stn','inv_products.name as prod_name','sys_attachments.file','sale_rate_code_fmcg.id as rate_code_id')
                        ->where('sale_orders.id',$request->so_id)
                        //->groupBy('sale_rate_code_fmcg.id')
                        ->orderBy('so_detail.display')
                        ->get();
        return $sale_order;
    }

    public function getApprovelOffer(Request $request)
    {
        $data=tradeOfferLib::getOffers($request);
        return $data;
    }

    public function getCustomerDiscount(Request $request)
    {
        $discount=DB::table('sale_customer_discount_fmcg')->where('cust_id',$request->coa_id)->where('date_from','<=',$request->date)->where('date_to','>=',$request->date)->first();
        $result = $discount ? $discount :0 ;
        return response()->json($result );
    }

    public function index()
    {
        $sale_order_approvels=DB::table('sale_order_approvel')
                                ->join('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                                ->join('crm_customers','crm_customers.coa_id','=','sale_order_approvel.cust_id')
                                ->select('sale_order_approvel.id','sale_order_approvel.editable','sale_order_approvel.month','sale_order_approvel.number','sale_order_approvel.date','sale_order_approvel.note','crm_customers.name as cust_name',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"))
                                ->where('sale_order_approvel.company_id',session('companyId'))
                                ->orderBy('sale_order_approvel.month')
                                ->orderBy('sale_order_approvel.number')
                                ->get();
        return view('sale_fmcg.sale_order_approvel_list',compact('sale_order_approvels'));
    }

    public function form($id=null)
    {
        $whereDateCluse = array();
        array_push($whereDateCluse,array ('sale_orders.editable', '=',1));
        $sale_orders=dbLib::getDocumentNumbers('sale_orders',$whereDateCluse);
        //dd($sale_orders);
        if($id)
        {
            $so_approvel=DB::table('sale_order_approvel as so_ap')
                        ->join('sale_orders','sale_orders.id','=','so_ap.so_id')
                        ->join('users','users.id','=','sale_orders.created_by')
                        ->join('crm_customers as cust','cust.coa_id','=','so_ap.cust_id')
                        ->join('crm_customers_address as cust_add','cust_add.cust_id','=','cust.id')
                        ->select('so_ap.*',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'cust.name as cust_name','cust_add.address as cust_address','cust_add.location as cust_town','sale_orders.date as so_date','sale_orders.month as so_month','sale_orders.number as so_number')
                        ->where('so_ap.id',$id)
                        ->get()
                        ->first();
            $coa_id=$so_approvel->cust_id;
            $rate_codes=DB::table('sale_rate_code_fmcg as rate_code')
                            ->join('crm_customers',function($join) use ($coa_id){
                                $join->on('crm_customers.category', '=','rate_code.customer_category');
                                $join->on('crm_customers.coa_id','=',DB::raw("'".$coa_id."'"));
                            })
                            ->select('rate_code.id',DB::raw("concat(rate_code.month,'-',LPAD(rate_code.number,4,0)) as rate_num"))
                            ->groupBy('rate_code.id')
                            ->orderBy('number', 'DESC')
                            ->get();
            $so_detail_approvel=DB::table('sale_order_detail_approvel as so_detail_ap')
                               ->join('sale_order_approvel','sale_order_approvel.id','=','so_detail_ap.soa_id')
                               ->join('inv_products','inv_products.id','=','so_detail_ap.prod_id')
                               ->select('so_detail_ap.*','inv_products.name as prod_name')
                               ->where('so_detail_ap.soa_id',$id)
                               ->orderBy('so_detail_ap.display')
                               ->get();
            $attachmentRecord=dbLib::getAttachment($id);
            //dd($attachmentRecord);
            return view('sale_fmcg.sale_order_approvel',compact('sale_orders','so_approvel','so_detail_approvel','rate_codes','attachmentRecord'));
        }
        else
        {
            return view('sale_fmcg.sale_order_approvel',compact('sale_orders'));
        }
       
    }

    public function getRateCode(Request $request)
    {
        $result=DB::table('sale_rate_code_fmcg')->where('id',$request->code_id)->where('prod_id',$request->prod_id)->first();
        return response()->json($result);
    }

    public function save(Request $request)
    {
        //dd($request->file);
        if($request->id)
        {
            $soa_id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save Purchase Requestion Record
            $soa_id= $this->store($request);
            DB::table('sale_orders')->where('id',$request->sale_order_no)->update(['editable'=>0]);
        }

        //save and update sale order detail
        $this->updateSaleOrderApprovelDetail($soa_id, $request);
        if($request->file)  // save and update sale order approvel Attachment Record
            dbLib::uploadDocument($soa_id,$request->file);
            
        return redirect('Sales_Fmcg/SaleOrderApprovel/List');
    }

    private function store($request)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('sale_order_approvel');
        $id = str::uuid()->toString();
        $sale_order_approvel = array (
            "id"=>$id ,
            "so_id"=>$request->sale_order_no,
            "month"=>$month,
            "number"=>$number,
            "date"=>$request->date,
            "cust_id"=>$request->customer_ID,
            "cust_balance"=>$request->customer_balance,
            "credit_days"=>$request->credit_days,
            "credit_amount"=>$request->credit_amount,
           // "avg_recover_date"=>$request->trade_offer,
            "note"=>$request->note,
            "company_id"=>$companyId,
            "user_id"=>$userId,
            "user_name"=>$userId,
            "created_at"=>Carbon::now(),

        ); 
        DB::table('sale_order_approvel')->insert($sale_order_approvel);
        return $id;
    }

    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $sale_order_approvel = array (
            "id"=>$id ,
            "month"=>$month,
            "date"=>$request->date,
           // "avg_recover_date"=>$request->trade_offer,
            "note"=>$request->note,
            "company_id"=>$companyId,
            "user_id"=>$userId,
            "user_name"=>$userId,
            "updated_at"=>Carbon::now(),

        ); 
        DB::table('sale_order_approvel')->where('id', '=',$id)->update($sale_order_approvel);
    }

    private function updateSaleOrderApprovelDetail($soa_id, $request)
    {
        DB::table('sale_order_detail_approvel')->where('soa_id','=',$soa_id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $sale_order_detail_ap = array (     
                "id"=>$id,
                "soa_id"=>$soa_id,
                "prod_id"=>$request->item_ID[$i],
                "unit"=>$request->unit[$i],
                "qty_stock"=>$request->qty_stock[$i],
                "qty_ordered"=>$request->qty_ordered[$i],
                "qty_approved"=>$request->qty_approved[$i],
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
            DB::table('sale_order_detail_approvel')->insert($sale_order_detail_ap);  
        }
    }

   

}
