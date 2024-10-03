<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmploymentDetailController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');
        // Validate incoming request
        $request->validate([
            'emp_code' => 'required|string',
            'emp_type' => 'nullable|string',
            'doj' => 'nullable|date',
            'department_id' => 'nullable',
            'designation_id' => 'nullable',
            'shift_id' => 'nullable',
            'payroll_type' => 'nullable',
            'work_location' => 'nullable',
            'company_email' => 'nullable|email',
            'username' => 'nullable|max:15',
            'password' => 'nullable',
            'role' => 'nullable',
            'status' => 'nullable',
        ]);

        $id = Str::uuid();
        // Store the data in the database
       $storeData = DB::table('hcm_employment_details')->insert([
            'id' => $id,
            'employee_id' => $request->input('employee_id'),
            'emp_code' => $request->input('emp_code'),
            'emp_type' => $request->input('emp_type'),
            'doj' => $request->input('doj'),
            'department_id' => $request->input('department_id'),
            'designation_id' => $request->input('designation_id'),
            'shift_id' => $request->input('shift_id'),
            'payroll_type' => $request->input('payroll_type'),
            'work_location' => $request->input('work_location'),
            'company_email' => $request->input('company_email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
           'company_id' => $companyId,
           'role_id' => $request->input('role'),
           'status' => $request->input('status'),
           'created_at' => now(),
        ]);

        if (!$storeData) {
            return redirect()->back()->with('error', 'Failed to add employment detail. Please try again.');
        }

        return redirect()->back()->with('success', 'Employment detail added successfully.');

    }


    public function update(Request $request , $id)
    {
        $request->validate([
            'emp_code' => 'required|string',
            'emp_type' => 'nullable|string',
            'doj' => 'nullable|date',
            'department_id' => 'nullable',
            'designation_id' => 'nullable',
            'shift_id' => 'nullable',
            'payroll_type' => 'nullable',
            'work_location' => 'nullable',
            'company_email' => 'nullable|email',
            'username' => 'nullable|max:15',
            'password' => 'nullable',
            'role' => 'nullable',
            'status' => 'nullable',
        ]);

        $find = DB::table('hcm_employment_details')->find($id);

        if(!$find) {
            return redirect()->back()->with('error', 'Employee Detail Not Found.');
        }
        // Store the data in the database
        $storeData = DB::table('hcm_employment_details')->where('id' , $id)->update([

            'emp_code' => $request->input('emp_code'),
            'emp_type' => $request->input('emp_type'),
            'doj' => $request->input('doj'),
            'department_id' => $request->input('department_id'),
            'designation_id' => $request->input('designation_id'),
            'shift_id' => $request->input('shift_id'),
            'payroll_type' => $request->input('payroll_type'),
            'work_location' => $request->input('work_location'),
            'company_email' => $request->input('company_email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role'),
            'status' => $request->input('status'),
            'updated_at' => now(),
        ]);

        if (!$storeData) {
            return redirect()->back()->with('error', 'Failed to update employment detail. Please try again.');
        }

        return redirect()->back()->with('success', 'Employment detail update successfully.');

    }


}
