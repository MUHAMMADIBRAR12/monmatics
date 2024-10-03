<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Libraries\dbLib;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',

        ];
        // Validate input data
        $validator = Validator::make($request->all(), $rules);

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = [
                'status' => 0,
                'result' => false,
                'message' => $validator->errors()->all(),
            ];
            return response($response, 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,

        ]);

        $token = $user->createToken('token-name')->plainTextToken;

        if ($user) {
            $response = [
                'status' => 200,
                'result' => true,
                'message' => 'User created successfully',
                'user' => $user,
            ];
            return response($response, 200);
        } else {
            $response = [
                'status' => 0,
                'result' => false,
                'message' => 'Something went wrong',
            ];
            return response($response, 404);
        }

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $data = [
                'status' => 0,
                'result' => false,
                'message' => 'Invalid Credentials'
            ];
            return response($data, 404);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        $response = [
            'status' => 200,
            'result' => true,
            'message' => 'Login Successfully',
            'user' => $user,
            'token' => $token
        ];
        return response($response, 200);
    }
    public function saveNotes(Request $request)
    {
        $id = str::uuid()->toString();
        $data = [
            'id' => $id,
            'subject' => $request->subject,
            'related_to_type' => $request->related_to_type,
            'related_id' => $request->related_ID,
            'assigned_to' => $request->assign_ID,
            'description' => $request->description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),


        ];

        $res = DB::table('crm_notes')->insert($data);
        if ($res) {
            return response()->json([
                'status' => 201,
                'message' => 'Note saved successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Save Failed',
            ]);
        }
    }


    public function getNotes(Request $request)
    {


        if (auth()->check()) {
            $userId = auth()->user()->id;
         
            $notes = DB::table('crm_notes')
            ->select('id', 'subject', 'related_to_type', 'related_id', 'assigned_to', 'description')
            ->where('assigned_to', $userId)
            ->get();
    
        if ($notes->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No notes found for the logged-in user',
            ]);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $notes,
        ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'User is not authenticated',
            ]);
        }


       
    }
    
    

    public function saveTasks(Request $request)
    {
        try {

            $request->validate([
                'subject' => 'required',
                'status' => 'required',
                'related_to_type' => 'required',
                'related_id' => 'required',
                'contact_id' => 'required',
                'priority' => 'required',
                'assigned_to' => 'required',
                'description' => 'required',

            ]);
            $id = str::uuid()->toString();
            $data = [
                "id" => $id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date,
                "due_date" => $request->due_date,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "contact_id" => $request->contact_ID,
                "priority" => $request->priority,
                "assigned_to" => $request->assign_ID,
                "description" => $request->description,
                "created_date" => Carbon::now(),

            ];

            $res = DB::table('crm_tasks')->insert($data);
            if ($res) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Task saved successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Task Save Failed',
                ]);
            }
        } catch (Exception $e) {
            $data = [
                'status' => 0,
                'result' => false,
                'message' => $e->getMessage()
            ];
            return response($data, 404);
        }
    }

    public function getTasks(Request $request)
    {
        $tasks = DB::table('crm_tasks')
            ->select('id', 'subject', 'start_date', 'priority',  'description','assigned_to')
            ->get();
    
        if ($tasks->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No tasks found',
            ]);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $tasks,
        ]);
    }

    public function saveLeads(Request $request)
    {
        try {

            $request->validate([
                // 'subject' => 'required',
                // 'status' => 'required',
                // 'related_to_type' => 'required',
                // 'related_id' => 'required',
                // // 'contact_id' => 'required',
                // 'priority' => 'required',
                // 'assigned_to' => 'required',
                // 'description' => 'required',

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

            $custId = str::uuid()->toString();
            $data['id'] = $custId;
            $data['company_id'] = $companyId;


            $res = DB::table('crm_customers')->insert($data);
            $res1 = DB::table('crm_customers_address')->where('cust_id', '=', $custId)->delete();
            $count = is_array($request->phone) ? count($request->phone) : 0;

            for ($i = 0; $i < $count; $i++) {
                $dataAddress = array(
                    "id" => str::uuid()->toString(),
                    "cust_id" => $custId,
                    "address" => $request->address[$i],
                    "phone" => $request->phone[$i],
                    "email" => $request->email[$i],
                    "fax" => $request->fax[$i],
                    "status" => 1,
                );
                $res1 = DB::table('crm_customers_address')->insert($dataAddress);
            }
            if ($res) {
                return response()->json([
                    'status' => 201,
                    'message' => '  Lead saved successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Saving Lead Failed',
                ]);
            }
        } catch (Exception $e) {
            $data = [
                'status' => 0,
                'result' => false,
                'message' => $e->getMessage()
            ];
            return response($data, 404);
        }
    }


    public function getLeads(Request $request)
    {
        $calls = DB::table('crm_customers')
        ->leftJoin('crm_customers_address','crm_customers_address.')
            ->select( 'id','name', 'category', 'note',  'related_to_type','contact_id')
            ->get();
    
        if ($calls->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Calls found',
            ]);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $calls,
        ]);
    }


    public function saveCalls(Request $request)
    {
        try {

            $request->validate([
                'subject' => 'required',
                'status' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'related_to_type' => 'required',
                'related_id' => 'required',
                'contact_id' => 'required',
                'communication_type' => 'required',
                'assigned_to' => 'required',
                'description' => 'required',

            ]);

            $id = str::uuid()->toString();

            $start_date = Carbon::createFromFormat('d-M-Y', $request->start_date)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d-M-Y', $request->end_date)->format('Y-m-d');

            $start_time = sprintf('%02d:%02d', $request->s_hour, $request->s_minute);
            $end_time = sprintf('%02d:%02d', $request->e_hour, $request->e_minute);

            $data = [
                "id" => $id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $start_date . ' ' . $start_time,
                "end_date" => $end_date . ' ' . $end_time,
                "related_to_type" => $request->related_to_type,
                "related_id" => '001',
                "contact_id" => $request->contact_ID,
                "communication_type" => $request->communication_type,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : (Auth::check() ? Auth::user()->id : null),
                "description" => $request->description,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];

            $res = DB::table('crm_calls')->insert($data);
            if ($res) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Call saved successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Call Save Failed',
                ]);
            }
        } catch (Exception $e) {
            $data = [
                'status' => 0,
                'result' => false,
                'message' => $e->getMessage()
            ];
            return response($data, 404);
        }
    }

    public function getCalls(Request $request)
    {
        $calls = DB::table('crm_calls')
            ->select( 'subject', 'start_date', 'status',  'related_to_type','contact_id')
            ->get();
    
        if ($calls->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Calls found',
            ]);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $calls,
        ]);
    }

    public function saveContacts(Request $request)
    {
        try{

            $userId = Auth::id();
            $companyId = session('companyId');
            $contact = array(
                "mr" => $request->type,
                "first_name" => $request->fname,
                "last_name" => $request->lname,
                "title" => $request->title,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : (Auth::check() ? Auth::user()->id : null),
                "mobile" => $request->mobile,
                "email" => $request->email,
                "phone_office" => $request->office_phone,
                "company_id" => $companyId,
                "user_id" => $userId,
                "created_at" => Carbon::now(),
            );
            $id = str::uuid()->toString();
            $contact['id'] = $id;
            $res =  DB::table('crm_contacts')->insert($contact);
            if ($res) {
                return response()->json([
                    'status' => 201,
                    'message' => 'Contact saved successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Contact Save Failed',
                ]);
            }
  
        }catch(Exception $e){
            $data = [
                'status' => 0,
                'result' => false,
                'message' => $e->getMessage()
            ]; 
            return response($data, 404); 
        }
 
        return redirect($request->backURL);
        //return redirect()->back();
    }

    public function getContacts(Request $request)
    {
        $contacts = DB::table('crm_contacts')
            ->select('id', 'first_name', 'last_name', 'mobile',  'email')
            ->get();
    
        if ($contacts->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No contacts found',
            ]);
        }
    
        return response()->json([
            'status' => 200,
            'data' => $contacts,
        ]);
    }

}
