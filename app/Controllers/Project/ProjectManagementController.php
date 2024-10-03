<?php

namespace App\Http\Controllers\project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Session;

class ProjectManagementController extends Controller
{
    public function index()
    {

        $projects = DB::table('prj_projects')->get();
        return view('project.project_list', compact('projects'));
    }

    public function form($id = null)
    {
        $parent_projects = DB::table('prj_projects')->select('id', 'name')->get();
        $project_category = Db::table('sys_options')->where('type', 'project_category')->get();
        if ($id) {

            $project = DB::table('prj_projects')
                ->leftjoin('users', 'users.id', '=', 'prj_projects.project_manager')
                ->leftjoin('crm_customers', 'crm_customers.coa_id', '=', 'prj_projects.cust_coa_id')
                ->select('prj_projects.*', 'crm_customers.name as cust_name', DB::raw("concat(users.firstName,' ',users.lastName) as project_manager_name"))
                ->where('prj_projects.id', $id)->first();
            $attachmentRecord = dbLib::getAttachment($id);
            return view('project.project', compact('parent_projects', 'project_category', 'project', 'attachmentRecord'));
        } else {

            return view('project.project', compact('parent_projects', 'project_category'));
        }
    }

    public function save(Request $request)
    {

        $companyId = session('companyId');
        $project = array(
            'parent_id' => $request->paparent_id,
            'name' => $request->project,
            'company_id' => $companyId,
            'cust_coa_id' => $request->customer_ID,
            'project_manager' => $request->assign_ID,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'category' => $request->category,
            'description' => $request->description,
        );
        if ($request->id) {
            DB::table('prj_projects')->where('id', $request->id)->update($project);
            $prj_id = $request->id;
        } else {
            $prj_id = str::uuid()->toString();
            $project['id'] = $prj_id;
            DB::table('prj_projects')->insert($project);
        }
        if ($request->file)  // save and update Project Attachment
            dbLib::uploadDocument($prj_id, $request->file);

        //return redirect('Project/ProjectManagment/View/'.$id);
        return redirect('Project/ProjectManagment/List');
    }

    public function view(Request $request, $id)
    {
        // dd($id);
        $project = DB::table('prj_projects as p1')
            ->leftjoin('prj_projects as p2', 'p2.id', '=', 'p1.parent_id')
            ->leftjoin('sys_companies', 'sys_companies.id', '=', 'p1.company_id')
            ->select('p1.*', 'sys_companies.name as company_name', 'p2.name as parent_name')
            ->where('p1.id', $id)
            ->first();
        $tasks = DB::table('crm_tasks')
            ->join('users', 'users.id', '=', 'crm_tasks.assigned_to')
            ->select('crm_tasks.*', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            ->where('crm_tasks.related_id', $id)
            ->get();

        $statuses = DB::table('sys_options')->where('type', '=', 'ticket_status')->get();
        $categories = DB::table('sys_options')->where('type', '=', 'ticket_category')->get();
        $priorities = DB::table('sys_options')->where('type', '=', 'ticket_priority')->get();
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();

        // $users=DB::table('users')->select('id',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))->get();
        $users = DB::table('users')
            ->leftJoin('prj_projects', function ($join) use ($id) {
                $join->on('prj_projects.project_manager', '=', 'users.id')
                    ->where('prj_projects.id', '=', $id);
            })
            ->select('users.id', DB::raw("concat(users.firstName,' ',users.lastName) as user_name"), 'prj_projects.project_manager')
            ->get();
        // dd();

        $team = DB::table('prj_projects')
            ->join('users', 'users.id', '=', 'prj_projects.project_manager')
            ->select(DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))
            ->where('prj_projects.id', $id)
            ->get();

        $checkedUserIds = DB::table('prj_project_team')
            ->where('prj_id', $id)
            ->pluck('emp_id')
            ->toArray();



        $tickets = DB::table('tkt_tickets')->where('related_to_id', $id)->orderBy('number', 'asc')->get();
        // dd($tickets);

        $attachmentRecord = dbLib::getAttachment($id);
        // dd($attachmentRecord->file);
        $attachment = DB::table('sys_attachments')->where('source_id', $id)->value('id');

        return view('project.project_view', compact('project', 'tasks', 'users', 'team', 'checkedUserIds', 'attachmentRecord', 'attachment', 'tickets', 'id', 'statuses', 'categories', 'priorities', 'departments',));
        return view('project.project_view');
    }

    //save team
    public function add_team(Request $request)
    {
        DB::table('prj_project_team')->where('prj_id', $request->prj_id)->delete();

        $userIds = $request->input('user_id', []);

        $projectTeamData = [];

        foreach ($userIds as $userId) {
            $id = Str::uuid()->toString();
            $projectTeamData[] = [
                'id' => $id,
                'prj_id' => $request->prj_id,
                'emp_id' => $userId,
            ];
        }

        // dd($projectTeamData);

        DB::table('prj_project_team')->insert($projectTeamData);

        return redirect()->back()->with('message', ' TeamMember Added successfully');
    }


    public function AttachmentSave(Request $request)
    {

        $id = $request->id;

        $data = array(
            'title' => $request->title,
        );
        if ($request->hasFile('file')) {
            dbLib::uploadDocument($id, $request->file('file'), $data['title']);
        }
        return redirect()->back();
    }


    public function AttachmentDelete($id)
    {

        DB::table('sys_attachments')->where('id', $id)->delete();

        return redirect()->back()->with('delete_message', 'Attachments deleted Successfully');
    }

    public function storeIdInSession($id)
    {

        // Store the ID in the session
        Session::put('stored_id', $id);

        $idd = session('stored_id');

        // Optionally, you can flash a message to the session

        return redirect()->route('Project.Managment', $idd);
    }
}
