<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\accountsLib;
use App\Libraries\appLib;
class TransactionListController extends Controller
{
    public function list()
    {
         $voucher_types=DB::table('fs_voucher_types')
                 ->whereNotNull('type')
                 ->where('id','!=','10')
                 ->where('id','!=','99')
                 ->get();
         $vouchers = DB::table('fs_transmains')
                 ->select('fs_transmains.*')
                 ->where('fs_transmains.company_id','=',session('companyId'))
                 ->orderBy('date')
                 ->get();
        return view('accounts.transaction_list',compact('voucher_types','vouchers'));
    }

    public function transactionList(Request $request)
    {
        $companyId = session('companyId');
        $currency  = session('currency');
        $whereDateCluse = array();
        $transactionList=array();
        if($request->account_id)
        {
            $arrAccount = array ('fs_transdetails.coa_id', '=', $request->account_id);
            array_push($whereDateCluse, $arrAccount);
        }
        if($request->type)
        {
            if($request->type==0)
                $arrType = array ('fs_transmains.voucher_type', '<=', 4);
            else
                $arrType = array ('fs_transmains.voucher_type', '=', $request->type);
            array_push($whereDateCluse,  $arrType);
        }
        if($request->from_date)
        {
            $arrFromDate = array ('fs_transmains.date', '>=',$request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }

        if($request->to_date)
        {
            $arrToDate = array ('fs_transmains.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }



        $results =DB::table('fs_transmains')
                    ->leftJoin('fs_transdetails','fs_transdetails.trm_id','=','fs_transmains.id')
                    ->select('fs_transmains.id','fs_transmains.voucher_type','fs_transmains.editable','fs_transmains.post_status','fs_transmains.date','fs_transdetails.description','fs_transdetails.debit','fs_transdetails.credit','fs_transmains.month','fs_transmains.number')
                    ->where('fs_transdetails.company_id','=',$companyId)
                    ->where($whereDateCluse)
                    ->orderBy('date')
                    ->get();
        $voucher_prefix=accountsLib::$voucher_prefix;
        $path=accountsLib::$path;
        foreach($results as $result)
        {
            $editable = ($result->post_status!=='Posted' and $result->editable==0)?'0':'1';
            $row= array(
                'document'=>$voucher_prefix[$result->voucher_type].''.$result->month.'-'.appLib::padingZero($result->number,4),
                'date'=> date(appLib::showDateFormat(), strtotime($result->date)),
                'description'=>$result->description,
                'debit'=>$result->debit,
                'credit'=>$result->credit,
                'voucher_type'=>$result->voucher_type,
                'editable'=>$editable,
                'id'=>$result->id,
                //'route'=>($result->post_status!=='Posted' and $result->editable==0)?'#': url($path[$result->voucher_type]).'/'.$result->voucher_type.'/'.$result->id ,
                'route'=>($result->post_status!=='Posted' and $result->editable==0)?'#': url($path[$result->voucher_type]).'/'.$result->voucher_type.'/'.$result->id ,
                'print_route'=>url($path[$result->voucher_type]).'Print/'.$result->id,
            );
            array_push($transactionList,$row);
        }
        return $transactionList;
    }



}
