<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShiftController extends Controller
{
    public function index()
    {
        $companyId = session('companyId');

        $shifts = DB::table('hcm_shifts')
            ->select(DB::raw('MIN(id) as id'), 'shift_code', 'shift_name', DB::raw('COUNT(*) as active_days_count'))
            ->where('company_id', $companyId)
            ->groupBy('shift_code', 'shift_name')
            ->get();

        return  view('hcm.shift.index' , compact('shifts'));
    }

    public function create()
    {
        return view('hcm.shift.create');
    }


    public function store(Request $request)
    {
       $validate =  $request->validate([
            'shift_name' => ['required', Rule::unique('hcm_shifts')->where(function ($query) {
                return $query->where('company_id', session('companyId'));
            })]
        ]);
       if (!$validate) {
           return redirect()->back()->with('error', 'There are errors');

       }
        $companyId = session('companyId');

        $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($daysOfWeek as $day) {
            $active = $request->has($day . "_shift");
                $id = Str::uuid();
                DB::table('hcm_shifts')->insert([
                    'id' => $id,
                    'shift_code' => $request->input('shift_code'),
                    'shift_name' => $request->input('shift_name'),
                    'day' => $day,
                    'start_time' => $request->input($day . "_start_time"),
                    'end_time' => $request->input($day . "_end_time"),
                    'active' => $active, // Mark the shift as active for this day
                    'company_id' => $companyId, // Assuming you have company_id associated with the authenticated user
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

        }

        return redirect()->route('hcm.shifts')->with('success', 'Shifts created successfully');
    }


    public function edit($id)
    {
        $shiftFind = DB::table('hcm_shifts')->find($id);
        $shifts = DB::table('hcm_shifts')
            ->where('shift_name', $shiftFind->shift_name)
            ->get();


//        dd($shifts);

        return view('hcm.shift.edit' , compact('shifts'));
    }

    public function update(Request $request, $id)
    {
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            $shift = DB::table('hcm_shifts')
                ->where('shift_name', $request->shift_name)
                ->where('day', $day)
                ->first();

            // Check if the checkbox for this day is checked
            $isChecked = $request->has($day . '_shift');

            if ($shift) {
                DB::table('hcm_shifts')
                    ->where('id', $shift->id)
                    ->update([
                        'start_time' => $request->{$day . '_start_time'},
                        'end_time' => $request->{$day . '_end_time'},
                        'active' => $isChecked ? 1 : 0, // Update the active status based on checkbox
                    ]);
            } else {
                if ($isChecked) {
                    DB::table('hcm_shifts')->insert([
                        'shift_name' => $request->shift_name,
                        'shift_code' => $request->shift_code,
                        'day' => $day,
                        'start_time' => $request->{$day . '_start_time'},
                        'end_time' => $request->{$day . '_end_time'},
                        'active' => 1, // Set active to 1 since the checkbox is checked
                    ]);
                }
            }
        }

        return redirect()->route('hcm.shifts')->with('success', 'Shift Updated Successfully');
    }


    public function show($id)
    {
        $shiftFind = DB::table('hcm_shifts')->find($id);
        if ($shiftFind) {
            $shifts = DB::table('hcm_shifts')
                ->where('shift_name', $shiftFind->shift_name)
                ->get();


            return view('hcm.shift.show', compact('shifts'));
        } else {
            return back()->with('error', 'Shift not found.');
        }
    }


    public function destroy($id)
    {
        // Find all records associated with the shift ID
        $shift = DB::table('hcm_shifts')->find($id);

        if ($shift) {
            // Delete all records associated with the shift name
            DB::table('hcm_shifts')->where('shift_name', $shift->shift_name)->delete();

            return redirect()->back()->with('success', 'Shift and all associated records have been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Shift not found.');
        }
    }
}
