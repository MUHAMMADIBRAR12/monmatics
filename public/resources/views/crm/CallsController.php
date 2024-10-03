<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\appLib;
use Illuminate\Support\Facades\Auth;

class CallsController extends Controller
{
    public function index()
    {
        $date = Carbon::now();
        $calls = DB::table('crm_calls')
            ->where('crm_calls.created_at', '>=', $date->format('Y-m-d'))
            ->where('crm_calls.status', '=', 'New')
            ->where('crm_calls.assigned_to', '=', Auth::user()->id)
            ->get();

        $callsList = array();
        $table = appLib::$related_table;
        foreach ($calls as $call) {

            $field = ($call->related_to_type == 'customer') ? 'coa_id' : 'id';
            $call_arr = array(
                "id" => $call->id,
                "subject" => $call->subject,
                "start_date" => $call->start_date,
                "related_to" => ($call->related_to_type) ? DB::table($table[$call->related_to_type])->select('name')->where($field, $call->related_id)->get()->first() : '',
            );

            array_push($callsList, $call_arr);
        }

        return view('crm.calls_list', compact('callsList'));
    }

    public function getCallsList(Request $request)
    {
        $whereDateCluse = array();
    
        if ($request->from_date) {
            $arrFromDate = array('crm_calls.start_date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
    
        if ($request->to_date) {
            $arrToDate = array('crm_calls.end_date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
    
        if ($request->related_to_type && $request->related_to_type !== 'all') {
            $arrRelatedId = array('crm_calls.related_to_type', '=', $request->related_to_type);
            array_push($whereDateCluse, $arrRelatedId);
        }
    
        $calls = DB::table('crm_calls')
            ->select('crm_calls.*')
            ->where($whereDateCluse)
            ->get();
    
        $callsList1 = array();
        $table = appLib::$related_table;
    
        foreach ($calls as $call) {
            $field = ($call->related_to_type == 'customer') ? 'coa_id' : 'id';
    
            if ($call->related_to_type == 'all') {
                $related_to = ($call->related_to_type) ? DB::table($table[$call->related_to_type])->select('name')->where($field, $call->related_id)->get()->first() : '';
            } else {
                $related_to = 'All';
            }
    
            $call_arr1 = array(
                "id" => $call->id,
                "subject" => $call->subject,
                "start_date" => $call->start_date,
                "related_to" => $related_to,
            );
    
            array_push($callsList1, $call_arr1);
        }
    
        return $callsList1;
    }
    

    public function form($id = null, $relatedTo = null, $refId = null)
    {
        $backURL = redirect()->back()->getTargetUrl();
        $status = DB::table('sys_options')->select('description')->where('type', 'task_status')->where('status', 1)->get();
        $communication_types = DB::table('sys_options')->select('description')->where('type', 'calls_communication_type')->where('status', 1)->get();
    
        if ($id == 1 && $relatedTo && $refId) {
            if ($relatedTo == 'vendor') {
                $relatedToInfo = DB::table('pa_vendors')->select('id', 'name')->where('id', $refId)->first();
            } else {
                $idVal = ($relatedTo == 'customer' ? 'coa_id as id' : 'id');
                $relatedToInfo = DB::table('crm_customers')->select(DB::raw($idVal), 'name')->where('id', $refId)->first();
            }
            return view('crm.calls', compact('relatedTo', 'relatedToInfo', 'backURL', 'status', 'communication_types'));
        } elseif ($id) {
            $call = DB::table('crm_calls')
                ->leftjoin('users', 'users.id', 'crm_calls.assigned_to')
                ->leftjoin('crm_contacts', 'crm_contacts.id', 'crm_calls.contact_id')
                ->select('crm_calls.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"), DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"))
                ->where('crm_calls.id', $id)
                ->first();
        
            $relatedToInfo = null; 
        
            if ($call->related_to_type == 'customer') {
                $relatedToInfo = DB::table('crm_customers')
                    ->select('coa_id as id', 'name')
                    ->where('coa_id', $call->related_id)
                    ->first();
            } elseif ($call->related_to_type == 'lead') {
                $relatedToInfo = DB::table('crm_customers')
                    ->select('id', 'name')
                    ->where('id', $call->related_id)
                    ->first();
            }
        
            return view('crm.calls', compact('call', 'relatedToInfo', 'backURL', 'status', 'communication_types'));
        }
         else {
            return view('crm.calls', compact('backURL', 'status', 'communication_types'));
        }
    }
    
    public function save(Request $request)
    {
        
        if ($request->id) {
            $call = array(
                "id" => $request->id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date . ' ' . $request->s_hour . ':' . $request->s_minute,
                "end_date" => $request->end_date . ' ' . $request->e_hour . ':' . $request->e_minute,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "contact_id" => $request->contact_ID,
                "communication_type" => $request->communication_type,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "updated_at" => Carbon::now(),
            );
            DB::table('crm_calls')->where('id', $request->id)->update($call);
        } else {
            $id = str::uuid()->toString();
            $call = array(
                "id" => $id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date . ' ' . $request->s_hour . ':' . $request->s_minute,
                "end_date" => $request->end_date . ' ' . $request->e_hour . ':' . $request->e_minute,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "contact_id" => $request->contact_ID,
                "communication_type" => $request->communication_type,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            );
            DB::table('crm_calls')->insert($call);
        }
        return redirect($request->backURL);
    }
}
