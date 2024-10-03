<?php

/*
 * The app core rights are with Solutions Wave.
 * For further help you can contact with info@solutionswave.com
 * All content of project are copyright with Solutions Wave.
 *
 * This class contains DB related functions of entire app.
 * However, logic of each module is located in their repsective models.
 */

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
//use Illuminate\Support\DateFactory;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Libraries\dbLib;
use App\Libraries\appLib;

class accountsLib
{

    
    public function createAccount($data)
    {
        
        dd($data);
        
        // This function need to define. It is not working
//        $data = array(
//                "coa_id" => request('parent_id'),
//                "code" => request('code'),
//                "trans_group" => request('trans_group'),
//                "name" => request('name'),
//                "level" => ($parentData->level + 1),
//                "order" => 3,
//                "coa_display" => 1,
//                "status" => 1,
//                "editable" => 1,
//                "category" => $parentData->category,
//                "company_id" => (request('trans_group') == 0) ? -1 : session('companyId'),
//                "branch_id" => -1,
//            );
//            $response = Coa::create($data);
//        
//        
//        insertGetId
    }
    
    // This recursive function generate chart of accoutn tree
    public static function coaTree($coaId, $space = "&nbsp; ")
    {
        $mainData = array();
        $coas = DB::table('fs_coas')->select(array('id', 'coa_id', 'name', 'editable', 'trans_group'))->where('coa_id', '=', $coaId)->orderby('id')->get();

        foreach ($coas as $coaId) {
            $space .= " &nbsp;";
            //  echo "<br>$space" . ($coaId->id );
            $mainData[] = $coaId;
            $coaIdData = accountsLib::coaTree($coaId->id, $space);
            $mainData[] = $coaIdData;
        }

        return  $mainData;
    }

    public static function saveTransMain($data)
    {
        //dd($data);
        $request = collect(($data));
        // Config variable
        $userId = Auth::id();
        $info = dbLib::getInfo();
        $first_approval = (config('app_session.voucherApproval_first') == true ? 'NULL' : '1');
        $second_approval = (config('app_session.voucherApproval_second') == true ? 'NULL' : '1');
        $postStatus  = ($request->has('post_status') ? $request['post_status'] : NUll);

        if ($request->has('cur_id')) {
            $curId = $request['cur_id'];
            $curId = ($info['cur_id']) ? $info['cur_id'] : 1;
            $curRate = ($info['cur_rate']) ? $info['cur_rate'] : 1;
        } else        // This will get currency and rate from dbLib::GetInfo
        {
            $curId = ($info['cur_id']) ? $info['cur_id'] : 1;
            $curRate = ($info['cur_rate']) ? $info['cur_rate'] : 1;
        }

        if ($request['id']) {
            $id = $request['id'];
            $tranMain = array(
                "cur_id" => $curId,
                "cur_rate" => $curRate,
                "note" => $request['note'],
                "cheque_number" => ($request->has('cheque_number') ? $request['cheque_number'] : 'NULL'),
                "post_status" => $postStatus,
                "updated_by" => $userId,
                "approve_by" => $first_approval,
                "checked_by" => $second_approval
            );
            $transMainMerged = $request->merge($tranMain);
            $transMainMerged = $transMainMerged->toArray();
            DB::table('fs_transmains')->where('id', '=', $id)->update($transMainMerged);
            // die('not ok');
        } else {
            // Insert values in transmain
            $companyId = session('companyId');
            $month = dbLib::getMonth($request['date']);
            $number =  dbLib::getNumber('fs_transmains', $request['voucher_type']);
            $id = str::uuid()->toString();
            $tranMain = array(
                "id" => $id,
                "date" => $request['date'],
                "month" => $month,
                "number" => $number,
                "voucher_type" => $request['voucher_type'],
                "company_id" => $companyId,
                "cur_id" => $curId,
                "cur_rate" => $curRate,
                "cheque_number" => ($request->has('cheque_number') ? $request['cheque_number'] : '-'),
                "post_status" => $postStatus,
                "note" => $request['note'],
                "created_by" => $userId,
                "editable" => ($request->has('editable') ? $request['editable'] : 0),
                "approve_by" => $first_approval,
                "checked_by" => $second_approval
            );
            $transMainMerged = $request->merge($tranMain);
            $transMainMerged = $transMainMerged->toArray();
            // dd($curId);
            // dd($transMainMerged);
            DB::table('fs_transmains')->insert($transMainMerged);
        }

        return $id;

        ///////////////////////// end of transmain/////////////////////////////
    }


//     public static function updateTransMain($data)
// {
//     $request = collect(($data));
//     $userId = Auth::id();
//     $info = dbLib::getInfo();
//     $first_approval = (config('app_session.voucherApproval_first') == true ? 'NULL' : '1');
//     $second_approval = (config('app_session.voucherApproval_second') == true ? 'NULL' : '1');
//     $postStatus  = ($request->has('post_status') ? $request['post_status'] : NUll);

//     if ($request['id']) {
//         $id = $request['id'];
//         $curId = ($info['cur_id']) ? $info['cur_id'] : 1;
//         $curRate = ($info['cur_rate']) ? $info['cur_rate'] : 1;

//         $tranMain = array(
//             "cur_id" => $curId,
//             "cur_rate" => $curRate,
//             "note" => $request['note'],
//             "cheque_number" => ($request->has('cheque_number') ? $request['cheque_number'] : 'NULL'),
//             "post_status" => $postStatus,
//             "updated_by" => $userId,
//             "approve_by" => $first_approval,
//             "checked_by" => $second_approval
//         );
        
//         $transMainMerged = $request->merge($tranMain);
//         $transMainMerged = $transMainMerged->toArray();

//         DB::table('fs_transmains')->where('id', '=', $id)->update($transMainMerged);
//     }
// }


