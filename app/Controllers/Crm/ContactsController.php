<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\appLib;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = DB::table('crm_contacts')
            ->leftjoin('users', 'users.id', '=', 'crm_contacts.assigned_to')
            ->select('crm_contacts.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            ->where('crm_contacts.assigned_to', '=', Auth::user()->id)
            ->get();
        $table = appLib::$related_table;
        $contactsList = array();
        foreach ($contacts as $contact) {
            $field = (($contact->related_to_type ?? '') == 'customer') ? "coa_id" : "id";
            $contacts_arr = array(
                "id" => $contact->id,
                "name" => $contact->first_name . ' ' . $contact->last_name,
                "profile" => $contact->profile,
                "office_No" => $contact->phone_office,
                "email" => $contact->email,
                "account_Name" => ($contact->related_to_type) ? DB::table($table[$contact->related_to_type])->select('name')->where($field, $contact->related_id)->get()->first() : '',
                "title" => $contact->title,
                "user_name" => $contact->user_name,
                "created_at" => $contact->created_at,
            );
            array_push($contactsList, $contacts_arr);
        }
        return view('crm.contacts_list', compact('contactsList'));
    }

    public function view($id)
    {
        //dd($id);
        $contact = DB::table('crm_contacts')->select('crm_contacts.*', DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"))->where('id', '=', $id)->first();
        return view('crm.contacts_view', compact('contact'));
    }


    
    public function form($id = null, $relatedTo = null, $refId=null)
{
    $backURL = redirect()->back()->getTargetUrl();

    // Get Related to Info if user come from Refrece from (Customer/Leads)
    if ($id == 1  && $relatedTo) {        
        if($relatedTo=='vendor')
        {
            $relatedToInfo = DB::table('pa_vendors')->select('id', 'name')->where('id','=', $refId)->first();
        }
        else
        {
            $idVal = ($relatedTo=='customer'?'coa_id as id':'id');
            $relatedToInfo = DB::table('crm_customers')->select(DB::raw($idVal), 'name')->where('id','=', $refId)->first();
        }
        return view('crm.contacts', compact('relatedTo', 'relatedToInfo', 'backURL'));
    } 
   elseif ($id) {
    $contact = DB::table('crm_contacts')
        ->leftjoin('users', 'users.id', '=', 'crm_contacts.assigned_to')
        ->select('crm_contacts.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
        ->where('crm_contacts.id', $id)
        ->first();

    $relatedToInfo = null;

    if ($contact->related_to_type == 'customer') {
        $relatedToInfo = DB::table('crm_customers')
            ->select('coa_id as id', 'name')
            ->where('coa_id', $contact->related_id)
            ->first();
    } elseif ($contact->related_to_type == 'lead') {
        $relatedToInfo = DB::table('crm_customers')
            ->select('id', 'name')
            ->where('id', $contact->related_id)
            ->first();
    }

    return view('crm.contacts', compact('contact', 'relatedToInfo', 'backURL'));
}

    else
        return view('crm.contacts', compact( 'backURL'));
    

}

    

    public function save(Request $request)
    {

        $request->validate([
            'mobile' => 'nullable|regex:/^\+?[0-9]{1,}$/',
            'office_phone' => 'nullable|string|regex:/^\+?[0-9]{1,}$/',
            'email' => 'nullable|string|email|max:255|',
        ]);
        
        $userId = Auth::id();
        $companyId = session('companyId');
        $contact = array(
            "mr" => $request->type,
            "first_name" => $request->fname,
            "last_name" => $request->lname,
            "title" => $request->title,
            "related_to_type" => $request->related_to_type,
            "related_id" => $request->related_ID,
            "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
            "mobile" => $request->mobile,
            "email" => $request->email,
            "phone_office" => $request->office_phone,
            "company_id" => $companyId,
            "user_id" => $userId,
            "primary_address" => $request->pri_address,
            "primary_city" => $request->pri_city,
            "primary_state" => $request->pri_state,
            "primary_postal_code" => $request->pri_code,
            "primary_country" => $request->pri_country,
            "other_address" => $request->oth_address,
            "other_city" => $request->oth_city,
            "other_state" => $request->oth_state,
            "other_postal_code" => $request->oth_code,
            "other_country" => $request->oth_country,
            "description" => $request->description,
            "created_at" => Carbon::now(),
        );
        if ($request->file('profile')) {
            $profileImage = $request->file('profile');
            $fileName = time() . '.' . $profileImage->getClientOriginalName();
            $profileImage->move(public_path('assets/attachments'), $fileName);
            $contact['profile'] = $fileName;
        }
        if ($request->id) {
            DB::table('crm_contacts')->where('id', '=', $request->id)->update($contact);
        } else {
            $id = str::uuid()->toString();
            $contact['id'] = $id;
            DB::table('crm_contacts')->insert($contact);
        }
        return redirect($request->backURL);
        //return redirect()->back();
    }


    // public function delete($id)
    // {

    // }


    
}
