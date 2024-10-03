<?php

namespace App\Http\Controllers\System;
//namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\system\Company;
use App\Libraries\dbLib;
use Config;


class CompanyController extends Controller
{
    public function company_index($id = null)
    {
        $companies = DB::table('sys_companies')->get();
        //  $attachmentRecord=dbLib::getAttachment($id);
        return view('system.company_list', compact('companies'));
    }
    public function company_form($id = null)
    {
        $countries = DB::table('sys_countries')->select(array('id', 'name'))->get();
        $currencies = DB::table('sys_currencies')->select(array('code'))->get();
        $months = DB::table('sys_months')->select(array('month'))->get();
        $attachment = DB::table('sys_attachments')->where('source_id', '=', $id)->first();

        // Check if the "multi currency" checkbox should be checked
        $multiCurrencyChecked = false; // By default, it is unchecked

        if ($id) {
            $company = DB::table('sys_companies')->select()->where('id', '=', $id)->first();

            // Check if the company has the multi_currency flag set to true
            if ($company && $company->multi_currency) {
                $multiCurrencyChecked = true; // Set the checkbox as checked
            }

            return view('system.company', compact('months', 'countries', 'attachment', 'currencies', 'company', 'multiCurrencyChecked'));
        } else {
            return view('system.company', compact('months', 'countries', 'currencies', 'multiCurrencyChecked'));
        }
    }

    public function company_save(Request $request)
    {




        //dd($request->input());
        $data = array(
            'name' => $request->name,
            'website' => $request->website,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            // 'fax' => $request->fax,
            'state' => $request->state,
            'address_two' => $request->address_two,
            'currency' => $request->currency,
            'country' => $request->country,
            'tax_number' => $request->tax_number,
            'fiscal_year' => $request->fiscal_year,
            'multi_currency' => $request->has('multi_currency') ? 1 : 0,
        );


        if ($request->id) {
            //  Company::update($data)->where('id','=', request()->id);
            DB::table('sys_companies')->where('id', '=', $request->id)->update($data);
        } else {

            $request->validate([
                'name' => 'nullable|unique:sys_companies,name',
                'website' => 'nullable|unique:sys_companies,website',
                'phone' => 'nullable|string|regex:/^\+?[0-9]{1,}$/',
                'email' => 'nullable|string|email|max:255|unique:sys_companies,email',
                'tax_number' => 'nullable|numeric',
                'address' => 'nullable|unique:sys_companies,address',
                // 'fax' => 'nullable|unique:sys_companies,fax',
                'address_two' => 'nullable|unique:sys_companies,address_two',
                'tax_number' => 'nullable|numeric|unique:sys_companies,tax_number',

                // Other validation rules for your fields
            ]);
            $id = str::uuid()->toString();
            $data['id'] = $id;
            DB::table('sys_companies')->insert($data);
        }

        if ($request->file('file'))
            dbLib::uploadDocument($request->id, $request->file);


        session()->flash('message', 'Company information updated successfully!');
        return redirect('Admin/Company/List');
    }

    public function logoRemove(Request $request)
    {
        DB::table('sys_companies')->where('logo', $request->img)->update(['logo' => null]);
    }


    ////////////////////////////////// -- WareHouse -- /////////////////////////////////////////

    public function warehouse_index()
    {
        $warehouselist = DB::table('sys_warehouses')->select()->where('sys_warehouses.company_id', session('companyId'))->orderBy('name')->get();
        return view('system.warehouse-list', compact('warehouselist'));
    }

    public function warehouse_form($id = null)
    {
        $parent_warehouses = DB::table('sys_warehouses')->where('sys_warehouses.company_id', session('companyId'))->select('id', 'name')->get();
        if ($id) {
            $warehouse = DB::table('sys_warehouses')->where('id', '=', $id)->get()->first();
            return view('system.warehouse', compact('warehouse', 'parent_warehouses'));
        } else {
            return view('system.warehouse', compact('parent_warehouses'));
        }
    }

