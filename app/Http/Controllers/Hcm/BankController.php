<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $validate = $request->validate([
            'bank_name' => 'required',
            'acc_title' => 'required',
            'acc_no' => 'required',
            'iban_no' => 'nullable',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);

        $id = Str::uuid();

        $storeBank = DB::table('hcm_bank_detail')->insert([
            'id' => $id,
            'employee_id' => $validate['employee_id'],
            'bank_name' => $validate['bank_name'],
            'account_title' => $validate['acc_title'],
            'account_no' => $validate['acc_no'],
            'iban_no' => $validate['iban_no'],
            'company_id' => $companyId,
            'created_at' => now(),
        ]);

        if (!$storeBank) {
            return redirect()->back()->with('error', 'Bank Detail added faild');
        }
        return redirect()->back()->with('success', 'Bank Detail added successfully');

    }


    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'bank_name' => 'required',
            'acc_title' => 'required',
            'acc_no' => 'required',
            'iban_no' => 'nullable',
        ]);

        $findBank = DB::table('hcm_bank_detail')->where('id' , $id)->get();


        if (!$findBank) {
            return redirect()->back()->with('error', 'Bank Detail not found');
        }

        $updateBank = DB::table('hcm_bank_detail')->update([
            'bank_name' => $validate['bank_name'],
            'account_title' => $validate['acc_title'],
            'account_no' => $validate['acc_no'],
            'iban_no' => $validate['iban_no'],
            'updated_at' => now(),

        ]);

        if (!$updateBank) {
            return redirect()->back()->with('error', 'Bank Detail updated faild');
        }
        return redirect()->back()->with('success', 'Bank Detail updated successfully');

    }


    public function destroy($id)
    {
        try {
            $findBank = DB::table('hcm_bank_detail')->where('id', $id)->first();

            if (!$findBank) {
                return redirect()->back()->with('error', 'Bank Detail not found');
            }

            DB::table('hcm_bank_detail')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Bank Deatil deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