    public static function saveTransDetail($data)
    {
        $info = dbLib::getInfo();
        $request = collect($data);

        if ($request->has('cur_id')) {
            $curId = $request['cur_id'];
            $curRate = $request['cur_rate'];
        } else {
            $curId = $info['cur_id'];
            $curRate = $info['cur_rate'];
        }
        if ($request['debit'] > 0 || $request['credit'] > 0) {
            $id = str::uuid()->toString();
            $transDetail = array(
                "id" => $id,
                "trm_id" => $request['trm_id'],
                "coa_id" => $request['coa_id'],
                "company_id" => session('companyId'),
                "description" => $request['description'],
                "cur_id" => $curId,
                "cur_rate" => $curRate,
                "debit" => ($request['debit']) ? $request['debit'] : 0,
                "credit" => ($request['credit']) ? $request['credit'] : 0,
                "recv_invoice_id" => ($request->has('recv_invoice_id') ? $request['recv_invoice_id'] : 'NULL'),
                "pay_invoice_id" => ($request->has('pay_invoice_id') ? $request['pay_invoice_id'] : 'NULL'),
                "cost_center_id" => ($request->has('cost_center_id') ? $request['cost_center_id'] : 'NULL'),
                "status" => '',
                "lock_status" => ''
            );
            DB::table('fs_transdetails')->insert($transDetail);
        }

        /*
         $trmId=$id;
        // // insert values in trandetail
        DB::table('fs_transdetail')->where('trm_id','=',$trmId)->delete();
        $nameIds = $request->name_ID;
        $i=0;
        $totalAmount=0;
        foreach($nameIds as $nameId)
        {
            if($request->type==1 || $request->type==3)
            {
                $debit = 0;
                $credit = $request->amount[$i];
            }
            else
            {
                $debit = $request->amount[$i];
                $credit = 0;
            }
            $totalAmount += $request->amount[$i];

            //print_r($transDetail);
            $i++;
        }

        if($debit==0)
        {
            $debit = $totalAmount;
            $credit = 0;
        }
        else
        {
            $debit = 0;
            $credit = $totalAmount;
        }

        $id = str::uuid()->toString();
        $transDetail = array (
            "id"=>$id,
            "trm_id"=>$trmId,
            "coa_id"=>$request->coa_id,
            "description"=> '', //$request->description[$i],
            "cur_id"=> $request->cur_id,
            "cur_rate"=> $request->rate,
            "cost_center_id"=>'',
            "debit"=>$debit,
            "credit"=>$credit,
        );
        DB::table('fs_transdetail')->insert($transDetail);

        */
    }

