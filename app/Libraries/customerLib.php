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

class customerLib
{
    public static function  customerDetail($coa_id)
    {
        return  DB::table('crm_customers')
                    ->leftjoin('crm_customers_address as cust_add','cust_add.cust_id','crm_customers.id')
                    ->leftjoin('crm_customer_extends as cust_ext','cust_ext.cust_id','crm_customers.id')                    
                    ->select('crm_customers.*', 'cust_add.*', 'cust_ext.*')
                    ->where('crm_customers.coa_id',$coa_id)
                    ->first();
    }

    public static function customerAccountDetail($coa_id,$post_status=null)
    {
        $whereDateCluse = array();
        if($post_status)
        {
            $arrFromDate = array ('fs_transmains.post_status','=',$post_status);
            array_push($whereDateCluse, $arrFromDate);
        }
       
        $companyId = session('companyId');
        $balance=DB::table('fs_transmains')
          ->join('fs_transdetails', function($join) use($coa_id){
            $join->on('fs_transdetails.trm_id', '=', 'fs_transmains.id')
                    ->where('fs_transdetails.coa_id', '=',$coa_id);
            })
           ->join('crm_customers','crm_customers.coa_id','=','fs_transdetails.coa_id')
           ->select(DB::raw("(SUM(fs_transdetails.debit) - sum(fs_transdetails.credit)) as balance"))
           ->where('fs_transmains.company_id','=',$companyId)
           ->where('fs_transmains.post_status','=','posted')
           ->where($whereDateCluse)
           ->get();
        if(isset($balance[0]->balance))
           return $balance[0]->balance;
        else
            return 0;
    } 
    
}