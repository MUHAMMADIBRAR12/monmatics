<?php

namespace App\Http\Controllers\Crm;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Libraries\appLib;
use App\inventory\Inventory;


class CrmController extends Controller
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
            ->whereNull('crm_customers.coa_id')
            ->get();
        //$customerAddress = DB::table('crm_customers_address')->select()->where('cust_id','=',$customers )->first();
        return view('crm.leads_list', compact('customers'));
    }

    public function form($id = null)
    {
        // $coaAccount = DB::table('fs_coas')->select(array('id','name'))->where('coa_id','=','73')->orderBy('name')->get();
        //$customer_types=DB::table('sys_options')->select('description')->where('type','customer_type')->where('status',1)->get();
        //$credit_limits=DB::table('sys_options')->select('description')->where('type','customer_credit_limit')->where('status',1)->get();
        $categories  = DB::table('crm_categories')->select()->orderBy('category')->get();
        $customer_types = appLib::getOptions('customer_type');
        $lead_sources = appLib::getOptions('opportunities_lead_source');
        $customer = DB::table('crm_customers')->select()->where('id', '=', $id)->first();
        $customerAddress = DB::table('crm_customers_address')->select()->where('cust_id', '=', $id)->first();
        $customerExtend = DB::table('crm_customer_extends')->select()->where('cust_id', '=', $id)->first();
        return view('crm.leads', compact('customer', 'customerAddress', 'customerExtend', 'categories', 'customer_types', 'lead_sources'));
    }

    public function view($id)
    {
        //dd($id);
        $customer = DB::table('crm_customers')
            ->leftjoin('crm_customers_address', 'crm_customers_address.cust_id', '=', 'crm_customers.id')
            ->leftjoin('sys_companies', 'sys_companies.id', '=', 'crm_customers.company_id')
            ->select('crm_customers.*', 'crm_customers_address.location', 'crm_customers_address.address', 'crm_customers_address.phone', 'crm_customers_address.fax', 'crm_customers_address.email', 'sys_companies.name as company_name')
            ->where('crm_customers.id', '=', $id)->first();

        $customer_contacts = DB::table('crm_contacts')->select('mobile', 'email', 'title', DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"))
            ->where('related_id', $id)->get();

        $customer_notes = DB::table('crm_notes')->select('subject', 'description')
            ->where('related_id', $id)->get();

        $customer_calls = DB::table('crm_calls')->select('subject', 'description', 'start_date', 'end_date')
            ->where('related_id', $id)->get();
        $customer_tasks = DB::table('crm_tasks')->select('subject', 'description', 'start_date', 'due_date')
            ->where('related_id', $id)->get();


        return view('crm.leads_view', compact('customer', 'customer_contacts', 'customer_notes', 'customer_calls', 'customer_tasks'));
    }

    // Saves cusotmer and lead information
    public function save(Request $request)
    {
        $request->validate([
            'phone.*' => 'nullable|regex:/^\+?[0-9]{1,}$/',
            'email.*' => 'nullable|email|max:255',
        ]);
    
        $companyId = session('companyId');
        $userId = Auth::id();
        $data = array(
            "name" => $request->name,
            "category" => $request->category,
            "note" => $request->note,
            "lead" => 1,
            "lead_source" => $request->lead_source,
            "created_by" => $userId,
            "status" => $request->status,
            "type" => $request->type,
        );
    
        if ($request->id) {
            $custId = $request->id;
            DB::table('crm_customers')->where('id', '=', $custId)->update($data);
        } else {
            $custId = str::uuid()->toString();
            $data['id'] = $custId;
            $data['company_id'] = $companyId;
            DB::table('crm_customers')->insert($data);
        }
    
        // Address
        DB::table('crm_customers_address')->where('cust_id', '=', $custId)->delete();
    
        $count = count($request->phone);
        for ($i = 0; $i < $count; $i++) {
            $address = $request->address[$i] ?? null;
            $phone = $request->phone[$i] ?? null;
            $email = $request->email[$i] ?? null;
            // $fax = $request->fax[$i] ?? null;
    
            if ($address && $phone && $email) {
                $dataAddress = array(
                    "id" => str::uuid()->toString(),
                    "cust_id" => $custId,
                    "address" => $address,
                    "phone" => $phone,
                    "email" => $email,
                    // "fax" => $fax,
                    "status" => 1,
                );
                DB::table('crm_customers_address')->insert($dataAddress);
            } else {
                // Handle the error or provide a default value
            }
        }
    
        // Attachment
    
        return redirect()->route('Crm/Leads/List/');
    }
    
}
