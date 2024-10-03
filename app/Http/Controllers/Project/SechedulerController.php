<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SechedulerController extends Controller
{
    public function view()
    {
        $project_manager = DB::table('prj_projects')
            ->leftjoin('users', 'users.id', '=', 'prj_projects.project_manager')
            ->select('prj_projects.project_manager', DB::raw("concat(users.firstName,' ',users.lastName) as project_manager_name"))
            ->groupBy('prj_projects.project_manager')
            ->orderBy('users.firstName')
            ->get();
        return view('project.secheduler', compact('project_manager'));
    }

    public function addSechduler(Request $request)
    {
        //when we change employee from one project to another project first delete employee  from this project on that date and add to new project
        DB::table('prj_project_team')->where('emp_id', $request->emp_id)->where('date', $request->date)->delete();
        $data = array(
            'id' => str::uuid()->toString(),
            'date' => $request->date,
            'emp_id' => $request->emp_id,
            'prj_id' => $request->project_id,
        );
        DB::table('prj_project_team')->insert($data);
    }

    public function removeSechduler(Request $request)
    {

        DB::table('prj_project_team')->where('emp_id', $request->emp_id)->where('prj_id', $request->project_id)->where('date', $request->date)->delete();
    }

    public function getSechduleByDate(Request $request)
    {
        //print_r($request->date);
        $whereManagerCluse = array();
        if ($request->manager > -1) {
            array_push($whereManagerCluse, array('prj_projects.project_manager', '=', $request->manager));
        }
        $date = $request->date;
        $freeEmployee = DB::table('hcm_employee')
            ->select('hcm_employee.id', DB::raw("concat(hcm_employee.first_name,' ',hcm_employee.last_name) as employee"))
            ->whereNotIn('hcm_employee.id', function ($query) use ($date) {
                $query->select('emp_id')
                    ->from('prj_project_team')
                    ->where('date', $date);
            })
            ->get();
        $ProjectsByDate = DB::table('prj_projects')->select('id', 'name')->where('start_date', $date)->get();
        $SechduleProjects = DB::table('prj_projects')
            ->leftJoin('prj_project_team', 'prj_project_team.prj_id', '=', 'prj_projects.id')
            ->leftJoin('hcm_employee', 'hcm_employee.id', '=', 'prj_project_team.emp_id')
            ->select('prj_project_team.emp_id', 'prj_projects.id as prj_id', DB::raw("concat(hcm_employee.first_name,' ',hcm_employee.last_name) as employee"), 'prj_projects.name as project')
            ->where('prj_projects.start_date', $date)
            ->where($whereManagerCluse)
            ->get();

        // $SechduleProjects=DB::table('prj_project_team')
        //                  ->join('hcm_employee','hcm_employee.id','=','prj_project_team.emp_id')
        //                  ->join('prj_projects','prj_projects.id','=','prj_project_team.prj_id')
        //                  ->select('prj_project_team.emp_id','prj_project_team.prj_id',DB::raw("concat(hcm_employee.first_name,' ',hcm_employee.last_name) as employee"),'prj_projects.name as project')
        //                  ->where('prj_project_team.date',$date)
        //                  ->where($whereManagerCluse)
        //                  ->get();
        $projects = array();
        foreach ($SechduleProjects as $SechduleProject) {
            $projects[$SechduleProject->prj_id][] = array("project_name" => $SechduleProject->project, "employee_id" => $SechduleProject->emp_id, "employee_name" => $SechduleProject->employee);
        }

        $result = array(
            'ProjectsByDate' => $ProjectsByDate,
            'freeEmployee' => $freeEmployee,
            'projects' => $projects
        );

        return $result;
    }
}
