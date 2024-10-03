<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    public function store(Request $request)
    {

        $companyId = session('companyId');

        $validation = $request->validate([
            'skill_type' => 'required',
            'skill_type_name' => $request->input('skill_type') == 'Other' ? 'required' : '',
            'skill_name' => 'required',
            'skill_level' => 'nullable|numeric',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);

        if ($request->input('skill_type') == 'Other') {
            $skillTypeId = Str::uuid();

            DB::table('hcm_skills')->insert([
                'id' => $skillTypeId,
                'skill_type' => $validation['skill_type_name'],
                'company_id' => $companyId,
                'created_at' => now(),
            ]);
        }
        $sillId = Str::uuid();

        $storeData = DB::table('hcm_skills')->insert([
            'id' => $sillId,
            'skill_type' => $request->input('skill_type') == 'Other' ? $validation['skill_type_name'] : $validation['skill_type'],
            'skill_name' => $validation['skill_name'],
            'skill_level' => $validation['skill_level'],
            'employee_id' => $validation['employee_id'],
            'company_id' => $companyId,
            'created_at' => now(),
        ]);

        if (!$storeData) {
            return redirect()->back()->with('error' , 'Faild to add Skill. Try Again!');
        }

        return redirect()->back()->with('success' , 'Skill Added Successfully');


    }

    public function update(Request $request, $id)
    {
        $companyId = session('companyId');

        $validation = $request->validate([
            'skill_name' => 'required',
            'skill_level' => 'nullable|numeric',
        ]);

        $findSkill = DB::table('hcm_skills')->where('id' , $id)->first();

        if (!$findSkill) {
            return redirect()->back()->with('error', 'skill not found.');
        }

        $updateData = DB::table('hcm_skills')->where('id' , $id)->update([
            'skill_name' => $validation['skill_name'],
            'skill_level' => $validation['skill_level'],
        ]);

        if (!$updateData) {
            return redirect()->back()->with('error', 'Failed to update skill. Try Again!');
        }

        return redirect()->back()->with('success', 'Skill Updated Successfully');

    }

    public function destroy($id)
    {
        try {

            $findSkill = DB::table('hcm_skills')->where('id', $id)->first();

            if (!$findSkill) {
                return redirect()->back()->with('error', 'Skill not found');
            }

            DB::table('hcm_skills')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Skill deleted successfully');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
