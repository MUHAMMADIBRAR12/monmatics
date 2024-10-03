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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{



    private function dbconn( $database,$username, $password)
    {
        // $database ='monmatics_development';
        // $username = 'root';
        // $password = '';

        $dbConnection = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => $database, // Assuming db_name is a field in the User model
            'username' => $username, // Assuming db_username is a field in the User model
            'password' => $password, // Assuming db_password is a field in the User model
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];

        Config::set('database.connections.APICONN', $dbConnection);
        config(['database.default' => 'APICONN']);
    }


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
        'email' => 'required',
        'password' => 'required',
    ]);

    // Fetch user from the main database
    $user = User::where('email', $request->email)->first();


    if (!$user) {
        $data = [
            'status' => 0,
            'result' => false,
            'message' => 'User not found'
        ];   return response()->json($data, 404);
    }


    // Connect to the user's specific database
    $dbConnection = [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => $user->db_name, // Assuming db_name is a field in the User model
        'username' => $user->db_username, // Assuming db_username is a field in the User model
        'password' => $user->db_password, // Assuming db_password is a field in the User model
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ];
    Config::set('database.connections.custom_connection', $dbConnection);
    // Establish a database connection to the user's specific database
    $database = DB::connection('custom_connection');

    // Fetch user data from the user's specific database
    $userData = $database->table('users')->where('email', $request->email)->first();

    if (!$userData || !Hash::check($request->password, $userData->password)) {
        $data = [
            'status' => 0,
            'result' => false,
            'message' => 'Invalid Credentials'
        ];
        return response()->json($data, 404);
    }



    // Generate token
    // $plainTextToken = $user->createToken('app')->plainTextToken;

    // Save the token to the user model or use Laravel Sanctum to generate tokens

    $response = [
        'status' => 200,
        'result' => true,
        'message' => 'Login Successfully',
        'user' => $userData, // Returning user data from the specific database

        'database_info' => [
            'db_name' => $user->db_name,
            'db_username' => $user->db_username,
            'db_password' => $user->db_password,
        ]

        // 'token' => $plainTextToken
    ];
    return response($response, 200);
}

