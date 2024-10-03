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

class purchaseLib
{
    
    public static function getPoNum($id)
    {
        $poNumbers =  DB::table('pur_purchase_expenses')->select('po_number')->where('id','=', $peId)->first();       
        return $poNumbers->po_number;      
    }
    
    public static function getGrnProducts($poId)
    {       
        
      //  , 'rate',  'inv_products.name as prod_name', 'inv_products.id as prod_id'
        
        $purchase_invoice_detail = DB::table('inv_grn_details')
            ->join('inv_products', 'inv_products.id', '=', 'inv_grn_details.prod_id')
            ->select(DB::raw("SUM(qty_approved) AS qty"), 'inv_grn_details.prod_id','inv_products.name as prod_name',  'rate', 'unit') 
            ->where('inv_grn_details.po_id', $poId)
                ->groupBy("inv_grn_details.prod_id")
                ->groupBy("prod_name")
                ->groupBy("rate")
                ->groupBy("unit")
            ->orderBy('display')
            ->get();
        return $purchase_invoice_detail;
        //dd($purchase_invoice_detail);
    }
}