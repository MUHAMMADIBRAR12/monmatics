<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\accountsLib;
class BankReconciliationController extends Controller
{
    public function index()
    {
        $bankbooks=DB::table('fs_coas')->select('id','name')->where('coa_id',9)->get();
        return view('reports.bank_reconciliation',compact('bankbooks'));
    }

    public function bankBookDetails(Request $request)
    {
        return accountsLib::getBankBookDetails($request);
    }

    public function updateReconciliation(Request $request)
    {
        if($request->bank_reconcile=='yes')
            DB::table('fs_transdetails')->where('id',$request->id)->update(['bank_reconcile'=>1]);
        else
            DB::table('fs_transdetails')->where('id',$request->id)->update(['bank_reconcile'=>null]);
    }
}
