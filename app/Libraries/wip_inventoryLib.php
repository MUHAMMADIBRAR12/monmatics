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

class wip_inventoryLib
{
    public static function add_wip_inventory($data)
    {
        $data['id']= str::uuid()->toString();
        $data['company_id']=session('companyId');
        DB::table('wip_inventories')->insert($data);
       
    }

    public static function get_wip_inventory_balance($prod_id)
    {
        $balance=DB::table('wip_inventories')->select(DB::raw("(SUM(qty_in) - sum(qty_out)) as balance"))->where('prod_id',$prod_id)->where('company_id',session('companyId'))->get();
        return $balance[0]->balance;
    }

    public static function get_wip_inventory_rate($prod_id)
    {
        $result=DB::table('wip_inventories')->select('rate')->where('prod_id',$prod_id)->where('qty_in','>','0')->orderBy('date','DESC')->first();
        return isset($result->rate) ? $result->rate : '';
    }

}

    
