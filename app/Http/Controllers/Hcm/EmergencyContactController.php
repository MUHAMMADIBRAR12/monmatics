<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmergencyContactController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $validate = $request->validate([
            'emergency_name' => 'required|string',
            'emergency_relationship' => 'nullable',
            'emergency_phone' => 'required',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
                ]
        ]);

        $id = Str::uuid();
        $storeContact = DB::table('hcm_emergency_contact')->insert([
            'id' => $id,
            'employee_id' => $validate['employee_id'],
            'name' => $validate['emergency_name'],
            'relationship' => $validate['emergency_relationship'],
            'number' => $validate['emergency_phone'],
            'company_id' => $companyId,
            'created_at' => now(),
        ]);

        if (!$storeContact) {
            return redirect()->back()->with('error', 'Emergency Contact added faild');
        }

        return redirect()->back()->with('success', 'Emergency Contact added successfully');

    }


    public  function update(Request $request, $id)
    {
        $validate = $request->validate([
            'emergency_name' => 'required|string',
            'emergency_relationship' => 'nullable',
            'emergency_phone' => 'required',
        ]);

       $findContact = DB::table('hcm_emergency_contact')->where('id' , $id)->first();


       if (!$findContact) {
           return redirect()->back()->with('error', 'Emergency Contact not found');
       }

        $updateContact = DB::table('hcm_emergency_contact')->where('id' , $id)->update([
            'name' => $validate['emergency_name'],
            'relationship' => $validate['emergency_relationship'],
            'number' => $validate['emergency_phone'],
            'updated_at' => now(),
        ]);

        if (!$updateContact) {
            return redirect()->back()->with('error', 'Failed to update Emergency Contact');
        }

        return redirect()->back()->with('success', 'Emergency Contact updated successfully');

    }

    public function destory($id)
    {
        try {
            // Find the employment history record
            $emergencyContact = DB::table('hcm_emergency_contact')->where('id', $id)->first();

            if (!$emergencyContact) {
                return redirect()->back()->with('error', 'Emergency Contact not found');
            }

            // Delete the employment history record
            DB::table('hcm_emergency_contact')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Emergency Contact deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


}
