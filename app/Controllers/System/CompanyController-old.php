<?php

namespace App\Http\Controllers\System;
//namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\system\Company;
use App\Libraries\dbLib;
use Config;
use Money\Currencies\ISOCurrencies;
use Money\Currency;


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
        // $countries = DB::table('sys_countries')->select(array('id', 'name'))->get();
        $countries = CountryListFacade::getList('en');
        $currencies = DB::table('sys_currencies')->select(array('code'))->get();
        // $currencies = new ISOCurrencies();
        $months = DB::table('sys_months')->select(array('month'))->get();
        $attachment = DB::table('sys_attachments')->where('source_id', '=', $id)->first();
        //dd($attachment);

        if ($id) {
            $company = DB::table('sys_companies')->select()->where('id', '=', $id)->first();
            return view('system.company', compact('months', 'countries', 'attachment', 'currencies', 'company'));
        } else {
            return view('system.company', compact('months', 'countries', 'currencies'));
        }
    }

    public function company_save(Request $request)
    {
        // dd($request->input());
        $data = array(
            'name' => $request->name,
            'website' => $request->website,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'fax' => $request->fax,
            'state' => $request->state,
            'address_two' => $request->address_two,
            'currency' => $request->currency,
            'country' => $request->country,
            'Tax_number' => $request->tax_number,
            'multi_currency' => $request->multi_currency == TRUE ? '1' : '0',
            'fiscal_year' => $request->fiscal_year,
        );


        if ($request->id) {
            //  Company::update($data)->where('id','=', request()->id);
            DB::table('sys_companies')->where('id', '=', $request->id)->update($data);
        } else {
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
            "rate" => $request->rate,
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
}
