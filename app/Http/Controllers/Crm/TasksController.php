<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Libraries\appLib;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function tasksList()
    {
        $date = Carbon::now();
        $status = DB::table('sys_options')->select('description')->where('type', 'task_status')->where('status', 1)->get();
        $tasks = DB::table('crm_tasks')
            ->leftjoin('users', 'users.id', '=', 'crm_tasks.assigned_to')
            ->select('crm_tasks.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            // ->where('crm_tasks.status', '=', 'New')
            // ->where('crm_tasks.assigned_to', '=', Auth::user()->id)
            ->get();

        return view('crm.tasks_list', compact('tasks', 'status'));
    }

    public function getTasksList(Request $request)
    {
        $whereDateCluse = array();
        $res = array();

        if ($request->from_date) {
            $arrFromDate = array('crm_tasks.start_date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('crm_tasks.due_date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }

        if ($request->status && $request->status !== "All") {
            $arrStatus = array('crm_tasks.status', '=', $request->status);
            array_push($whereDateCluse, $arrStatus);
        }

        $tasks = DB::table('crm_tasks')
            ->leftjoin('users', 'users.id', '=', 'crm_tasks.assigned_to')
            ->select('crm_tasks.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            ->where($whereDateCluse)
            ->get();

        foreach ($tasks as $task) {
            $row = array(
                'id' => $task->id,
                'subject' => $task->subject,
                'start_date' => date(appLib::showDateFormat(), strtotime($task->start_date)),
                'priority' => $task->priority,
                'status' => $task->status,
            );
            array_push($res, $row);
        }
        return $res;
    }



    public function tasks($id = null, $relatedTo = null, $refId = null)
    {
        $backURL = redirect()->back()->getTargetUrl();
        $status = DB::table('sys_options')->select('description')->where('type', 'task_status')->where('status', 1)->get();
        $priority = DB::table('sys_options')->select('description')->where('type', 'task_priority')->where('status', 1)->get();
        $task = '';
        if ($id == 1 && $relatedTo) {
            if ($relatedTo == 'vendor') {
                $relatedToInfo = DB::table('pa_vendors')->select('id', 'name')->where('id', '=', $refId)->first();
            } else {
                $idVal = ($relatedTo == 'customer' ? 'coa_id as id' : 'id');
                $relatedToInfo = DB::table('crm_customers')->select(DB::raw($idVal), 'name')->where('id', '=', $refId)->first();
            }
            return view('crm.tasks', compact('relatedTo', 'relatedToInfo', 'backURL', 'status', 'priority'));
        } elseif ($id) {
            $task = DB::table('crm_tasks')
                ->leftjoin('users', 'users.id', '=', 'crm_tasks.assigned_to')
                ->leftjoin('prj_projects', 'prj_projects.id', '=', 'crm_tasks.related_id')
                ->leftjoin('crm_contacts', 'crm_contacts.id', '=', 'crm_tasks.contact_id')
                ->leftjoin('crm_contacts as contact', 'contact.id', '=', 'crm_tasks.related_id')
                ->leftjoin('crm_customers', 'crm_customers.id', '=', 'crm_tasks.related_id')
                ->leftjoin('crm_customers as customer', 'customer.coa_id', '=', 'crm_tasks.related_id')


                ->select(
                    'crm_tasks.*',
                    DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),
                    DB::raw("concat(crm_contacts.first_name,' ',crm_contacts.last_name) as contact_name"),
                    'prj_projects.name as projectname',DB ::raw ( "concat(contact.first_name,'',contact.last_name) as contact_username" ),
                    'crm_customers.name as customer_name','customer.name as cusname'
                )
                ->where('crm_tasks.id', $id)
                ->first();



            $project = DB::table('prj_projects')->where('id', $id)->first();
            if($project){


                return view('crm.tasks', compact('project', 'backURL', 'status', 'priority'));
            }




            if ($task) {
                if ($task->related_to_type == 'customer') {
                    $relatedToInfo = DB::table('crm_customers')
                        ->select('coa_id as id', 'name')
                        ->where('coa_id', $task->related_id)
                        ->first();
                } elseif ($task->related_to_type == 'lead') {
                    $relatedToInfo = DB::table('crm_customers')
                        ->select('id', 'name')
                        ->where('id', $task->related_id)
                        ->first();
                }




                return view('crm.tasks', compact('project','task', 'backURL', 'status', 'priority'));
            } else {
            }
        } elseif ($id) {
            return view('crm.tasks', compact('status', 'priority', 'backURL'));
        }


        return view('crm.tasks', compact('task', 'status', 'priority', 'backURL'));
    }








    public function tasksSave(Request $request)
    {

        if ($request->id) {

            $task = [

                "id" => $request->id,
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
                "last_updated" => Carbon::now(),
            ];

            DB::table('crm_tasks')->where('id', $request->id)->update($task);
        } else {
            $id = str::uuid()->toString();
            $task = array(
                "id" => $id,
                "subject" => $request->subject,
                "status" => $request->status,
                "start_date" => $request->start_date,
                "due_date" => $request->due_date,
                "related_to_type" => $request->related_to_type,
                "related_id" => $request->related_ID,
                "contact_id" => $request->contact_ID,
                "priority" => $request->priority,
                "assigned_to" => $request->assign_ID ? $request->assign_ID : Auth::user()->id,
                "description" => $request->description,
                "created_date" => Carbon::now(),
            );
            DB::table('crm_tasks')->insert($task);
        }
        return redirect($request->backURL);
    }
}
