<?php

/* 
 * The app core rights are with Solutions Wave. 
 * For further help you can contact with info@solutionswave.com
 * All content of project are copyright with Solutions Wave.
 *  
 * This class contains DB related functions of entire app. 
 * However, logic of each module is located in their repsective models. 
 */

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
//use Illuminate\Support\DateFactory;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Libraries\dbLib;
use App\Libraries\appLib;

class tradeOfferLib
{
    public static function getOffers($req,$total_gross_amount=0,$total_discount=0,$total_net_amount=0)
    {
        //return $req;
         //get all offers where date and product id
         $offers=DB::table('sale_trade_offers')->where('prod_id',$req->item_id)->where('from_date','<=',$req->date)->where('to_date','>=',$req->date)->where('company_id',session('companyId'))->get();
         //get customer category
         $customer=DB::table('crm_customers as cust')
                            ->leftjoin('crm_customer_extends as cust_ext','cust_ext.cust_id','=','cust.id')
                            ->select('cust.category','cust.margin','cust_ext.discount','cust_ext.special_discount','cust_ext.adv_payment','cust_ext.cod')
                            ->where('cust.coa_id',$req->cust_id)
                            ->get()
                            ->first();
        
        $totalDiscounts=array();
        $date=$req->date;
        $qty=$req->qty;
        $rate=$req->rate;
       // $gross_amount = $qty * $rate;
        $gross_amount=$req->rate;
        $total_gross_amount +=$gross_amount;
        //$customerDiscounts =$customer->margin + $customer->discount + $customer->special_discount + $customer->adv_payment + $customer->cod;
        // These margins are in percentages thats why sum
        $customerDiscounts =$customer->margin + $customer->adv_payment + $customer->cod;
        
        //These dicousnts are in amount. and apply with qty.
        $SpecialDisounts = $customer->discount + $customer->special_discount ;
        $SpecialDisountsAmount = $SpecialDisounts * $qty;
        $data = array(
            'discount_amount' => 0,
            'discount' => 0,
            'free_sku' =>0,
            'free_qty' =>0);  
        $amount=0;
        $customerDiscountAmount=($gross_amount * $customerDiscounts) / 100;
        $totalCustomerDiscount =$customerDiscountAmount+$SpecialDisountsAmount ;
        $net_amount = $gross_amount-$totalCustomerDiscount;
        foreach($offers as $offer)
        {      
             // $qty : Qty of sale order
             // $amount: Amount of Sale order   
            $amount =   ($offer->apply_on=="gross_amount")?$gross_amount:$net_amount; 
            $value = ($offer->type=="Quantity")?$qty:$amount;
            //if(($offer->from_qty<=$value && $offer->to_qty>=$value) && ($req->cust_id==$offer->cust_id || $req->cust_location==$offer->town || $customer->category==$offer->customer_category))
            if(($offer->from_qty<=$value && $offer->to_qty>=$value) && ($req->cust_id==$offer->cust_id  || $customer->category==$offer->customer_category))
            {
                
               // return "naa" .$offer->type ."---" . $value; 
                //return  $customer->category.",".$offer->customer_category ."++++,".$offer->id;
               $data =tradeOfferLib::repeatOn($offer->from_qty, $offer->to_qty, $offer->repeat_on, $value, $offer->disc_percent, $offer->disc_amount, $offer->free_prod_id, $offer->free_qty);
               return $data;
               break;
            }
        }
        $trade_offer_amount = (($amount*$data['discount'])/100) + $data['discount_amount'];
        $finalDiscount = $trade_offer_amount+$totalCustomerDiscount;
        $total_discount+=$finalDiscount;
        $netAmount = $gross_amount-$finalDiscount;
        $total_net_amount += $netAmount;
        $discountDetails = array(
            "discount_amount"=>$trade_offer_amount,
            "net_amount"=>$netAmount,
            "sku"=>$data['free_sku'],
            "free_qty"=>$data['free_qty'],
            "margin"=> $total_gross_amount * $customer->margin /100,
            "adv_payment"=> $total_gross_amount * $customer->adv_payment /100,
            "cod"=> $total_gross_amount * $customer->cod /100,
            "special_discount"=>$qty * $customer->special_discount,
            "discount"=> $qty * $customer->discount,
            "total_discount"=>$total_discount,
            "total_net_amount"=> $total_net_amount, 
       );
       return  $discountDetails;
                                             
    }

    public static function repeatOn ($from, $to, $repeat, $total, $discount_percent, $discount_amount, $sku, $freeunit)
    {
        
        $add_discount = array ();    // this array will retrun at the end. \

        // Calculate repeat value 
       // $repeatValue = $total - $from;
        $repeatOffer = (int) ($total / $repeat);
        // return "abcf". $from.", t" . $to.", r" . $repeat.", to" . $total.", dp" . $discount_percent.", da" . $discount_amount.", sk" . $sku.",fe " . $freeunit;
        //if ($repeatValue>=$repeat)
        if ($repeatOffer>=1)
        {   
            
            if(isset($discount_percent))
            {
                $totalRepeatDisount = $repeatOffer * $discount_percent;
                $add_discount['discount'] = $totalRepeatDisount;
                $add_discount['discount_amount'] = 0;
                $add_discount['free_sku'] =0;
                $add_discount['free_qty'] =0;
            }
            elseif(isset($discount_amount))
            {
                $totalRepeatDisount = $repeatOffer * $discount_amount;
                $add_discount['discount_amount'] = $totalRepeatDisount;
                $add_discount['discount'] = 0;
                $add_discount['free_sku'] =0;
                $add_discount['free_qty'] =0;  
            }
            elseif(isset($sku))
            {
                $totalRepeatDisount = $repeatOffer * $freeunit;                
                $add_discount['free_sku'] =$sku;
                $add_discount['free_qty'] =$totalRepeatDisount; 
                $add_discount['discount_amount'] = 0;
                $add_discount['discount'] = 0;   
            }
    
        }
        else
        {
            $add_discount['discount_amount'] = 0;
            $add_discount['discount'] = 0; 
            $add_discount['free_sku'] ='';
            $add_discount['free_qty'] =0;   
        }
        return  $add_discount;

    }
}

    
