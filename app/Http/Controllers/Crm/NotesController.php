<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Libraries\appLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NotesController extends Controller
{
    public function index()
{
    $date = Carbon::now();
    $table = appLib::$related_table;
    $notesList = array();
    $notes = DB::table('crm_notes')
        ->where('crm_notes.created_at', '>=', $date->format('Y-m-d'))
        ->where('crm_notes.assigned_to', '=', Auth::user()->id)
        ->get();

    foreach ($notes as $note) {
        $field = ($note->related_to_type == 'customer') ? 'coa_id' : 'id';
        
        if ($note->related_to_type == 'contacts') {
            $contact = DB::table('crm_contacts')->select('first_name', 'id')->where('id', $note->related_id)->first();
            $related_to = ($contact) ? array('first_name' => $contact->first_name, 'id' => $contact->id) : '';
        } else {
            $related_to = ($note->related_to_type) ? DB::table($table[$note->related_to_type])->select('name', $field)->where($field, $note->related_id)->get()->first() : '';
        }
        
        $note_arr = array(
            "id" => $note->id,
            "subject" => $note->subject,
            "related_to" => $related_to,
            "description" => $note->description,
        );
        
        array_push($notesList, $note_arr);
    }
    
    return view('crm.notes_list', compact('notesList'));
}

    public function getNotesList(Request $request)
{
    $whereDateCluse = array();

    if ($request->from_date) {
        $arrFromDate = array('crm_notes.created_at', '>=', $request->from_date);
        array_push($whereDateCluse, $arrFromDate);
    }

    if ($request->to_date) {
        $arrToDate = array('crm_notes.created_at', '<=', $request->to_date);
        array_push($whereDateCluse, $arrToDate);
    }

    if ($request->related_to_type && $request->related_to_type !== 'all') {
        $arrRelatedId = array('crm_notes.related_to_type', '=', $request->related_to_type);
        array_push($whereDateCluse, $arrRelatedId);
    }

    $notes = DB::table('crm_notes')
        ->select('crm_notes.*')
        ->where($whereDateCluse)
        ->get();

    $res = array();
    $table = appLib::$related_table;

    foreach ($notes as $note) {
        $field = ($note->related_to_type == 'customer') ? 'coa_id' : 'id';
    
        if ($note->related_to_type == 'contacts') {
            $contact = DB::table('crm_contacts')->select('first_name', 'id')->where('id', $note->related_id)->first();
            $related_to = ($contact) ? array('first_name' => $contact->first_name, 'id' => $contact->id) : '';
        } elseif ($note->related_to_type !== 'all') {
            $table = appLib::$related_table[$note->related_to_type];
            $related_record = ($table) ? DB::table($table)->select('name', $field)->where($field, $note->related_id)->first() : null;
            $related_to = ($related_record) ? ['name' => $related_record->name, 'id' => $related_record->$field] : '';
        } else {
            $related_to = 'All';
        }
    
        $note_arr = array(
            "id" => $note->id,
            "subject" => $note->subject,
            "related_to" => $related_to,
            "description" => $note->description,
        );
    
        array_push($res, $note_arr);
    }
    
    


    return $res;
}

    

  
    public function form($id = null, $relatedTo = null, $refId=null)
    {
        $backURL = redirect()->back()->getTargetUrl();
        if ($id == 1  && $relatedTo) {        
            if($relatedTo=='contact')
            {
                $relatedToInfo = DB::table('crm_contacts')->select('id', 'name')->where('id','=', $refId)->first();
            }
            else
            {
                $idVal = ($relatedTo=='customer'?'coa_id as id':'id');
                $relatedToInfo = DB::table('crm_customers')->select(DB::raw($idVal), 'name')->where('id','=', $refId)->first();
            }
            return view('crm.note', compact('relatedTo', 'relatedToInfo', 'backURL'));
        } 
        
        elseif ($id) {
            $note = DB::table('crm_notes')
                ->leftjoin('users', 'users.id', 'crm_notes.assigned_to')
                ->select('crm_notes.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
                ->where('crm_notes.id', $id)
                ->first();

                $relatedToInfo = null;

           
                if ($note->related_to_type == 'customer') {
                    $relatedToInfo = DB::table('crm_customers')
                        ->select('coa_id as id', 'name')
                        ->where('coa_id', $note->related_id)
                        ->first();
                } elseif ($note->related_to_type == 'lead') {
                    $relatedToInfo = DB::table('crm_customers')
                        ->select('id', 'name')
                        ->where('id', $note->related_id)
                        ->first();
                }
                elseif ($note->related_to_type == 'contact') {
                    $relatedToInfo = DB::table('crm_contacts')
                        ->select('id', 'name')
                        ->where('id', $note->related_id)
                        ->first();
                }
                
            
                return view('crm.note', compact('note', 'backURL','relatedToInfo'));
    } 
    else
        return view('crm.note', compact( 'backURL'));
    }



    public function save(Request $request)
    {
        if ($request->id) {
            $note = array(
                "id" => $request->id,
                "subject" => $request->subject,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "updated_at" => Carbon::now(),
            );
            DB::table('crm_notes')->where('id', $request->id)->update($note);
        } else {
            $id = str::uuid()->toString();
            $note = array(
                "id" => $id,
                "subject" => $request->subject,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            );
            DB::table('crm_notes')->insert($note);
        }

        return redirect($request->backURL);
    }

    public function view($id)
    {
        $note = DB::table('crm_notes')
            ->leftjoin('users', 'users.id', 'crm_notes.assigned_to')
            ->select('crm_notes.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            ->where('crm_notes.id', $id)
            ->first();
        $related_table = array(
            "customer" => "crm_customers",
        );
        $related_field = array(
            "customer" => 'coa_id',
            "lead" => 'id',
        );
        $related_data = DB::table($related_table[$note->related_to_type])->select('name', $related_field[$note->related_to_type])->where('coa_id', $note->related_id)->get()->first();
        return view('crm.note_view', compact('note', 'related_data'));
    }
}
