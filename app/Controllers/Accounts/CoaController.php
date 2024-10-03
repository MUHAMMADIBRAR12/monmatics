<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\accounts\Coa;
use App\Libraries\accountsLib;

class CoaController extends Controller
{
    // Chart of Account
    public function index(request $request)
    {
        $coa = DB::table('fs_coas')->select(array('id', 'name', 'editable'))->where('trans_group', '=', 0)->orderby('id')->get();
        $coa_id = 0;
        if ($request->coaId) // This is parent coa_id // new mode
            $coa_id = $request->coaId;

        if ($request->Id)    // id in edit mode
        {
            $record = DB::table('fs_coas')->select()->where('id', '=', $request->Id)->get();
            $arrdata = $record->toArray();
            $data = $arrdata[0];
        }

        if (!empty($data))
            return view('accounts.coa', compact('coa', 'data'));
        else
            return view('accounts.coa', compact('coa', 'coa_id'));
    }

    // List chart of Accounts
    public function list()
    {
        function generateTreeView($items, $currentParent, $currLevel = 0, $prevLevel = -1)
        {
            foreach ($items as $itemId => $item) {
                if ($currentParent == $item->coa_id) {
                    if ($currLevel > $prevLevel) {
                        echo "<ol class='tree'> ";
                    }

                    if ($currLevel == $prevLevel) {
                        echo " </li>";
                    }

                    $menuLevel = $item['parent_id'];
                    if ($item['hasChild'] > 0) {
                        $menuLevel = $itemId;
                    }

                    echo '<li> <label for="level' . $menuLevel . '">' . $item['name'] . '</label><input type="checkbox" id="level' . $menuLevel . '"/>';

                    if ($currLevel > $prevLevel) {
                        $prevLevel = $currLevel;
                    }

                    $currLevel++;

                    generateTreeView($items, $itemId, $currLevel, $prevLevel);
                    $currLevel--;
                }
            }

            if ($currLevel == $prevLevel)
                echo " </li></ol>";
        }

        $coaTree = accountsLib::coaTree(-1);
        //dd($coaTree);
        $coa = DB::table('fs_coas')->select(array('id', 'name', 'editable'))->where('trans_group', '=', 0)->orderby('id')->get();
        //dd($coa);
        // $coaList = DB::table('fs_coas')->select(array('id','code', 'name', 'trans_group', 'category', 'editable'))->where('status','=', 1)->orderby('id')->get();
        return view('accounts.coa-list', compact('coaTree', 'coa'));
    }


    // Save Chart of Account
    public function save(Request $request)
    {
        if ($request->id) {
            // Check if the account being edited is a group account
            $isGroupAccount = DB::table('fs_coas')->where('id', $request->id)->where('trans_group', 0)->exists();
    
            // Check if the group account has any child accounts
            $hasChildAccounts = DB::table('fs_coas')->where('coa_id', $request->id)->exists();
    
            if ($isGroupAccount && $hasChildAccounts) {
                // Group account has child accounts, prevent editing if it is not only updating the account name, code, or parent ID
                if ($request->has('name') || $request->has('code')) {
                    $data = array();
    
                    if ($request->has('name')) {
                        $data['name'] = $request->name;
                    }
    
                    if ($request->has('code')) {
                        $data['code'] = $request->code;
                    }
    
                    $response = DB::table('fs_coas')->where('id', $request->id)->update($data);
                } else {
                    return response()->json(["result" => "error", "message" => "Delete child accounts before editing the group account."]);
                }
            } else {
                $data = array(
                    "coa_id" => $request->parent_id,
                    "trans_group" => $request->trans_group,
                    "name" => $request->name,
                );
            
                // Check if the account is being converted from group to transaction account
              
            
                if ($request->has('code')) {
                    $data['code'] = $request->code;
                }
            
                $response = DB::table('fs_coas')->where('id', $request->id)->update($data);
            }
            
        } else {
            $parentCoaId = request('parent_id');
            $parentData = DB::table('fs_coas')->select()->where('id', '=', $parentCoaId)->first();
    
            $data = array(
                "coa_id" => request('parent_id'),
                "code" => request('code'),
                "trans_group" => request('trans_group'),
                "name" => request('name'),
                "level" => ($parentData->level + 1),
                "order" => 3,
                "coa_display" => 1,
                "status" => 1,
                "editable" => 1,
                "category" => $parentData->category,
                "company_id" => (request('trans_group') == 0) ? -1 : session('companyId'),
                "branch_id" => -1,
            );
            $response = Coa::create($data);
        }
    
        if ($response) {
            // Check if the account name or code is updated and the trans_group is not changed
            if (($request->has('name') || $request->has('code')) && $request->trans_group == 0) {
                return response()->json(["result" => "success", "message" => "Account has been created/updated successfully."]);
            } else {
                return response()->json(["result" => "success", "message" => "Account has been created/updated successfully."]);
            }
        } else {
            // Display the error message only for group accounts and transaction accounts
            if (($request->trans_group == 0 || $request->trans_group == 1) && !$request->has('name')) {
                return response()->json(["result" => "success", "message" => "Account has been created/updated successfully."]);
            } else {
                return response()->json(["result" => "error", "message" => "Delete Child First"]);
            }
        }
    }
    
    
    
    
    


    public function fetchAccountInfo(Request $request)
    {

        $coass = DB::table('fs_coas')->select()->where('id', '=', $request->id)->first();
        return response()->json($coass);
        // dd($coass);
        // return $coass;
    }

    public function editParent(Request $request)
    {
        $account = DB::table('fs_coas')->where('id', $request->id)->get();
        return ($account);
    }

    public function deleteAccount(Request $request)
    {
        $data = DB::table('fs_transdetails')->where('coa_id', $request->id)->first();
    
        if ($data) {
            return 'First delete transactions related to this account.';
        } else {
            // No transactions found, proceed with deletion
            DB::table('fs_coas')->where('id', $request->id)->delete();
            return 'Account deleted successfully.';
        }
    }
    
}
