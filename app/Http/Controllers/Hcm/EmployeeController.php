<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use App\Libraries\dbLib;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        $companyId = session('companyId');

        $employees = DB::table('hcm_employees')->where('company_id' , $companyId)->get();
        return view('hcm.employee.index' , compact('employees'));
    }

    public function create()
    {
        return view('hcm.employee.create');
    }

    public function store(Request $request)
    {
        $companyId = session('companyId');

        // Validate the incoming request data
        $validatedData = $request->validate([
            'fname' => 'required|string',
            'lname' => 'nullable|string',
            'nationalID' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'current_address' => 'nullable|string',
            'current_city' => 'nullable|string',
            'current_state' => 'nullable|string',
            'current_country' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_city' => 'nullable|string',
            'permanent_state' => 'nullable|string',
            'permanent_country' => 'nullable|string',
        ]);
        $empID = Str::uuid();
        // Insert the data into the database
        $employeCreate =  DB::table('hcm_employees')->insert([
            'id' => $empID,
            'f_name' => $validatedData['fname'],
            'l_name' => $validatedData['lname'],
            'national_id' => $validatedData['nationalID'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'martial_status' => $validatedData['marital_status'],
            'current_address' => $validatedData['current_address'],
            'current_city' => $validatedData['current_city'],
            'current_state' => $validatedData['current_state'],
            'current_country' => $validatedData['current_country'],
            'permanent_address' => $validatedData['permanent_address'],
            'permanent_city' => $validatedData['permanent_city'],
            'permanent_state' => $validatedData['permanent_state'],
            'permanent_country' => $validatedData['permanent_country'],
            'company_id' => $companyId,
        ]);

        if (!$employeCreate) {
            return redirect()->route('employee.index')->with('error', 'Failed to create employee!');

        }
        // Redirect back or return a response
        return redirect()->route('employee.index')->with('success', 'Employee created successfully!');
    }

    public function empDetail($id)
    {
        $companyId = session('companyId');


        $employee = DB::table('hcm_employees')->find($id);
        $empHistory = DB::table('hcm_employment_history')->where('employee_id', $employee->id)->get();
        $emergencyContact = DB::table('hcm_emergency_contact')->where('employee_id', $employee->id)->get();
        $bankDetail = DB::table('hcm_bank_detail')->where('employee_id', $employee->id)->get();
        $educationDetail = DB::table('hcm_education_details')->where('employee_id', $employee->id)->get();

        $socialMediaPlatform = DB::table('hcm_socialmedia_detail')->where('employee_id',  null)
//            ->where('company_id', $companyId)
            ->where('url',  null)->get();
        $socialMediaDetail = DB::table('hcm_socialmedia_detail')->where('employee_id', $employee->id)->get();

        $skillType = DB::table('hcm_skills')->where('employee_id', null)
            ->where('skill_name', null)
            ->where('skill_level', null)
            ->where('company_id', $companyId)
            ->distinct()
            ->pluck('skill_type');

        $skillsByType = Collection::wrap([]);

        foreach ($skillType as $type) {
            $skillsByType[$type] = DB::table('hcm_skills')
                ->where('skill_type', $type)->whereNotNull('skill_name')
                ->where('employee_id', $employee->id)
                ->whereNotNull('employee_id')->where('company_id', $companyId)
                ->get();
        }


        $assets = DB::table('hcm_assets')->where('employee_id', $employee->id)->get();




        $departments = DB::table('hcm_departments')->where('company_id', $companyId)->get();
        $designations = DB::table('hcm_designation')->where('company_id', $companyId)->get();
        $shifts = DB::table('hcm_shifts')
            ->select(DB::raw('MIN(id) as id'), 'shift_code', 'shift_name', DB::raw('COUNT(*) as active_days_count'))
            ->where('company_id', $companyId)
            ->groupBy('shift_code', 'shift_name')
            ->get();

        $roles = DB::table('sys_roles')->select('name')->get()->unique('name');
        $employementDetails = DB::table('hcm_employment_details')->where('employee_id', $employee->id)->get();

        $documentTypes = DB::table('hcm_employees_documents')->where('employee_id', null)
            ->where('title', null)->where('company_id', $companyId)->get();

        $documents = DB::table('hcm_employees_documents')->where('employee_id', $employee->id)->get();


        return view('hcm.employee.show', compact('employee' , 'empHistory', 'emergencyContact', 'bankDetail', 'educationDetail',
        'socialMediaPlatform' , 'socialMediaDetail', 'skillType', 'skillsByType', 'assets', 'departments' , 'designations', 'shifts', 'employementDetails',
        'documentTypes', 'documents', 'roles'
        ));
    }

    public function empPersonalInfo(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fname' => 'nullable|string',
            'lname' => 'nullable|string',
            'nationalID' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'current_address' => 'nullable|string',
            'current_city' => 'nullable|string',
            'current_state' => 'nullable|string',
            'current_country' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_city' => 'nullable|string',
            'permanent_state' => 'nullable|string',
            'permanent_country' => 'nullable|string',
        ]);

        $employeeUpdate =  DB::table('hcm_employees')->where('id' , $id)->update([
            'f_name' => $validatedData['fname'],
            'l_name' => $validatedData['lname'],
            'national_id' => $validatedData['nationalID'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'martial_status' => $validatedData['marital_status'],
            'current_address' => $validatedData['current_address'],
            'current_city' => $validatedData['current_city'],
            'current_state' => $validatedData['current_state'],
            'current_country' => $validatedData['current_country'],
            'permanent_address' => $validatedData['permanent_address'],
            'permanent_city' => $validatedData['permanent_city'],
            'permanent_state' => $validatedData['permanent_state'],
            'permanent_country' => $validatedData['permanent_country'],
        ]);

        if ($employeeUpdate) {
            return redirect()->back()->with('error', 'Failed to update employee!');

        }

        return redirect()->back()->with('success', 'Employee updated successfully!');


    }

    public function employementHistory(Request $request)
    {
        try {
            $data =  $request->validate([
                'company_name' => 'required|string|max:50',
                'joining_date' => 'required|date',
                'joining_designation' => 'nullable|string|max:50',
                'leave_date' => 'required|date', // Assuming 'end_date' corresponds to 'leaving_date'
                'leave_designation' => 'nullable|string|max:50', // Corrected from 'leave_designation' to 'leaving_designation'
                'employee_id' => [
                    'required',
                    'exists:hcm_employees,id'
                ]
            ]);

            $empHistoryID = Str::uuid();

//            // Handle file upload
            $file = $request->file('experience_letter');

            if ($file) {
                dbLib::uploadDocument($empHistoryID , $request->file('experience_letter'));
            }



            // Insert data into the database
            $create = DB::table('hcm_employment_history')->insert([
                'id' => $empHistoryID,
                'company_name' => $request->input('company_name'),
                'joining_date' => $request->input('joining_date'),
                'joining_designation' => $request->input('joining_designation'),
                'leaving_date' => $request->input('leave_date'),
                'leaving_designation' => $request->input('leave_designation'),
                'employee_id' => $request->input('employee_id')
            ]);


            return redirect()->back()->with('success' , 'Employment history saved successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error' , 'Something went wrong');
        }

    }


    public function updateEmployementHistory(Request $request, $id)
    {
        try {
            $data =  $request->validate([
                'company_name' => 'required|string|max:50',
                'joining_date' => 'required|date',
                'joining_designation' => 'nullable|string|max:50',
                'leave_date' => 'required|date', // Assuming 'end_date' corresponds to 'leaving_date'
                'leave_designation' => 'nullable|string|max:50', // Corrected from 'leave_designation' to 'leaving_designation'
                'employee_id' => [
                    'required',
                    'exists:hcm_employees,id'
                ]
            ]);

            // Find the employment history record
            $employmentHistory = DB::table('hcm_employment_history')->where('id', $id)->first();


            if (!$employmentHistory) {
                return redirect()->back()->with('error', 'Employment history not found');
            }

            $file = $request->file('experience_letter');

            if ($file) {
               $deleted = dbLib::deleteAttachment($id);

               if ($deleted) {
                   dbLib::uploadDocument($id , $request->file('experience_letter'));
               }

            }




            // Update data in the database
            DB::table('hcm_employment_history')->where('id', $id)->update([
                'company_name' => $request->input('company_name'),
                'joining_date' => $request->input('joining_date'),
                'joining_designation' => $request->input('joining_designation'),
                'leaving_date' => $request->input('leave_date'),
                'leaving_designation' => $request->input('leave_designation'),
                'employee_id' => $request->input('employee_id')
            ]);

            return redirect()->back()->with('success', 'Employment history updated successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


    public function downloadExperienceLetter($id)
    {
        dbLib::downloadAttachment($id);
    }


    public function destroyEmploymentHistory($id)
    {
        try {
            // Find the employment history record
            $employmentHistory = DB::table('hcm_employment_history')->where('id', $id)->first();

            if (!$employmentHistory) {
                return redirect()->back()->with('error', 'Employment history not found');
            }
             dbLib::deleteAttachment($id);

            // Delete the employment history record
            DB::table('hcm_employment_history')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Employment history deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }






}
