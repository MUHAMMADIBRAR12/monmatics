<?php

namespace App\Http\Controllers\Hcm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function store(Request $request)
    {
        $companyId = session('companyId');

        $validation = $request->validate([
            'asset_name' => 'required',
            'employee_id' => [
                'required',
                'exists:hcm_employees,id',
            ]
        ]);

        $id = Str::uuid();

        $storeData = DB::table('hcm_assets')->insert([
            'id' => $id,
            'name' => $validation['asset_name'],
            'company_id' => $companyId,
            'employee_id' => $validation['employee_id'],
        ]);

        if (!$storeData) {
            return redirect()->back()->with('error', 'Failed to add asset. Try Again!');
        }

        return redirect()->back()->with('success', 'Assets added successfully');

    }

    public function update(Request $request, $id)
    {
        $companyId = session('companyId');

        $validation = $request->validate([
            'asset_name' => 'required',
        ]);

        $findAsset = DB::table('hcm_assets')->where('id' , $id)->first();

        if (!$findAsset) {
            return redirect()->back()->with('error', 'Asset Not Found!');
        }

        $updateData = DB::table('hcm_assets')->where('id' , $id)->update([
            'name' => $validation['asset_name'],
        ]);

        if (!$updateData) {
            return redirect()->back()->with('error', 'Failed to save asset. Try Again!');
        }
        return redirect()->back()->with('success', 'Asset saved successfully.');
    }

    public function destroy($id)
    {
        try {

            $findAsset = DB::table('hcm_assets')->where('id', $id)->first();

            if (!$findAsset) {
                return redirect()->back()->with('error', 'Asset not found');
            }

            DB::table('hcm_assets')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Asset deleted successfully');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