    public function warehouse_save(Request $request)
    {

        $request->validate([
            'warehouse' => 'required|alpha',
            'phone' => 'nullable|numeric',
        ]);
        $companyId = session('companyId');
        if ($request->id) {
            $warehouse = array(
                "name" => $request->warehouse,
                "parent_id" => $request->parent_id,
                "email" => $request->email,
                "phone" => $request->phone,
                "location" => $request->location,
                "address" => $request->address,
                "status" => $request->status,
                "company_id" => $companyId,
            );
            DB::table('sys_warehouses')->where('id', '=', $request->id)->update($warehouse);
        } else {
            $id = Str::uuid()->toString();
            $warehouse = array(
                "id" => $id,
                "parent_id" => $request->parent_id,
                "name" => $request->warehouse,
                "email" => $request->email,
                "phone" => $request->phone,
                "location" => $request->location,
                "address" => $request->address,
                "status" => $request->status,
                "company_id" => $companyId,
            );
            DB::table('sys_warehouses')->insert($warehouse);
        }
        return redirect('Admin/Warehouse/List');
    }


    //////////////////////////////////////////////// Taxes ///////////////////////////////

    public function taxes_index()
    {
        $taxList = DB::table('sys_taxes')->select()->orderBy('name')->get();
        return view('system.taxes_list', compact('taxList'));
    }

    public function taxes_form($id = null)
    {
        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->where('coa_id', '=', '49')->orderBy('name')->get();
        $tax = DB::table('sys_taxes')->select()->where('id', '=', $id)->first();
        return view('system.tax', compact('tax', 'coaAccount'));
    }

    public function taxes_save(Request $request)
    {

        $rate = $request->rate;
        $ratePattern = '/^\d{0,10}(\.\d+)?$/';


        if (!preg_match($ratePattern, $rate)) {
            // Flash the error message
            session()->flash('error', 'Rate should have a maximum of 10 digits before and 2 digits after the decimal point.');

            // Redirect back with the error message and the input data
            return redirect()->back()->withInput()->withErrors(['rate' => 'Rate should have a maximum of 10 digits before and 2 digits after the decimal point.']);
        }


        $data = array(
            "coa_id" => $request->coa_id,
            "name" => $request->name,
            "rate" => $request->rate,
            "withheld" => $request->withheld,
            "status" => $request->status,
        );

        if ($request->id)
            DB::table('sys_taxes')->where('id', '=', $request->id)->update($data);
        else
            DB::table('sys_taxes')->insert($data);

        return redirect()->route('Admin/Taxes/List');
    }

    //////////////////////////////////////////////// Discount ///////////////////////////////

    public function discount_index()
    {
        $discountList = DB::table('sys_discounts')->select()->orderBy('name')->get();
        return view('system.discount_list', compact('discountList'));
    }

    public function discounts_form($id = null)
    {
        $coaAccount = DB::table('fs_coas')->select(array('id', 'name'))->where('coa_id', '=', '49')->orderBy('name')->get();
        $discount = DB::table('sys_discounts')->select()->where('id', '=', $id)->first();

        return view('system.discount', compact('discount', 'coaAccount'));
    }

    public function discounts_save(Request $request)
    {

        $data = array(
            "coa_id" => $request->coa_id,
            "name" => $request->name,
            "category" => $request->category,
            "rate" => $request->amount ?? $request->rate_percentage, // Use the correct name attribute
            "type" => $request->type,
            "status" => $request->status,
        );

        if ($request->id)
            DB::table('sys_discounts')->where('id', '=', $request->id)->update($data);
        else {
            $id = Str::uuid()->toString();
            $data['id'] = $id;
            DB::table('sys_discounts')->insert($data);
        }
        return redirect()->route('Admin/Discounts/List');
    }


    //////////////////////////////////////////////// Terms & Conditions ///////////////////////////////
    public function terms_index()
    {
        $termList = DB::table('sys_terms')->select()->orderBy('category')->get();
        return view('system.terms_list', compact('termList'));
    }

