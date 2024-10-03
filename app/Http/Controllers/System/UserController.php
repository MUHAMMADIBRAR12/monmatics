<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Libraries\dbLib;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function home(){
        
        return view('home.home');
    }



    public function email_verification(Request $request)
    {
        $email = $request->email;

        $email = DB::table('users')->where('email', $email)->first();
        if ($email) {
            return "This Email is Already Exist";
        } else {
            return "";
        }
    }


    public function index()
    {
        $userList = DB::table('users')
            ->select('users.*')
            ->where('is_deleted', 0)
            ->get();

        return view('system.userList', compact('userList'));
    }
    public function form($id = null, $profile = null)
    {
        $roleName = DB::table('sys_roles')->select('name')->get()->unique('name');
        $modules = DB::table('sys_modules')->select(array('module', 'id', 'status'))->where('mdl_id', -1)->where('status', 1)->get();

        $companies = DB::table('sys_companies')->select('id', 'name')->get();
        if ($id) {
            $userData = DB::table('users')->select('id', 'firstName', 'lastName', 'name', 'email', 'role', 'profile', 'status')->where('id', $id)->get()->first();
            $user_companies = DB::table('sys_companies')
                ->leftJoin('user_companies', function ($join) use ($id) {
                    $join->on('user_companies.company_id', '=', 'sys_companies.id');
                    $join->on('user_companies.user_id', '=', DB::raw("$id"));
                })
                ->select('sys_companies.id', 'sys_companies.name', 'user_companies.company_id')
                ->get();
                // $modules = DB::table('sys_modules')->select(array('module', 'id', 'status'))->where('mdl_id', -1)->where('status', 1)->get();
                // $modules = DB::table('sys_roles')
                // ->join('sys_modules','sys_modules.id', 'sys_roles.mdl_id')
                // ->select('sys_roles.*','sys_modules.module')
                // ->where('sys_roles.name',Auth::user()->role)->get();

                // dd($modules);

                $modules = DB::table('sys_roles')
                ->join('sys_modules', 'sys_modules.id', 'sys_roles.mdl_id')
                ->select(
                    'sys_roles.mdl_id',
                    'sys_modules.module',
                    DB::raw('MAX(sys_roles.id) as id'),
                    DB::raw('MAX(sys_roles.name) as name')
                )
                ->where('sys_roles.name', Auth::user()->role)
                ->groupBy('sys_roles.mdl_id', 'sys_modules.module')
                ->get();

            // dd($modules);



            return view('system.userInformation', compact('modules','roleName', 'userData', 'profile', 'companies', 'user_companies'));
        } else {
            return view('system.userInformation', compact('roleName', 'companies','modules'));
        }
    }

    public function save(Request $request)
    {


        $request->validate([
            'fname' => [
                'required',
                'string',
                'regex:/^[A-Za-z\s]+$/u', // Allow only letters and spaces
                'min:3',
                'max:10',
            ],
            'email' => 'string|email|max:255|',
            // Add validation rules for other fields if needed
        ]);



        if ($request->id) {
            // $id = Str::uuid()->toString();
            $user = array(
                // 'id' => $id,
                'firstName' => $request->fname,
                'lastName' => $request->lname,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'route' => $request->route,
                "updated_at" => Carbon::now(),
            );
            if ($request->password) {
                $password = Hash::make($request->password);
                $user['password'] = $password;
            }

            DB::table('users')->where('id', $request->id)->update($user);
            $user_id = $request->id;
        } else {
            // $id = Str::uuid()->toString();
            $password = Hash::make($request->password);
            $user = array(
                // 'id' => $id,
                'firstName' => $request->fname,
                'lastName' => $request->lname,
                'email' => $request->email,
                'password' => $password,
                'role' => $request->role,
                'status' => $request->status,
                'route' => $request->route,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            );
            $user_id = DB::table('users')->insertGetId($user);
            $databaseName = 'monmatics_multitenant';
            $tableName = 'users';

            $connectionConfig = [
                'driver' => env('DB_CONNECTION'),
                'host' => env('DB_HOST'),
                'port' => env('DB_PORT'),
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
            ];

            config(['database.connections.'.$databaseName => $connectionConfig]);
            DB::purge($databaseName);

            $insertedId = DB::connection($databaseName)->table($tableName)->insertGetId([
                'email' => $request->email,
                'db_name' => session('db_name'),
                'db_username' => session('db_username'),
                'db_password' => session('db_password'),
            ]);

            DB::table('user_companies')->insert([
                'user_id' => $insertedId,
                'company_id' => session('companyId'),
            ]);

            // Clear the database connection configuration
            config(['database.connections.'.$databaseName => null]);
            DB::purge($databaseName);

        }

        if ($request->profile) {
            DB::table('sys_attachments')->where('source_id', $user_id)->delete();
            $this->uploadProfilePic($user_id, $request->profile);
        }

        DB::table('user_companies')->where('user_id', $user_id)->delete();

        if ($request->company_id) {
            $count = count($request->company_id);
            for ($i = 0; $i < $count; $i++) {
                $user_company = array(
                    "user_id" => $user_id,
                    "company_id" => $request->company_id[$i],
                );
                DB::table('user_companies')->insert($user_company);
            }
        }
        return redirect('Admin/Users/List');
    }

    public function hide($id)
    {
        $user = array(
            'is_deleted' => 1,
        );
        DB::table('users')->where('id', $id)->update($user);
        return back()->with('msg', 'Record Deleted successfully');
    }

    public function user_prof(Request $request, $profile = null)
    {
        if ($profile) {
            if ($request->password) {
                $password = Hash::make($request->password);
                DB::table('users')->where('id', $request->id)->update(['password' => $password]);
            }

            if($request->route){
                DB::table('users')->where('id', $request->id)->update(['route' => $request->route]);
            }
            if ($request->file('profile')) {
                $this->uploadProfilePic($request->id, $request->file('profile'));
            }
            return back();
        }
    }

    private  function  uploadProfilePic($user_id, $profilePic)
    {
        DB::table('sys_attachments')->where('source_id','=',$user_id)->delete();;
        dbLib::uploadDocument($user_id, $profilePic);
    }
}
