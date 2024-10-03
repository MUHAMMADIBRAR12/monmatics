<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = DB::table('hcm_salaries')->where('salary_name', '!=', null)->get();

        return view('hcm.salary.index' , compact('salaries'));
    }

    public function show($id)
    {
        $designations = DB::table('hcm_designation')->get();


        $salary = DB::table('hcm_salaries')->where('id', $id)->first();

        if (!$salary) {
            return redirect()->back()->with('error', 'Salary not found.');
        }

        $allowances = DB::table('hcm_salaries')->where('parent_salary_id', $id)
            ->whereNotNull('allowance_name')->get();
        $deductions = DB::table('hcm_salaries')->where('parent_salary_id', $id)
            ->whereNotNull('deduction_name')->get();

        return view('hcm.salary.show', compact('salary', 'allowances', 'deductions', 'designations'));
    }

    public function create()
    {
        $designations = DB::table('hcm_designation')->get();
        return view('hcm.salary.create', compact('designations'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'salary_name' => 'required|string',
            'salary_for' => 'required',
            'basic_salary' => 'required|numeric',
            'allowance_name.*' => 'required|string', // Validation for dynamic fields
            'allowance_amount.*' => 'required|numeric', // Validation for dynamic fields
            'allowance_percentage.*' => 'nullable|numeric', // Validation for dynamic fields
            'deduction_name.*' => 'required|string', // Validation for dynamic fields
            'deduction_amount.*' => 'required|numeric', // Validation for dynamic fields
            'deduction_percentage.*' => 'nullable|numeric', // Validation for dynamic fields
        ]);

        $salaryId = Str::uuid();
        $salaryName = $validatedData['salary_name'];
        $salaryFor = $validatedData['salary_for'];
        $basicSalary = $validatedData['basic_salary'];

        $allowancesName = $validatedData['allowance_name'];
        $allowancesAmount = $validatedData['allowance_amount'];
        $allowancesPercentage = $validatedData['allowance_percentage'];

        $deductionsName = $validatedData['deduction_name'];
        $deductionsAmount = $validatedData['deduction_amount'];
        $deductionsPercentage = $validatedData['deduction_percentage'];
        DB::beginTransaction();

        try {
            $salary = DB::table('hcm_salaries')->insert([
                'id' => $salaryId,
                'salary_name' => $salaryName,
                'salary_for' => $salaryFor,
                'designation_id' => $salaryFor,
                'basic_salary' => $basicSalary,
            ]);
            // Insert allowances records
            foreach ($allowancesName as $index => $allowanceName) {
                $allowanceId = Str::uuid();
                DB::table('hcm_salaries')->insert([
                    'id' => $allowanceId,
                    'parent_salary_id' => $salaryId,
                    'allowance_name' => $allowanceName,
                    'allowance_amount' => $allowancesAmount[$index],
                    'allowance_percentage' => $allowancesPercentage[$index],
                    'created_at' => now(),
                ]);
            }
            // Insert deduction records
            foreach ($deductionsName as $index => $deductionName) {
                $deductionId = Str::uuid();
                DB::table('hcm_salaries')->insert([
                    'id' => $deductionId,
                    'parent_salary_id' => $salaryId,
                    'deduction_name' => $deductionName,
                    'deduction_amount' => $deductionsAmount[$index],
                    'deduction_percentage' => $deductionsPercentage[$index],
                    'created_at' => now(),
                ]);
            }

            // Commit the transaction
            DB::commit();
            return redirect()->route('salary.index')->with('success', 'Salary created successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            dd($e);
            // Optionally, return an error response
            return redirect()->route('salary.index')->with('error', 'Failed to create Salary.');
        }

    }

    public function edit($id)
    {
        // Retrieve the salary details along with its allowances and deductions
        $salary = DB::table('hcm_salaries')->where('id', $id)->first();

        if (!$salary) {
            return redirect()->back()->with('error', 'Salary not found.');
        }

        $allowances = DB::table('hcm_salaries')->where('parent_salary_id', $id)
            ->whereNotNull('allowance_name')->get();
        $deductions = DB::table('hcm_salaries')->where('parent_salary_id', $id)
            ->whereNotNull('deduction_name')->get();

        $designations = DB::table('hcm_designation')->get();


        return view('hcm.salary.edit', compact('salary', 'allowances', 'deductions', 'designations'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'salary_name' => 'required|string',
            'salary_for' => 'required|string',
            'basic_salary' => 'required|numeric',
            'allowance_name.*' => 'required|string', // Validation for dynamic fields
            'allowance_amount.*' => 'required|numeric', // Validation for dynamic fields
            'allowance_percentage.*' => 'nullable|numeric', // Validation for dynamic fields
            'deduction_name.*' => 'required|string', // Validation for dynamic fields
            'deduction_amount.*' => 'required|numeric', // Validation for dynamic fields
            'deduction_percentage.*' => 'nullable|numeric', // Validation for dynamic fields
        ]);
        // Start a transaction
        DB::beginTransaction();

        try {
            // Update the salary details
            DB::table('hcm_salaries')->where('id', $id)->update([
                'salary_name' => $validatedData['salary_name'],
                'salary_for' => $validatedData['salary_for'],
                'basic_salary' => $validatedData['basic_salary'],
                'designation_id' => $validatedData['salary_for'],
            ]);

            // Delete existing allowances and deductions
            DB::table('hcm_salaries')->where('parent_salary_id', $id)->delete();

            // Insert updated allowances records
            foreach ($validatedData['allowance_name'] as $index => $allowanceName) {
                DB::table('hcm_salaries')->insert([
                    'id' => Str::uuid(),
                    'parent_salary_id' => $id,
                    'allowance_name' => $allowanceName,
                    'allowance_amount' => $validatedData['allowance_amount'][$index],
                    'allowance_percentage' => $validatedData['allowance_percentage'][$index],
                    'created_at' => now(),
                ]);
            }

            // Insert updated deductions records
            foreach ($validatedData['deduction_name'] as $index => $deductionName) {
                DB::table('hcm_salaries')->insert([
                    'id' => Str::uuid(),
                    'parent_salary_id' => $id,
                    'deduction_name' => $deductionName,
                    'deduction_amount' => $validatedData['deduction_amount'][$index],
                    'deduction_percentage' => $validatedData['deduction_percentage'][$index],
                    'created_at' => now(),
                ]);
            }

            // Commit the transaction
            DB::commit();
            return redirect()->route('salary.index')->with('success', 'Salary updated successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            // Optionally, return an error response
            return redirect()->route('salary.index')->with('error', 'Failed to update salary.');
        }
    }

    public function destory($id)
    {
        DB::beginTransaction();

        try {
            DB::table('hcm_salaries')->where('parent_salary_id' , $id)->delete();
            DB::table('hcm_salaries')->where('id' , $id)->delete();

            DB::commit();

            return redirect()->route('salary.index')->with('success', 'Salary deleted successfully');


        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->route('salary.index')->with('error', 'Fail to delete salary');


        }

    }
}
