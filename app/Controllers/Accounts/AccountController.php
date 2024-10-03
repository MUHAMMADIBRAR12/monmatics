<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;
use App\Libraries\accountsLib;
use App\Libraries\dbLib;

class AccountController extends Controller
{
                public function list($source)
                {
                    $source_arr = array(
                        "Expense" => '4',
                        "Bank" => '9',
                        "Income" => '3',
                    );
                
                    // Check if the source exists in the source array
                    if (array_key_exists($source, $source_arr)) {
                        $category = $source;
                        $categoryId = $source_arr[$source];
                        $sourceData = DB::table('fs_coas')->where('category', $categoryId)->get();
                        return view('accounts.source-list', compact('sourceData', 'source', 'category'));
                    } else {
                        // Handle invalid source here (e.g., redirect to an error page)
                    }
                }
                

    

    public function view($source, $id = null)
    {

        $sourceData = DB::table('fs_coas')->select('id')->where('name', $source)->first();
        // dd($sourceData);
        $data = accountsLib::coaTree($sourceData->id);
        // dd($data);
        if ($id) {
            $record = DB::table('fs_coas')->where('id', $id)->get()->first();

            return view('accounts.source', compact('source', 'data', 'record'));
        } else {
            return view('accounts.source', compact('source', 'data'));
        }
    }


    public function save(Request $request, $source)
    {
        //dd($request->input());
        $category = array(
            "Expense" => '4',
            "Bank" => '1',
            "Income" => '3',
        );
        if ($request->id) {
            $storeData = array(
                "name" => $request->name,
                "balance" => $request->balance ?? $request->balance,
                "trans_group" => $request->trans_group,
                "coa_display" => 1,
                "status" => 1,
                "editable" => 1,
                "category" => $category[$source],
            );
            DB::table('fs_coas')->where('id', $request->id)->update($storeData);
            return redirect('Accounts/ExpenseList/' . $source);
        } else {
            $storeData = array(
                "coa_id" => $request->parent_id,
                "name" => $request->name,
                "balance" => $request->balance ?? $request->balance,
                "trans_group" => $request->trans_group,
                "coa_display" => 1,
                "status" => 1,
                "editable" => 1,
                "category" => $category[$source],
            );
            // DB::table('fs_coas')->insert($storeData);
            dbLib::createCoaAccount($storeData);
            return redirect('Accounts/ExpenseList/' . $source);
        }
    }
}
