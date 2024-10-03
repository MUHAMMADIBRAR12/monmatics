<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\inventoryLib;
use App\Libraries\customerLib;

class AppController extends BaseController
{



    function getCurrencyRate(Request $request)
    {
        $code = $request->code;
        $result = DB::table('sys_currencies')->select(array('rate'))->where('code', '=', $code)->first();
        return $result->rate;
    }

    function getTaxRates(Request $request)
    {
        $taxName = $request->taxName;
        $result = DB::table('sys_taxes')->select(array('rate'))->where('name', '=', $taxName)->first();
        return $result->rate;
    }

    public function coaSearch(Request $request)
    {
        $searchStr = $request->name;
        $result = DB::table('fs_coas')->select(array('id', 'name'))->where('trans_group', '=', 1)->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }

        return response()->json($response);
    }
    public function productSearch(Request $request)
    {
        $searchStr = $request->name;
        $result = DB::table('inv_products')->select(array('id', 'name'))->where('prod_services', '=', 'product')->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }

        return response()->json($response);
    }


    public function customerSearch(Request $request)
    {

        $searchStr = $request->name;
        //$restult = DB::table('crm_customers')->select()->where('name', 'like', '%'.$searchStr.'%')->orderby('name')->get();
        $result = DB::table('crm_customers')->select(array('coa_id', 'name'))->where('lead', 0)->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->coa_id, "label" => $record->name);
        }

        return response()->json($response);
    }


    public function leadSearch(Request $request)
    {

        $searchStr = $request->name;
        //$restult = DB::table('crm_customers')->select()->where('name', 'like', '%'.$searchStr.'%')->orderby('name')->get();
        $result = DB::table('crm_customers')->select(array('id', 'name'))->where('lead', 1)->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }

    public function leadCstSearch(Request $request)
    {

        $searchStr = $request->name;
        //$restult = DB::table('crm_customers')->select()->where('name', 'like', '%'.$searchStr.'%')->orderby('name')->get();
        $result = DB::table('crm_customers')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }

    public function projectSearch(Request $request)
    {
        $companyId = session('companyId');
        $searchStr = $request->name;
        $result = DB::table('prj_projects')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->where('company_id', $companyId)->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }

        return response()->json($response);
    }

    public function itemSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('inv_products')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }
    public function rawItemSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('inv_products')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->where('coa_id', 21)->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }
    public function SaleItemSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('inv_products')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->where('coa_id', 72)->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }
    public function vendorSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('pa_vendors')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();

        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }

    public function vendorCoaSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('pa_vendors')->select(array('coa_id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();

        foreach ($result as $record) {
            $response[] = array("value" => $record->coa_id, "label" => $record->name);
        }
        return response()->json($response);
    }

    public function getItemDetail(Request $request)
    {

        $stock = inventoryLib::getStock($request->id, $request->warehouseId)->qty;
        $id = $request->id;
        $result = DB::table('inv_products')
            ->leftjoin('sys_units as u1', 'u1.id', '=', 'inv_products.primary_unit')
            ->leftjoin('sys_units as u2', 'u2.id', '=', 'inv_products.purchase_unit')
            ->leftjoin('sys_units as u3', 'u3.id', '=', 'inv_products.sale_unit')
            ->select('inv_products.*', 'u1.name as primary_unit', 'u2.name as purchase_unit', 'u2.operator_value', 'u3.name as sale_unit', 'u3.operator_value as sale_opt_val')
            ->where('inv_products.id', '=', $id)
            ->first();
        $response = array(
            "name" => $result->name,
            'coa_id' => $result->coa_id,
            "sku" => $result->sku,
            "unit" => $result->primary_unit,
            "category" => $result->category,
            "purchase_price" => $result->purchase_price,
            "sale_price" => $result->sale_price,
            "reorder" => $result->reorder,
            "qty_in_stock" => $stock,
            "primary_unit" => $result->primary_unit,
            "purchase_unit" => $result->purchase_unit,
            "sales_unit" => $result->sale_unit,
            "description" => $result->description,
            "operator_value" => $result->operator_value,
            "sale_opt_val" => $result->sale_opt_val,
            "packing_detail" => $result->packing_detail,
        );
        return response()->json($response);
    }

    public function getInquiries(Request $request)
    {

        $customerId = $request->id;
        $results = DB::table('sal_inquiry')->select()->where('cust_id', '=', $customerId)
            ->whereNotIn(
                'id',
                function ($query) use ($customerId) {
                    $query->select('inquiry_id')->from('sal_quotation')->where('cust_id', '=', $customerId);
                }
            )->get();
        $response = array();
        foreach ($results as $result) {
            $response[] = array(
                "id" => $result->id,
                "date" => $result->date,
                "month" => $result->month,
                "number" => $result->number,
                "shiping_method" => $result->shiping_method,
                "payment_terms" => $result->payment_terms,
                "note" => $result->note,
                "note2" => $result->note2,
                "status" => $result->status,
                "company_id" => $result->company_id,
            );
        }
        return response()->json($response);
    }

    public function getQuotations(Request $request)
    {

        $cstCoaId  = $request->id;
        $customerDetail = $this->customerDetails(null, $cstCoaId);
        $customerId = $customerDetail['id'];
        $results = DB::table('sal_quotation')->select()
            ->where('cust_id', '=', $customerId)
            ->whereNotIn(
                'id',
                function ($query) use ($cstCoaId) {
                    $query->select('quot_id')->from('sal_invoices')->where('cst_coa_id', '=', $cstCoaId);
                }
            )->get();

        $response = array();
        foreach ($results as $result) {
            $response[] = array(
                "id" => $result->id,
                "date" => $result->date,
                "month" => $result->month,
                "number" => $result->number,
                "shiping_method" => $result->shiping_method,
                "payment_terms" => $result->payment_terms,
                "note" => $result->note,
                "status" => $result->status,
                "company_id" => $result->company_id,
            );
        }
        return response()->json($response);
    }
    ///////////////////////////////////////////// below are theme code will be deleted
    public static function customerDetails($CustId = null, $CustomerAccountId = null)
    {
        $fieldName  = ($CustId) ? 'id' : 'coa_id';
        $id = ($CustId) ? $CustId : $CustomerAccountId;
        $result = DB::table('crm_customers')->select()->where($fieldName, '=', $id)->first();
        $response = array(
            "id" => $result->id,
            "coa_id" => $result->coa_id,
            "name" => $result->name,

        );
        return $response;
    }

    // employee search
    // public static function employeeSearch(Request $request)
    // {
    //     $companyId = session('companyId');
    //     $searchStr = $request->name;
    //     $result = DB::table('hcm_employee')
    //                 ->select(array('id', 'first_name','last_name'))
    //                 ->where('first_name', 'like', '%'.$searchStr.'%')
    //                 ->where('designation','Manager')
    //                 ->where('company_id',$companyId)
    //                 ->orderby('first_name')
    //                 ->get();
    //     $response = array();
    //     foreach($result as $record){
    //        $response[] = array("value"=>$record->id,"label"=>$record->first_name.' '.$record->last_name);
    //     }
    //     return response()->json($response);
    // }

    //customer detail
    public static function getCustomerDetail(Request $request)
    {
        $result = customerLib::customerDetail($request->coa_id);
        $response = array(
            "phone" => $result->phone,
            "address" => $result->address,
            "location" => $result->location,
        );
        $response = $result;
        return response()->json($response);
    }

    // contact search
    public static function contactsSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('crm_contacts')->select(array('id', 'first_name', 'last_name'))->where('first_name', 'like', '%' . $searchStr . '%')->orderby('first_name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->first_name . ' ' . $record->last_name);
        }
        return response()->json($response);
    }
    // user search
    public static function userSearch(Request $request)
    {

        $searchStr = $request->name;
        $result = DB::table('users')->select(array('id', 'firstName', 'lastName'))->where('firstName', 'like', '%' . $searchStr . '%')->orderby('firstName')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->firstName . ' ' . $record->lastName);
        }
        return response()->json($response);
    }

    //transaction account search
    public static function transactionAccountsSearch(Request $request)
    {
        $searchStr = $request->name;
        $companyId = session('companyId');
        $result = DB::table('fs_coas')->select(array('id', 'name'))->where('fs_coas.trans_group', 1)->where('fs_coas.company_id', $companyId)->where('name', 'like', '%' . $searchStr . '%')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }

    //location
    public static function getLocation(Request $request)
    {
        $searchStr = $request->name;
        $result = DB::table('geo_locations')->select(array('id', 'name'))->where('name', 'like', '%' . $searchStr . '%')->where('trans', 'Yes')->orderby('name')->get();
        $response = array();
        foreach ($result as $record) {
            $response[] = array("value" => $record->id, "label" => $record->name);
        }
        return response()->json($response);
    }



    public static function checkBatch(Request $request)
    {

        $batch = DB::table('wip_material_issue_requests')->where('batch', $request->check_value)->first();
        if ($batch) {
            return "$request->check_value.is Already Exist";
        } else {
            return "";
        }
    }

    function inbox()
    {
        return view('app.inbox');
    }

    function compose()
    {
        return view('app.compose');
    }

    function single()
    {
        return view('app.single');
    }

    function chat()
    {
        return view('app.chat');
    }

    function calendar()
    {
        return view('app.calendar');
    }
    /*
    function calendarView(){
        return view('crm.calendar_view');
    }
*/
    function contactList()
    {
        return view('app.contact-list');
    }


    public function setTheme(Request $request)
    {
        $theme = $request->input('theme');
        $userId = auth()->id();
    
        $themeValue = $theme === 'light' ? 'light' : 'dark';
    
        DB::table('users')
            ->where('id', $userId)
            ->update(['theme' => $themeValue]);
    
        // Update the session theme value
        session(['theme' => $themeValue]);
    
        return redirect()->back();
    }
    

}