    public function terms_form($id = null)
    {
        $term = DB::table('sys_terms')->select()->where('id', '=', $id)->first();
        $categories = DB::table('sys_terms')->distinct()->get(['category']);
        return view('system.term', compact('term', 'categories'));
    }

    public function terms_save(Request $request)
    {
        $data = array(
            "category" => $request->category,
            "terms" => $request->terms,
            "status" => $request->status
        );
        if ($request->id)
            DB::table('sys_terms')->where('id', '=', $request->id)->update($data);
        else
            DB::table('sys_terms')->insert($data);

        return redirect()->route('Admin/Term/List');
    }

    //////////////////////////////////////////////// Departments ///////////////////////////////

    public function depart_index()
    {
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        return view('system.department_list', compact('departments'));
    }

    public function depart_form($id = null)
    {

        return view('system.department');
    }

    public function depart_save(Request $request)
    {
        $companyId = session('companyId');
        $department = array(
            "dpt_id" => $request->main_department,
            "name" => $request->department,
            "company_id" => $companyId,
        );
        if ($request->id) {

            $department['id'] = $request->id;
            DB::table('sys_departments')->where('id', $request->id)->update($department);
            return redirect('Admin/Department/List')->with('update_msg', 'Department Updated');
        } else {
            $id = str::uuid()->toString();
            $department['id'] = $id;
            DB::table('sys_departments')->insert($department);
            return redirect('Admin/Department/List')->with('insert_msg', 'New Department Added');
        }
    }

    public function depart_edit(Request $request)
    {

        $department = DB::table('sys_departments')->where('id', '=', $request->id)->get();
        return $department;
    }

    public function depart_remove($id)
    {
        DB::table('sys_departments')->where('id', $id)->delete();
        return back()->with('delete_msg', 'Brand Delete');
    }

    public function smtpView($id = null)
    {

        if ($id) {
            $credentials =  DB::table('smtp_pop_setting')->where('company_id', session('companyId'))->orderBy('created_at', 'desc')->get();
            $credential =  DB::table('smtp_pop_setting')->where('id', $id)->first();
            $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
            return view('system.smtp_pop', compact('credential', 'credentials', 'departments'));
        }
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        $credentials =  DB::table('smtp_pop_setting')->where('company_id', session('companyId'))->orderBy('created_at', 'desc')->get();
        $credential =  null;


        return view('system.smtp_pop', compact('credentials', 'departments', 'credential'));
    }

    public function smtpstore(Request $request)
    {


        // dd($data);
        if ($request->credential_id) {
            $data = [
                "imap_host" => $request->imap_host,
                "imap_port" => $request->imap_port,
                "imap_protocol" => $request->imap_protocol,
                "imap_encryption" => $request->imap_encryption,
                "department" => $request->department,
                "message" => $request->message,
                "imap_username" => $request->imap_username,
                "imap_password" => $request->imap_password,
                "company_id" => session('companyId'),

            ];
            DB::table('smtp_pop_setting')->where('id', $request->credential_id)->update($data);
            return response()->json([
                'id' => $request->credential_id,
                'port' => $data['imap_port'],
                'host' => $data['imap_host'],
                'username' => $data['imap_username'],
                'password' => $data['imap_password'],
                'encryption' => $data['imap_encryption'],
                'protocol' => $data['imap_protocol'],
                'message' => $data['message'],
                'department' => $data['department'],

            ]);
        } else {
            $id = str::uuid()->toString();
            $data = [
                'id' => $id,
                "imap_host" => $request->imap_host,
                "imap_port" => $request->imap_port,
                "imap_protocol" => $request->imap_protocol,
                "imap_encryption" => $request->imap_encryption,
                "department" => $request->department,
                "message" => $request->message,
                "status" => 'deactivate',
                "imap_username" => $request->imap_username,
                "imap_password" => $request->imap_password,
                "company_id" => session('companyId'),

            ];

            DB::table('smtp_pop_setting')->insert($data);
            return response()->json([
                'id' => $data['id'],
                'port' => $data['imap_port'],
                'host' => $data['imap_host'],
                'username' => $data['imap_username'],
                'password' => $data['imap_password'],
                'encryption' => $data['imap_encryption'],
                'protocol' => $data['imap_protocol'],
                'status' => 'deactivate',
                'department' => $data['department'],
                'message' => $data['message'],
            ]);
        }
    }