public function getUsers(Request $request)
{
    $this->dbconn($request->db_name, $request->db_username, $request->db_password);

    $users=DB::table('users')
    ->select('id','parent_id','email','email_verified_at','created_at','updated_at','ntn','role','firstName','lastName','profile','status','route')
    ->get();

    if ($users->isEmpty()) {
        return response()->json([
            'status' => 404,
            'message' => 'No users found',
        ]);
    }

    return response()->json([
        'status' => 200,
        'data' => $users,
    ]);
}

   public function  saveOrUpdateNote(Request $request)
    {
     $this->dbconn($request->db_name, $request->db_username, $request->db_password);
     // check if id exisit
     $existingRecord = DB::table('crm_notes')->where('id', $request->id)->first();
        $companyId = session('companyId');
        // 
        $data = [
            'id' => $request->id,
            'subject' => $request->subject,
            'related_to_type' => $request->related_to_type,
            'related_id' => $request->related_ID,
            'assigned_to' => $request->assign_ID,
            'description' => $request->description,
            // "company_id" => $companyId,
            // 'created_at' => $request->created_at,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
      if($existingRecord){
        $res = DB::table('crm_notes')->where('id', '=', $request->id)->update($data);
        $message= $res ? 'Note updated successfully' : 'update failed';
      }else{        
        
        $res = DB::table('crm_notes')->insert($data);
        $message = $res ? 'Note saved successfully': 'Save Failed';
      }


            return response()->json([
                'status' => $res ? 201 : 404,
                'message' => $message,
            ]);
        
    }

    public function getNotes(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        // Check if the request includes a valid bearer token
                if ($request->has('id')) {
                    $userId = $request->input('id');

                    // Check if the user exists in your database
                    $user = User::find($userId);

                         if (!$user) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'User not found',
                        ]);
                    }
                   // Establish custom database connection

                 //  Fetch notes for the authenticated user using the custom connection
                    $notes = DB::table('crm_notes')
                        ->select('id', 'subject', 'related_to_type', 'related_id', 'assigned_to', 'description')
                        ->where('assigned_to', $userId)
                        ->get();

                    // Check if notes are found for the user
                    if ($notes->isEmpty()) {
                        return response()->json([
                            'status' => 404,
                            'message' => 'No notes found for the logged-in user',
                        ]);
                    }

                    // Return notes if found
                    return response()->json([
                        'status' => 200,
                        'data' => $notes,
                    ]);
                } else {
                    // Return 401 status if token is invalid or expired
                    return response()->json([
                        'status' => 401,
                        'message' => 'Invalid or expired token',
                    ]);
                }
            }

            // } else {
            //     // Return 401 status if token is not provided
            //     return response()->json([
            //         'status' => 401,
            //         'message' => 'Token not provided',
            //     ]);
            public function deleteNotes(Request $request)
            {
                $this->dbconn($request->db_name, $request->db_username, $request->db_password);
            
                // Check if the note exists
                $note = DB::table('crm_notes')->find($request->id);
                if (!$note) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Note not found',
                    ]);
                }
                // Delete the note
                $res = DB::table('crm_notes')->where('id', $request->id)->delete();
                // $res = DB::table('crm_notes')->where('id', $request->id)->update(['deleted_at' => now()]);

                if ($res) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Note deleted successfully',
                    ]);
                } else {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Failed to delete note',
                    ]);
                }
            }
            
        // public function updateNotes(Request $request){
        //         $this->dbconn($request->db_name, $request->db_username, $request->db_password);

        //         try {
        //             $note = DB::table('crm_notes')->find($request->id); // Corrected spelling of $request
        //             if (!$note) { // Added a missing $ sign before "note"
        //                 return response()->json([
        //                     'status' => 404,
        //                     'message' => 'Note not found',
        //                 ]);
                        
        //             } else {
        //                 DB::beginTransaction(); // Corrected typo in method name
            
        //                 DB::table('crm_notes')->where('id', $request->id) // Added where condition to specify which note to update
        //                     ->update([
        //                         'subject' => $request->subject,
        //                         'related_to_type' => $request->related_to_type,
        //                         // 'related_id' => $request->related_ID,
        //                         // 'assigned_to' => $request->assign_ID,
        //                         'description' => $request->description,
        //                     ]);
            
        //                 DB::commit(); // Moved commit inside the else block
        //             }
        //         } catch (\Exception $e) {
        //             DB::rollback();
        //             // Optionally handle the exception or log it 
        //             $note([
        //                 'status' => 0,
        //                 'result' => false,
        //                 'message' => $e->getMessage()
        //             ]);
        //         }
        //     }
            

    public function saveorUpdateTask(Request $request, $id=null)
    {
        
       
        

        try {
            
            // $request->validate([
            //     'subject' => 'required',
            //     // 'status' => 'required',
            //     // 'related_to_type' => 'required',
            //     // 'related_id' => 'required',
            //     // 'contact_id' => 'required',
            //     // 'priority' => 'required',
            //     // 'assigned_to' => 'required',
            //     // 'description' => 'required',

            // ]);
            $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        
            $existingRecord = DB::table('crm_tasks')->where('id', $request->id)->first();

           // $companyId = session('companyId');
            // $id = str::uuid()->toString();
            $data = [
                "id" => $request->id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date,
                "due_date" => $request->due_date,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_id,
                "contact_id" => $request->contact_id,
                "priority" => $request->priority,
                "assigned_to" => $request->assigned_to,
                "description" => $request->description,
                "created_by" => $request->created_by,
               // "company_id" => $companyId,
                "created_date" => Carbon::now(),
            ];
           
            
            if ( $existingRecord){
               
                $res = DB::table('crm_tasks')->where('id','=',$request->id)->update($data);                
                $message = $res ? 'Task updated successfully' : 'save failed';
                }
            else{
                    // $id = str::uuid()->toString();
                    // $data['id'] = $id;
                    $res = DB::table('crm_tasks')->insert($data);
                    $message = $res ? 'Task saved successfully' : 'save failed';
                }

                return response()->json([
                    'status'=> $res ? 201 : 404,
                    'message'=> $message ,
                ]);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'result' => false,
                'message' => 'dd' . $e->getMessage()
            ];
        }
    }

    public function getTasks(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $userId = $request->userId;
        $tasks = DB::table('crm_tasks')
            ->select('id', 'subject', 'start_date','due_date','status','related_to_type','related_id','contact_id', 'priority',  'description','assigned_to')
            ->where('created_by','=',$userId)
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

    public function deleteTasks(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
    
        // Check if the note exists
        $task = DB::table('crm_tasks')->find($request->id);
        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ]);
        }
        // Delete the note
        $res = DB::table('crm_tasks')->where('id', $request->id)->delete();
        // $res = DB::table('crm_tasks')->where('id', $request->id)->update(['deleted_at' => now()]);

        if ($res) {
            return response()->json([
                'status' => 200,
                'message' => 'Task deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete task',
            ]);
        }
    }

    public function saveOrUpdateLeads(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $existingRecord = DB::table('crm_customers')->where('id', $request->id)->first();
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
                "id" => $request->id,
                "name" => $request->name,
                "category" => $request->category,
                "note" => $request->note,
                "assigned_to" => $request->assigned_to,
                "lead" => 1,
                "lead_source" => $request->lead_source,
                "created_by" => $userId,
                "status" => $request->status,
                "type" => $request->type,

            );

            $custId = $request->id;
            // $data['id'] = $custId;
            $data['company_id'] = $companyId;

            if($existingRecord){
                $res = DB::table('crm_customers')->where('id','=',$request->id)->update($data);
                $message = $res ? 'Lead updated successfully' : 'update failed';
            }else{
                $res = DB::table('crm_customers')->insert($data);
                $message = $res ? 'Lead saved successfully' : 'save failed';
            } 

            $custId  = $request->id;  // recently store/ updated customer id
            // dd($custId);
            $res1 = DB::table('crm_customers_address')->where('cust_id', '=', $custId)->delete();
          //  dd($res1);
            //$count = count($request->phone) ? count($request->phone) : 0;
          // dd($count);
          if($request->phone)            
          {
                $dataAddress = array(
                    "id" => str::uuid()->toString(),
                    "cust_id" => $custId,
                    "address" => $request->address,
                    "phone" => $request->phone,
                    "email" => $request->email,
                    "fax" => $request->fax,
                    "status" => 1,
                );
            //
                $res1 = DB::table('crm_customers_address')->insert($dataAddress);
            }
           
            return response()->json([
                'status'=> $res ? 201 : 404,
                'message'=> $message ,
            ]);
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
        $userId = $request->userId;
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $leads = DB::table('crm_customers')
        ->leftJoin('crm_customers_address', 'crm_customers.id', '=', 'crm_customers_address.cust_id')
        //->select('crm_customers.*', 'crm_customers_address.*')
        ->select('crm_customers.id', 'crm_customers.name', 'crm_customers.assigned_to','crm_customers.created_by','crm_customers.category','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.address','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.email','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.phone')
        ->where('crm_customers.lead', '=', 1)
        ->where('crm_customers.created_by', '=', $userId)
        ->get();

        if ($leads->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Lead found',
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $leads,
        ]);
    }


    public function saveOrUpdateCalls(Request $request)
    {

        $this->dbconn($request->db_name, $request->db_username, $request->db_password);

        $existingRecord = DB::table('crm_calls')->where('id', $request->id)->first();

        try {

            $request->validate([
                // 'subject' => 'required',
                // 'status' => 'required',
                // 'start_date' => 'required',
                // 'end_date' => 'required',
                // 'related_to_type' => 'required',
                // 'related_id' => 'required',
                // 'contact_id' => 'required',
                // 'communication_type' => 'required',
                // 'assigned_to' => 'required',
                // 'description' => 'required',

            ]);

            // $id = str::uuid()->toString();

      //      $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d h:i:s');
      //      $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d h:i:s');

            //$start_time = sprintf('%02d:%02d', $request->s_hour[0], $request->s_minute[0]);
            //dd($start_time);
           // $end_time = sprintf('%02d:%02d', $request->e_hour[0], $request->e_minute[0]);

         //  $companyId = session('companyId');
            $userId = Auth::id();
            $data = [
                "id" => $request->id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date ,
                "end_date" => $request->end_date ,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_id,
                "contact_id" => $request->contact_id,
                "communication_type" => $request->communication_type,
                "assigned_to" => $request->assigned_to ? $request->assigned_to : (Auth::check() ? Auth::user()->id : null),
                "created_by" => $request->created_by,
                "description" => $request->description,
                // "company_id" => $companyId,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];

            if ( $existingRecord){
                
                $res = DB::table('crm_calls')->where('id','=',$request->id)->update($data);
                $message = $res ? 'Call updated successfully' : 'save failed';
                }
            else{
                    // $id = str::uuid()->toString();
                    // $data['id'] = $id;
                    $res = DB::table('crm_calls')->insert($data);
                    $message = $res ? 'call saved successfully' : 'save failed';
                }

                return response()->json([
                    'status'=> $res ? 201 : 500,
                    'message'=> $message ,
                ]);
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
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $userId = $request->userId;
        $calls = DB::table('crm_calls')
            ->select( 'subject', 'start_date','end_date', 'status',  'related_to_type','contact_id','description','related_id','communication_type','assigned_to')
            ->where('created_by','=',$userId)
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

    public function deleteCalls(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
    
        // Check if the note exists
        $call = DB::table('crm_calls')->find($request->id);
        if (!$call) {
            return response()->json([
                'status' => 404,
                'message' => 'Call not found',
            ]);
        }
        // Delete the note
        $res = DB::table('crm_calls')->where('id', $request->id)->delete();
        // $res = DB::table('crm_tasks')->where('id', $request->id)->update(['deleted_at' => now()]);

        if ($res) {
            return response()->json([
                'status' => 200,
                'message' => 'Call deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to delete call',
            ]);
        }
    }


    public function saveOrUpdateContacts(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);

        $existingRecord = DB::table('crm_contacts')->where('id', $request->id)->first();

        try{

            $userId = Auth::id();
           // $companyId = session('companyId');
            $contact = array(
                "id" => $request->id,
                "mr" => $request->type,
                "first_name" => $request->fname,
                "last_name" => $request->lname,
                "title" => $request->title,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "assigned_to" => $request->assigned_to ? $request->assigned_to : (Auth::check() ? Auth::user()->id : null),
                "created_by" => $request->created_by,
                "mobile" => $request->mobile,
                "email" => $request->email,
                "phone_office" => $request->phone_office,
                "primary_address"=>$request->address,
                "primary_city"=>$request->city,
                "primary_state"=>$request->state,
                "primary_postal_code"=>$request->postal_code,
                "primary_country"=>$request->country,
                "description"=>$request->description,
            //    "company_id" => $companyId,
                 "user_id" => $userId,
                "created_at" => Carbon::now(),
            );

            // $id = str::uuid()->toString();
            // $contact['id'] = $id;

            if ($existingRecord) {
              $res = DB::table('crm_contacts')->where('id','=',$request->id)->update($contact);
                $message= $res ? 'Contact Updated' : 'update failed';
            }else{
               $res = DB::table('crm_contacts')->insert($contact);
                    $message = $res ? 'Contact saved successfully' : 'save failed';
            }
            
                return response()->json([
                    'status' => $res ? 201 : 404,
                    'message' => $message,
                ]);

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
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $userId = $request->userId;
        $contacts = DB::table('crm_contacts')
            ->select('id', 'first_name', 'last_name', 'mobile',  'email')
            ->where('created_by','=',$userId)
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
    public function saveOrUpdateCustomers(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
       $existingRecord = DB::table('crm_customers')->where('id',$request->id)->first();
        // $userId = Auth::id();
         $companyId = session('companyId');
        //$id = str::uuid()->toString();
        $customers = array(
            // "mr" => $request->type,
             "id" => $request->id,
            "name" => $request->name,
            "category" => $request->category,
            "tax_number" => $request->tax_number ,
            "note" => $request->note,
            "type" => $request->type,
            "status" => $request->status,
            "assigned_to" => $request->assigned_to,
            "credit_limit" => $request->credit_limit,
            "credit_amount" => $request->credit_amount,
            "margin" => $request->margin,
            "lead" => $request->lead,
            "lead_source" => $request->lead_source,
           "company_id" => $companyId,
           "created_by" => $request->created_by,
            "created_at" => Carbon::now(),
            "deleted_at" => Carbon::now(),
            // "updated_at" => Carbon::now(),

        );

         if ( $existingRecord){
                
                $res = DB::table('crm_customers')->where('id','=',$request->id)->update($customers);
                $message = $res ? 'customer updated successfully' : 'customer failed';
                }
            else{
                    // $id = str::uuid()->toString();
                    // $data['id'] = $id;
                    $res = DB::table('crm_customers')->insert($customers);
                    $message = $res ? 'customer saved successfully' : 'save failed';
                }

                return response()->json([
                    'status'=> $res ? 201 : 404,
                    'message'=> $message ,
              ]);
        // if ($res) {
        //     return response()->json([
        //         'status' => 201,
        //         'message' => 'Customer saved successfully'
        //     ]);
        // }else{
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Customer save failed'
        //     ]);
         }
 
    

    public function getCustomers(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password); 

        $userId = $request->userId;
        
        $customers = DB::table('crm_customers')
            ->leftJoin('crm_customers_address', 'crm_customers.id', '=', 'crm_customers_address.cust_id')
            //->select('crm_customers.*', 'crm_customers_address.*')
            ->select('crm_customers.id', 'crm_customers.name', 'crm_customers.assigned_to','crm_customers.created_by','crm_customers.category','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.address','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.email','crm_customers.lead_source','crm_customers.status','crm_customers.type','crm_customers.note','crm_customers.lead','crm_customers_address.phone')
            ->where('created_by', '=', $userId)
            ->get();
    
        if ($customers->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No customers found',
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $customers,
        ]);
    }

     public function getCompanyid(Request $request)
    {
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        $userId = Auth::id();       
        $companyId = session('companyId');
        return response()->json([
            'status' => 200,
            'companyd' => $companyId,
            'userid' => $userId,
        ]);


         
    }
    public function saveOrUpdateOpportunity(Request $request){
        $this->dbconn($request->db_name, $request->db_username, $request->db_password);
        // $id = str::uuid()->toString();
        $existingRecord = DB::table('crm_opportunities')->where('id',$request->id)->first();
        $data = [
            "id" => $request->id,
            "name" => $request->name,
            "cust_id" => $request->cust_id,
            "cur_name" => $request->cur_name,
            "close_date" =>Carbon::now(),
            "amount" => $request->amount ,
            "lead_type" => $request->lead_type,
            "sale_stage" => '001',
            "lead_source" => $request->lead_source,
            "compaign_id" => $request->compaign_id,
            "next_step" => $request->next_step,
            "assigned_to" => $request->assign_ID ? $request->assign_ID : (Auth::check() ? Auth::user()->id : null),
            "description" => $request->description,
            // "company_id" => $companyId,
            "created_by"=>$request->created_by,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
         ];
        if($existingRecord){
             $res = DB::table('crm_opportunities')->where('id','=',$request->id)->update($data);
             $message = $res ? 'Opptunity updated successfully' : 'update failed';
            }else{
                $res = DB::table('crm_opportunities')->insert($data);
                $message = $res ? 'Opptunity saved successfully' : 'save failed';
            }
               return response()->json([
                'status' => $res ? 201:404,
                'message' => $message,
               ]);
}
  public function getOpportunity(Request $request){
  $userId = $request->userId;
    $this->dbconn($request->db_name, $request->db_username, $request->db_password);
   
    $res = DB::table('crm_opportunities')
   ->where('created_by', '=', $userId)
   ->get();
   if($res->isEmpty()){
      return response()->json([
        'status' => 404,
        'message' => 'No Opportunity found'
      ]);
   }

   return response()->json([
    'status' => 200,
    'message' => $res,
   ]);
  }
}