<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    public function index()
    {
        $companyId = session('companyId');

        $leaves = DB::table('hcm_leaves')
            ->where('leave_group', '!=', null)
            ->where('company_id', $companyId)
            ->get();

        return view('hcm.leaves.index' , compact('leaves'));

    }

    public function show($id)
    {
        $designations = DB::table('hcm_designation')->get();

        $leave = DB::table('hcm_leaves')->find($id);

        $leaveTypes = DB::table('hcm_leaves')->where('leave_group_id' , $leave->id)->get();

        return view('hcm.leaves.show' , compact('leave' , 'leaveTypes' , 'designations'));

    }

    public function create()
    {
        $designations = DB::table('hcm_designation')->get();
        return view('hcm.leaves.create' , compact('designations'));
    }

    public function store(Request $request)
    {
        $companyId = session('companyId');

        // Validate incoming request data
        $validatedData = $request->validate([
            'leave_group' => 'nullable|string|max:36',
            'leave_for' => 'nullable|string|max:50',
            'leave_type.*' => 'nullable|string|max:50',
            'leave_count.*' => 'nullable|integer',
            'leave_category.*' => 'nullable|in:Accural,Earned,Carry_Forward',
        ]);
        $leaveGroup = $validatedData['leave_group'];
        $leaveFor = $validatedData['leave_for'];
        $leaveTypes = $validatedData['leave_type'];
        $leaveCounts = $validatedData['leave_count'];
        $leaveCategories = $validatedData['leave_category'];

        $leaveGroupId = Str::uuid();
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Insert leave group record
            $leaveGroupInsert = DB::table('hcm_leaves')->insert([
                'id' => $leaveGroupId,
                'leave_group' => $leaveGroup,
                'leave_for' => $leaveFor,
                'created_at' => now(),
                'company_id' => $companyId,
                'designation_id' => $leaveFor,
            ]);
            // Insert leave records
            foreach ($leaveTypes as $index => $leaveType) {
                $leaveTypeId = Str::uuid();
                DB::table('hcm_leaves')->insert([
                    'id' => $leaveTypeId,
                    'leave_group_id' => $leaveGroupId,
                    'leave_type' => $leaveType,
                    'leave_count' => $leaveCounts[$index],
                    'leave_category' => $leaveCategories[$index],
                    'created_at' => now(),
                    'company_id' => $companyId,

                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success' , 'Leave group created successfully' );

        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            // Optionally, return an error response
            return redirect()->back()->with('error' , 'Failed to create leave group.' );

        }
    }


    public function edit($id)
    {
        $designations = DB::table('hcm_designation')->get();

        $leave = DB::table('hcm_leaves')->find($id);

        $leaveTypes = DB::table('hcm_leaves')->where('leave_group_id' , $leave->id)->get();

        return view('hcm.leaves.edit', compact('leave' , 'leaveTypes', 'designations'));
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'leave_group' => 'nullable|string|max:36',
            'leave_for' => 'nullable|string|max:50',
            'leave_type.*' => 'nullable|string|max:50',
            'leave_count.*' => 'nullable|integer',
            'leave_category.*' => 'nullable|in:Accural,Earned,Carry_Forward',
        ]);

        $leaveGroup = $validatedData['leave_group'];
        $leaveFor = $validatedData['leave_for'];
        $leaveTypes = $validatedData['leave_type'];
        $leaveCounts = $validatedData['leave_count'];
        $leaveCategories = $validatedData['leave_category'];

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update leave group record
            DB::table('hcm_leaves')->where('id', $id)->update([
                'leave_group' => $leaveGroup,
                'leave_for' => $leaveFor,
                'updated_at' => now(),
                'designation_id' => $leaveFor,
            ]);

            // Delete existing leave types
            DB::table('hcm_leaves')->where('leave_group_id', $id)->delete();

            // Insert updated leave types
            foreach ($leaveTypes as $index => $leaveType) {
                $leaveTypeId = Str::uuid();

                DB::table('hcm_leaves')->insert([
                    'id' => $leaveTypeId,
                    'leave_group_id' => $id,
                    'leave_type' => $leaveType,
                    'leave_count' => $leaveCounts[$index],
                    'leave_category' => $leaveCategories[$index],
                    'updated_at' => now(),
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Optionally, return a success response
            return redirect()->route('leave.index')->with('success', 'Leave record updated successfully');

        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            dd($e);
            // Optionally, return an error response
            return redirect()->back()->with('error', 'Failed to update leave record.')->withErrors($e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Delete the leave group and associated leaves
            DB::table('hcm_leaves')->where('leave_group_id', $id)->delete();

            DB::table('hcm_leaves')->where('id' , $id)->delete();

            // Commit the transaction
            DB::commit();

            // Optionally, return a success response
            return redirect()->route('leave.index')->with('success' , 'Leave Group deleted successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();

            // Optionally, return an error response
            return redirect()->route('leave.index')->with('erroe' , 'Failed to delete leave records.');

        }
    }


}