    public function smtpdelete($credential_id)
    {

        DB::table('smtp_pop_setting')->where('id', $credential_id)->delete();
        return redirect()->back()->with('error', 'Credential Deleted successfuly');
    }

    public function smtpActive($id)
    {

        DB::table('smtp_pop_setting')->where('id', $id)->update(['status' => 'activate']);
        return redirect()->back();
    }
    public function smtpDeActive($id)
    {

        DB::table('smtp_pop_setting')->where('id', $id)->update(['status' => 'deactivate']);
        return redirect()->back();
    }


    public function mailview($id = null)
    {

        if ($id) {

            $Mail =  DB::table('mail_configration_setting')->where('id',$id)->first();
            $Mails =  DB::table('mail_configration_setting')->where('company_id', session('companyId'))->orderBy('created_at', 'desc')->get();
            $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
            return view('system.mail-configration', compact('departments', 'Mails','Mail'));
        }
        $Mail = null;
        $Mails =  DB::table('mail_configration_setting')->where('company_id', session('companyId'))->orderBy('created_at', 'desc')->get();
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        return view('system.mail-configration', compact('departments', 'Mails','Mail'));
    }

    public function mailstore(Request $request)
    {


        // dd($data);
        if ($request->id) {
            $data = [
                "mail_host" => $request->mail_host,
                "mail_port" => $request->mail_port,
                "mail_transport" => $request->mail_transport,
                "mail_encryption" => $request->mail_encryption,
                "department" => $request->department,
                "mail_username" => $request->mail_username,
                "mail_password" => $request->mail_password,
                "from_name" => $request->from_name,
                "company_id" => session('companyId'),

            ];
            DB::table('mail_configration_setting')->where('id', $request->id)->update($data);
            return response()->json([
                'id' => $request->id,
                'port' => $data['mail_port'],
                'host' => $data['mail_host'],
                'username' => $data['mail_username'],
                'password' => $data['mail_password'],
                'encryption' => $data['mail_encryption'],
                'transport' => $data['mail_transport'],
                'department' => $data['department'],
                'from_name' => $data['from_name'],
            ]);
        } else {
            $id = str::uuid()->toString();
            $data = [
                'id' => $id,
                "mail_host" => $request->mail_host,
                "mail_port" => $request->mail_port,
                "mail_transport" => $request->mail_transport,
                "mail_encryption" => $request->mail_encryption,
                "department" => $request->department,
                "status" => 'deactivate',
                "mail_username" => $request->mail_username,
                "mail_password" => $request->mail_password,
                "from_name" => $request->from_name,
                "company_id" => session('companyId'),

            ];

            DB::table('mail_configration_setting')->insert($data);
            return response()->json([
                'id' => $data['id'],
                'port' => $data['mail_port'],
                'host' => $data['mail_host'],
                'username' => $data['mail_username'],
                'password' => $data['mail_password'],
                'encryption' => $data['mail_encryption'],
                'transport' => $data['mail_transport'],
                'status' => 'deactivate',
                'department' => $data['department'],
                'from_name' => $data['from_name'],

            ]);
        }
    }

    public function maildelete($id)
    {

        DB::table('mail_configration_setting')->where('id', $id)->delete();
        return redirect()->back()->with('error', 'Mail Deleted successfuly');
    }

    public function MailActive($id)
    {

        DB::table('mail_configration_setting')->where('id', $id)->update(['status' => 'activate']);
        return redirect()->back();
    }
    public function MailDeActive($id)
    {

        DB::table('mail_configration_setting')->where('id', $id)->update(['status' => 'deactivate']);
        return redirect()->back();
    }



}
