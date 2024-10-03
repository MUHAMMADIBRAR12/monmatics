<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $validation = $request->validate([
            'uni_name' => 'required',
            'degree_name' => 'required',
            'from_date' => 'nullable',
            'to_date' => 'nullable',
            'cgpa' => 'nullable|numeric|between:0,9999.99',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);


        $id = Str::uuid();

        $file = $request->file('degree_doc');


        $storeData = DB::table('hcm_education_details')->insert([
            'id' => $id,
            'employee_id' => $validation['employee_id'],
            'university_name' => $validation['uni_name'],
            'degree' => $validation['degree_name'],
            'start_date' => $validation['from_date'],
            'end_date' => $validation['to_date'],
            'cgpa' => $validation['cgpa'],
            'company_id' => $companyId,
            'created_at' => now(),
        ]);


        if (!$storeData) {
            return redirect()->back()->with('error', 'Faild to add education detail');
        }

        if ($file) {
            dbLib::uploadDocument($id , $request->file('degree_doc'));
        }
        return redirect()->back()->with('success', 'Education Detail added successfully');


    }

    public function downloadDoc($id)
    {
        dbLib::downloadAttachment($id);
    }

    public function update(Request $request, $id)
    {
        $validation = $request->validate([
            'uni_name' => 'required',
            'degree_name' => 'required',
            'from_date' => 'nullable',
            'to_date' => 'nullable',
            'cgpa' => 'nullable|numeric|between:0,9999.99',
        ]);

        $findEducation = DB::table('hcm_education_details')->where('id' , $id)->first();

        if (!$findEducation)
        {
            return redirect()->back()->with('error', 'Education detail not found');
        }

        $file = $request->file('degree_doc');

        if ($file) {
            $deleted = dbLib::deleteAttachment($id);

            if ($deleted) {
                dbLib::uploadDocument($id , $request->file('degree_doc'));
            }

        }

        $updateEducation = DB::table('hcm_education_details')->where('id', $id)->update([
            'university_name' => $validation['uni_name'],
            'degree' => $validation['degree_name'],
            'start_date' => $validation['from_date'],
            'end_date' => $validation['to_date'],
            'cgpa' => $validation['cgpa'],
            'updated_at' => now(),

        ]);

        if (!$updateEducation) {
            return redirect()->back()->with('error', 'Faild to update education detail');
        }

        return redirect()->back()->with('success', 'Education Detail updated successfully');
    }

    public function destroy($id)
    {
        try {

            $emergencyContact = DB::table('hcm_education_details')->where('id', $id)->first();

            if (!$emergencyContact) {
                return redirect()->back()->with('error', 'Education detail not found');
            }
            dbLib::deleteAttachment($id);

            DB::table('hcm_education_details')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Education Detail deleted successfully');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong');
        }

    }
}
