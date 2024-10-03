<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\inventory\Inventory;

class CustomerController extends Controller
{
    public function index($id = null)
    {
        $customers = DB::table('crm_customers')
            ->leftjoin('crm_customers_address', 'crm_customers_address.cust_id', 'crm_customers.id')
            ->leftjoin('crm_customer_extends as crm_extends', 'crm_extends.cust_id', 'crm_customers.id')
            ->leftjoin('fs_coas', 'fs_coas.id', '=', 'crm_customers.coa_id')
            ->select('crm_customers.*', 'crm_customers_address.phone', 'crm_customers_address.email', 'fs_coas.name as main_account', 'crm_extends.discount', 'crm_extends.special_discount', 'crm_extends.cod')
            ->orderBy('crm_customers.name', 'ASC')
            ->where('crm_customers.company_id', session('companyId'))
            ->whereNotNull('crm_customers.coa_id')
            ->get();
        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->where('coa_id', '=', '73')->orderBy('name')->get();

        //$customerAddress = DB::table('crm_customers_address')->select()->where('cust_id','=',$customers )->first();
        return view('crm.customers_list', compact('customers'));
    }
    public function form($id = null)
    {

        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->orderBy('name')->get();
        $categories  = DB::table('crm_categories')->select()->orderBy('category')->get();

        $customer_types = appLib::getOptions('customer_type'); //DB::table('sys_options')->select('description')->where('type','customer_type')->where('status',1)->get();
        $credit_limits = appLib::getOptions('customer_credit_limit'); // DB::table('sys_options')->select('description')->where('type','customer_credit_limit')->where('status',1)->get();

        $customer = DB::table('crm_customers')->select()->where('id', '=', $id)->first();
        $customerAddress = DB::table('crm_customers_address')->select()->where('cust_id', '=', $id)->first();
        $customerExtend = DB::table('crm_customer_extends')->select()->where('cust_id', '=', $id)->first();
        return view('crm.customer', compact('customer', 'customerAddress', 'customerExtend', 'coaAccount', 'categories', 'customer_types', 'credit_limits'));
    }

    public function view($id)
    {
        //dd($id);
        $customer = DB::table('crm_customers')
            ->leftjoin('crm_customers_address', 'crm_customers_address.cust_id', '=', 'crm_customers.id')
            ->leftjoin('sys_companies', 'sys_companies.id', '=', 'crm_customers.company_id')
            ->select('crm_customers.*', 'crm_customers_address.location', 'crm_customers_address.address', 'crm_customers_address.phone', 'crm_customers_address.fax', 'crm_customers_address.email', 'sys_companies.name as company_name')
            ->where('crm_customers.id', '=', $id)->first();
        $customer_contacts = DB::table('crm_contacts')->select('id','mobile', 'email', 'title', DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"))
            ->where('related_id', $customer->coa_id)->get();
        $customer_notes = DB::table('crm_notes')->select('id','subject', 'description')
            ->where('related_id', $customer->coa_id)->get();
        $customer_calls = DB::table('crm_calls')->select('id','subject', 'description', 'start_date', 'end_date')
            ->where('related_id', $customer->coa_id)->get();
        $customer_tasks = DB::table('crm_tasks')->select('id','subject', 'description', 'start_date', 'due_date')
            ->where('related_id', $customer->coa_id)->get();
        return view('crm.customer_view', compact('customer', 'customer_contacts', 'customer_notes', 'customer_calls', 'customer_tasks'));
    }

    // Saves cusotmer and lead information
    public function save(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|regex:/^\+?[0-9]{1,}$/',
            'email' => 'nullable|string|email|max:255|',
            'tax_number'=> 'nullable|numeric',
        ]);

        // Validation
        $id = ($request->id) ? $request->id : NULL;
        // $arrValidation = array('name' => 'required|unique:crm_customers,name,' . $id);
        // $Validation = $request->validate($arrValidation);
        //////////////////////

        // dd($request->input());
        $companyId = session('companyId');
        $userId = Auth::id();
        $data = array(
            "name" => $request->name,
            "category" => $request->category,
            "note" => $request->note,
            "created_by" => $userId,
            "status" => $request->status,
            "type" => $request->type,
            "credit_limit" => $request->credit_limit,
            "credit_amount" => $request->credit_amount,
            "margin" => $request->margin,
            "tax_number" => $request->tax_number,
            "lead" => 0,
            "code" => $request->code,
        );