    //delete trans detail record
    public static function delTransDetail($trans_id)
    {
        DB::table('fs_transdetails')->where('trm_id', '=', $trans_id)->delete();
    }

    public static function getOpeningBalance($coaId, $date = null)
    {
        $companyId = session('companyId');
        $wherearray = ($date) ? array('fs_transmains.date', '<', $date) : array('fs_transmains.voucher_type', '=', 99);
        $opening_balance = DB::table('fs_transmains')
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select(DB::raw("(SUM(fs_transdetails.debit) - SUM(fs_transdetails.credit)) as balance"))
            ->where('fs_transdetails.coa_id', $coaId)
            ->where([$wherearray])
            ->where('fs_transdetails.company_id', '=', $companyId)
            ->where('fs_transmains.post_status', '=', 'Posted')
            ->first();

        return $opening_balance;
    }

    public static function getClosingBalance($coaId, $date = null, $from_date = null)
    {
        $companyId = session('companyId');
        $where = array ();

        if ($date) {
            $wherearray = ($date) ? array('fs_transmains.date', '<=', $date) : '';
            array_push($where, $wherearray);
        }
        if ($from_date) {
            $wherearray = ($from_date) ? array('fs_transmains.date', '>=', $from_date) : '';
            array_push($where, $wherearray);
        }

        $opening_balance = DB::table('fs_transmains')
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select(DB::raw("(SUM(fs_transdetails.debit * fs_transdetails.cur_rate) - SUM(fs_transdetails.credit * fs_transdetails.cur_rate)) as balance"))
            ->where('fs_transdetails.coa_id', $coaId)
            ->where($where)
            ->where('fs_transdetails.company_id', '=', $companyId)
            ->where('fs_transmains.post_status', '=', 'Posted')
            ->first();


        return $opening_balance->balance;
    }
/*
    public static function getSubAccount($id)
    {
        $fullAccount = array();
        $recordSets = DB::table('fs_coas')->select(array('id','name'))->where('coa_id','=',$id)->where('trans_group','=','0')->orderBy('name')->get();
        foreach ($recordSets as $recordSet)
        {
            $accountGroups = $this->getSubAccount($recordSet->id);             
        }
        array_push($fullAccount, )
        return $recordSets;
        
        
    }

*/


