<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $companyId = session('companyId');

        $departments = DB::table('hcm_departments')
            ->where('company_id', $companyId)
            ->get();
        $parentDepartmentIds = DB::table('hcm_departments')
            ->whereNotNull('parent_department_id')
            ->pluck('parent_department_id')
            ->toArray();

        return view('hcm.department.index', [
            'departments' => $departments,
            'parentDepartmentIds' => $parentDepartmentIds,
        ]);
    }

    public function create()
    {

        $companyId = session('companyId');

        $departments = DB::table('hcm_departments')
            ->where('company_id', $companyId)
            ->get();
        return view('hcm.department.create' , compact('departments'));

    }
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $request->validate([
            'dept_code' => 'nullable|string|max:15|unique:hcm_departments',
            'name' => 'required|string|max:36|unique:hcm_departments',
            'location' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|string|email|max:50|unique:hcm_departments',
        ]);
        $id = Str::uuid(); // Generating UUID

        DB::table('hcm_departments')->insert([
            'id' => $id,
            'dept_code' => $request->dept_code,
            'name' => $request->name,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'parent_department_id' => $request->parent_dept_id,
            'company_id' => $companyId,
            'created_at' => now(),
            'updated_at' => null // Assuming you want 'updated_at' to be null initially
        ]);

        return redirect()->route('hcm.department')
            ->with('success', 'Department created successfully.');
    }

    public function edit($id)
    {
        $companyId = session('companyId');

        $departments = DB::table('hcm_departments')
            ->where('company_id', $companyId)
            ->get();

        $selectedDepartment = DB::table('hcm_departments')->find($id);

        if (!$selectedDepartment) {
            return redirect()->route('hcm.department')->with('error', 'Department not found.');
        }


        return view('hcm.department.edit', compact('departments' ,
            'selectedDepartment'));
    }

    public function update(Request $request, $id)
    {
        $companyId = session('companyId');

        $request->validate([
            'dept_code' => 'nullable|string|max:15|unique:hcm_departments,dept_code,'.$id,
            'name' => 'required|string|max:36|unique:hcm_departments,name,'.$id,
            'location' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|string|email|max:50|unique:hcm_departments,email,'.$id,
            'parent_dept_id' => 'nullable|exists:hcm_departments,id'
        ]);

        $department = DB::table('hcm_departments')->find($id);

        if (!$department) {
            return redirect()->route('hcm.department.edit')->with('error', 'Department not found.');
        }

        DB::table('hcm_departments')
            ->where('id', $id)
            ->update([
                'dept_code' => $request->dept_code,
                'name' => $request->name,
                'location' => $request->location,
                'phone' => $request->phone,
                'email' => $request->email,
                'parent_department_id' => $request->parent_dept_id,
                'company_id' => $companyId,
                'updated_at' => now()
            ]);

        return redirect()->route('hcm.department')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        $department = DB::table('hcm_departments')->find($id);

        if (!$department) {
            return redirect()->route('departments.index')->with('error', 'Department not found.');
        }

        DB::table('hcm_departments')->where('id', $id)->delete();

        return redirect()->route('hcm.department')->with('success', 'Department deleted successfully.');

    }
}
