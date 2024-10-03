<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $vendorList=DB::table('pa_vendors')
                    ->join('pa_vendor_details','pa_vendor_details.pa_ven_id','=','pa_vendors.id')
                    ->select('pa_vendors.*','pa_vendor_details.email','pa_vendor_details.phone')
                    ->where('pa_vendors.company_id',session('companyId'))
                    ->get();
        return view('vendor.vendor_list',compact('vendorList'));
    }
    public function form($id=null)
    {
        
        
        $coaAccount = DB::table('fs_coas')->select(array('coa_id','name'))->where('id','=',48)->orderBy('name')->get();
        $categories=DB::table('pa_categories')->select('category')->get();
        $vendor_types=DB::table('sys_options')->select('description')->where('type','vendor_type')->where('status',1)->get();
        $credit_limits=DB::table('sys_options')->select('description')->where('type','customer_credit_limit')->where('status',1)->get();
        if($id)
        {
            $vendor=DB::table('pa_vendors')
                        ->join('fs_coas','fs_coas.id','=','pa_vendors.coa_id')
                        ->join('pa_vendor_details','pa_vendor_details.pa_ven_id','=','pa_vendors.id')
                       ->select('pa_vendors.*','pa_vendor_details.*','fs_coas.code')
                        ->where('pa_vendors.id','=',$id)
                        ->get()
                        ->first();
            return view('vendor.vendor',compact('categories','coaAccount','vendor','vendor_types','credit_limits'));
        }
        return view('vendor.vendor',compact('categories','coaAccount','vendor_types','credit_limits'));
    }
    public function save(Request $request)
    {
        $companyId = session('companyId');    
        $userId = Auth::id();  
        $data = array(
            "name"=>$request->name ,
            "category"=>$request->category ,
            "note"=>$request->note ,
            "status"=>$request->status ,
            "type"=>$request->type,
            "credit_limit"=>$request->credit_limit,
            "tax_number"=>$request->tax_number,
            "created_by"=>$userId,   
        );
        {
            if($request->coa_id)
            {
                
               $coaData = array ("name"=>$request->name);
                dbLib::updateCoaAccount($coaData, $request->coa_id);
            }
            else    // create new account
            {
                
                $coaData = array(
                    "coa_id"=>$request->parent_coa_id ,
                    "name"=>$request->name,
                    "coa_display"=>1,
                    "status"=>1,
                    "trans_group"=>1,
                    "category"=>1,
                    "company_id"=>$companyId,
                    "editable"=>0
                    ); 
                
                $coaId = dbLib::createCoaAccount($coaData);
                $data['coa_coa_id']=$request->parent_coa_id;
                $data['coa_id']=$coaId;
            }
        }
        if($request->id)
        {    
            $pa_venId = $request->id;
            DB::table('pa_vendors')->where('id','=',$pa_venId)->update($data);      
        }
        else
        {
            $pa_venId = str::uuid()->toString();
            $data['id']=$pa_venId;
            $data['company_id']=$companyId;
            DB::table('pa_vendors')->insert($data);
            
        }
        // Pa Vendor Details 
        DB::table('pa_vendor_details')->where('pa_ven_id','=',$pa_venId)->delete();
            $vendorDetail = array (
                "id"=>str::uuid()->toString(),
                "pa_ven_id"=>$pa_venId,
                "location"=>$request->location,
                "address"=>$request->address,
                "phone"=>$request->phone,
                "email"=>$request->email,
                "fax"=>$request->fax,
            );
            DB::table('pa_vendor_details')->insert($vendorDetail);
        // Pa Vendor Attachment
        if($request->file)
        {
            DB::table('pa_vendor_attachments')->where('pa_ven_id','=',$pa_venId)->delete();
             $file=$request->file;
              $fileName = time().'.'.$file->getClientOriginalName();  
              $file->move(public_path('assets/attachments'), $fileName);
              $fileData = array(
                  "pa_ven_id"=>$pa_venId,
                  "file"=> $fileName,
              );
              DB::table('pa_vendor_attachments')->insert($fileData);
        }

       // return 
        return redirect('Vendor/VendorManagement/List');
    }

    public function view($id)
    {
        $vendor=DB::table('pa_vendors')
                    ->leftjoin('pa_vendor_details as vd','vd.pa_ven_id','=','pa_vendors.id')
                    ->leftjoin('sys_companies','sys_companies.id','pa_vendors.company_id')
                    ->select('pa_vendors.*','vd.address','vd.location','vd.email','vd.fax','vd.phone','sys_companies.name as company_name')
                    ->where('pa_vendors.id',$id)
                    ->get()
                    ->first();
        $vendor_contacts=DB::table('crm_contacts')->select('mobile','email','title',DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"))->where('related_id',$id)->get();
        return view('vendor.vendor_view',compact('vendor','vendor_contacts'));
    }
    
}