    public static function getCoaTree($coaId, $date, $table, $from_date, $space = "")
    {
        $companyId = session('companyId');
        $month = session('month');

        $whereMain = array(
            //            ["month","=", $month ],
            ["coa_id", "=", $coaId]
        );


        $treeAccounts = DB::table('fs_coas')
            ->select('id', 'name', 'trans_group')
            ->where($whereMain)
            ->orWhere(function ($query) {
                $query->where("company_id", "-1")
                    ->where("company_id", session('companyId'));
            })
            ->get();
        $totalAmount = 0;
        foreach ($treeAccounts as $treeAccount) {
            $id = $treeAccount->id;
            $space = ($id <= 4) ? '' : $space;
            if ($treeAccount->trans_group == 0) {
                // if Record is group it will add values to new table without amount
                // amount will updated after nested loop is completed.
                $space .= "&nbsp; &nbsp; ";
                $finalData = array(
                    "name" => $space . $treeAccount->name,
                    "group_trans" => $treeAccount->trans_group,
                );
                $newTableRecodId = DB::table($table)->insertGetId($finalData);

                $amount = accountsLib::getCoaTree($id, $date, $table, $from_date, $space);

                // if ($amount == 0) {
                //     DB::table($table)->where("id", "=", $newTableRecodId)->delete();
                // } else {

                    if ($amount > 0) {
                        $DBamount = array(
                            "debit" => $amount,
                            "credit" => 0,
                        );
                    } else {
                        $DBamount = array(
                            "debit" => 0,
                            "credit" => ($amount * -1),
                        );
                    }
                    DB::table($table)->where("id", "=", $newTableRecodId)->update($DBamount);
                //}
            } else {
                // if account is transaction account.
                // system will bet the closing balance and
                // values will be updated in temp table.
                $amount = accountsLib::getClosingBalance($id, $date, $from_date);
                if ($amount != 0) {

                    if ($amount > 0) {
                        $debit = $amount;
                        $credit = 0;
                    } else {
                        $debit = 0;
                        $credit = $amount * -1;
                    }
                    $finalData = array(
                        "name" => $space . $treeAccount->name,
                        "group_trans" => $treeAccount->trans_group,
                        "debit" => $debit,
                        "credit" => $credit,
                    );
                    DB::table($table)->insert($finalData);
                }
            }


            $totalAmount += $amount;
        }
        return $totalAmount;
    }


    public static function getBankBookDetails($data)
    {
        $companyId = session('companyId');
        $month = session('month');
        $account = $data->account_id;
        $whereDateCluse = array();
        // $opening_balance= accountsLib::getOpeningBalance($account,$data->from_date);

        if ($data->type) {
            $arrFromDate = array('fs_transmains.voucher_type', '=', $data->type);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($data->from_date) {
            $arrFromDate = array('fs_transmains.date', '>=', $data->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($data->to_date) {
            $arrToDate = array('fs_transmains.date', '<=', $data->to_date);
            array_push($whereDateCluse, $arrToDate);
        }

        $result = DB::table('fs_transmains')
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select('fs_transmains.*', 'fs_transdetails.id as transdetail_id', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transdetails.bank_reconcile')
            ->whereIn('trm_id', function ($query) use ($account, $companyId) {
                $query->select('trm_id')
                    ->from('fs_transdetails')
                    ->where('coa_id', '=', $account)
                    ->where('company_id', '=', $companyId);
            })
            ->where('coa_id', '!=', $account)
            ->where('month', '=', $month)
            ->where('fs_transmains.post_status', '=', 'posted')
            ->where($whereDateCluse)
            ->orderBy('date')
            ->get();

        // In Bank Reconcilation we dont need option balance. I just commented opening balance
        // Jsut to make sure this fuction is using any where expecially Cash & bank book.
        //
        $opening_balance = $opening_balance->merge($result);
        $opening_balance->all();

        return $opening_balance;
    }



    //array of voucher type prefix
    public static $voucher_prefix = array(
        "1" => "CR",
        "2" => "CP",
        "3" => "BR",
        "4" => "BP",
        "5" => "JV",
        "6" => "PV",
        "7" => "PR",
        "8" => "SV",
        "9" => "SR",
        "10" => "qq",
        "99" => "OB",

    );

    //array of path
    public static $path = array(
        "1" => 'Accounts/CashVoucher',
        "2" => 'Accounts/CashVoucher',
        "3" => "Accounts/CashVoucher",
        "4" => "Accounts/CashVoucher",
        "5" => "Accounts/JournalVoucher",
        "6" => "Accounts/JournalVoucher",
        "7" => "Accounts/JournalVoucher",
        "8" => "Accounts/JournalVoucher",
        "9" => "Accounts/JournalVoucher",
        "10" => "Accounts/JournalVoucher",
        "99" => "Accounts/OpeningBalance",
    );
}