        //        if($request->t==1)
        {
            if ($request->coa_id) {
                $coaData = array("name" => $request->name);
                dbLib::updateCoaAccount($coaData, $request->coa_id);
            } else    // create new account
            {

                $coaData = array(
                    "coa_id" => $request->parent_coa_id,
                    "name" => $request->name,
                    "coa_display" => 1,
                    "status" => 1,
                    "trans_group" => 1,
                    "category" => 1,
                    "company_id" => $companyId,
                    "editable" => 0
                );

                $coaId = dbLib::createCoaAccount($coaData);
                $data['coa_coa_id'] = $request->parent_coa_id;
                $data['coa_id'] = $coaId;
            }
        }
        if ($request->id) {
            $custId = $request->id;
            DB::table('crm_customers')->where('id', '=', $custId)->update($data);
        } else {
            $custId = str::uuid()->toString();
            $data['id'] = $custId;
            $data['company_id'] = $companyId;
            DB::table('crm_customers')->insert($data);
        }

      

        $dataAddress = array(
            "id" => str::uuid()->toString(),
            "cust_id" => $custId,
            "location" => $request->location,
            "address" => $request->address,
            "phone" => $request->phone,
            "email" => $request->email,
            // "fax" => $request->fax,
            "status" => 1,
        );
        DB::table('crm_customers_address')->insert($dataAddress);

        //        // Address
        //        DB::table('crm_customers_address')->where('cust_id','=',$custId)->delete();
        //        $count = count($request->location);
        //        for($i=0;$i<$count;$i++)
        //        {
        //            $dataAddress = array (
        //                "id"=>str::uuid()->toString(),
        //                "cust_id"=>$custId,
        //                "location"=>$request->location[$i],
        //                "address"=>$request->address[$i] ,
        //                "phone"=>$request->phone[$i] ,
        //                "email"=>$request->email[$i] ,
        //                "fax"=>$request->fax[$i] ,
        //                "status"=>1 ,
        //            );
        //            DB::table('crm_customers_address')->insert($dataAddress);
        //        }

        //Customer_extend
        DB::table('crm_customer_extends')->where('cust_id', '=', $custId)->delete();
        $customer_extend = array(
            "cust_id" => $custId,
            "cnic" => $request->cnic,
            "chanell" => $request->chanell,
            "stn" => $request->stn,
            "discount" => $request->discount,
            "special_discount" => $request->special_discount,
            "adv_payment" => $request->adv_payment,
            "cod" => $request->cod,
        );
        DB::table('crm_customer_extends')->insert($customer_extend);


        // Attachment
        // return redirect()->route('Crm/Customers/Create/');
        // return redirect()->route('Crm/customerList/'.$t);
        return redirect()->route('Crm/Customers/List/');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'phone' => 'nullable|string|regex:/^\+?[0-9]{1,}$/',
            'email' => 'nullable|string|email|max:255|',
            'tax_number'=> 'nullable|numeric',
        ]);


        $companyId = session('companyId');
        $userId = Auth::id();
    
        // Data array for customer
        $data = [
            "name" => $request->name,
            "category" => $request->category,
            "note" => $request->note,
            "created_by" => $userId,
            "status" => $request->status,
            "type" => $request->type,
            "credit_limit" => $request->credit_limit,
            "credit_amount" => $request->credit_amount,
            "margin" => $request->margin,
            "tax_number" => $request->tax_number,
            "lead" => 0,
            "code" => $request->code,
        ];
    
        // COA account update or creation
        if ($request->coa_id) {
            $coaData = ["name" => $request->name];
            dbLib::updateCoaAccount($coaData, $request->coa_id);
        } else {
            $coaData = [
                "coa_id" => $request->parent_coa_id,
                "name" => $request->name,
                "coa_display" => 1,
                "status" => 1,
                "trans_group" => 1,
                "category" => 1,
                "company_id" => $companyId,
                "editable" => 0
            ];
    
            $coaId = dbLib::createCoaAccount($coaData);
            $data['coa_coa_id'] = $request->parent_coa_id;
            $data['coa_id'] = $coaId;
        }
    
        // Update the customer
        DB::table('crm_customers')->where('id', '=', $id)->update($data);
    
        // Update the customer address
        $dataAddress = [
            "location" => $request->location,
            "address" => $request->address,
            "phone" => $request->phone,
            "email" => $request->email,
            // "fax" => $request->fax,
            "status" => 1,
        ];
        DB::table('crm_customers_address')->where('cust_id', '=', $id)->update($dataAddress);
    
        // Customer extend data
        DB::table('crm_customer_extends')->where('cust_id', '=', $id)->delete();
        $customerExtend = [
            "cust_id" => $id,
            "cnic" => $request->cnic,
            "chanell" => $request->chanell,
            "stn" => $request->stn,
            "discount" => $request->discount,
            "special_discount" => $request->special_discount,
            "adv_payment" => $request->adv_payment,
            "cod" => $request->cod,
        ];
        DB::table('crm_customer_extends')->insert($customerExtend);
    
        // Redirect to the desired route
        return redirect()->route('Crm/Customers/List/');
    }


    public function import(Request $request)
{
    // Perform the import logic

    // Set the flash message
    $request->session()->flash('alert', 'Import successful!');

    // Redirect back to the previous page
    return redirect()->back();
}
    
}
