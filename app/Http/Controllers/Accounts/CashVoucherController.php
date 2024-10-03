<?php



namespace App\Http\Controllers\Accounts;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

use phpDocumentor\Reflection\Types\Object_;

use App\accounts\CashVoucher;

use App\Libraries\dbLib;

use App\Libraries\appLib;

use Session;

use App\Libraries\accountsLib;
use Exception;
use Illuminate\Support\Facades\Auth;





class CashVoucherController extends Controller

{

    public function list($type)
    {
        $voucher_types = DB::table('fs_voucher_types')
            ->whereNotNull('type')
            ->whereIn('id', [1, 2])
            ->get();

        return view('accounts.cash_list', compact('voucher_types', 'type'));
    }



    public function getCashTransactionList(Request $request)
    {
        $companyId = session('companyId');
        $currency  = session('currency');
        $whereDateCluse = array();
        $transactionList = array();
        if ($request->account_id) {
            $arrAccount = array('fs_transdetails.coa_id', '=', $request->account_id);
            array_push($whereDateCluse, $arrAccount);
        }
        if ($request->type) {
            if ($request->type == -1)
                $arrType = array('fs_transmains.voucher_type', '<=', 4);
            else
                $arrType = array('fs_transmains.voucher_type', '=', $request->type);
            array_push($whereDateCluse,  $arrType);
        }
        if ($request->from_date) {
            $arrFromDate = array('fs_transmains.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('fs_transmains.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
        $results = DB::table('fs_transmains')
            ->leftJoin('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select('fs_transmains.id', 'fs_transmains.voucher_type', 'fs_transmains.editable', 'fs_transmains.post_status', 'fs_transmains.date', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transmains.month', 'fs_transmains.number')
            ->where('fs_transdetails.company_id', '=', $companyId)
            ->where($whereDateCluse)
            ->get();
        $voucher_prefix = accountsLib::$voucher_prefix;
        $path = accountsLib::$path;
        foreach ($results as $result) {
            $editable = ($result->post_status !== 'Posted' and $result->editable == 0) ? '0' : '1';
            $row = array(
                'document' => $voucher_prefix[$result->voucher_type] . '' . $result->month . '-' . appLib::padingZero($result->number, 4),
                'date' => date(appLib::showDateFormat(), strtotime($result->date)),
                'description' => $result->description,
                'debit' => $result->debit,
                'credit' => $result->credit,
                'voucher_type' => $result->voucher_type,
                'editable' => $editable,
                'id' => $result->id,
                //'route'=>($result->post_status!=='Posted' and $result->editable==0)?'#': url($path[$result->voucher_type]).'/'.$result->voucher_type.'/'.$result->id ,
                'route' => ($result->post_status !== 'Posted' and $result->editable == 0) ? '#' : url($path[$result->voucher_type]) . '/' . $result->voucher_type . '/' . $result->id,
                'print_route' => url($path[$result->voucher_type]) . 'Print/' . $result->id,
            );
            array_push($transactionList, $row);
        }
        return $transactionList;
    }



    public function index($type, $id = null)

    {

        $option = array();
        $info = array();
        $info['type'] = $type;
        $info['title'] = ($type == 1 || $type == 3) ? ' Reciepts ' : ' Payments ';
        $info['coaId'] = ($type == 1 || $type == 2) ? 10 : 9;
        $coa = DB::table('fs_coas')->select(array('id', 'name'))->where('trans_group', '=', 1)->where('coa_id', '=', $info['coaId'])->orderby('id')->get();
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();
        //this block is for fill type value
        if ($type == 1 || $type == 3) {
            $option[0] = 1;
            $option[1] = 3;
        } else {
            $option[0] = 2;
            $option[1] = 4;
        }
        //if Voucher is in Edit mode
        if ($id) {
            $transmain = DB::table('fs_transmains')
                ->select('fs_transmains.*', DB::raw("concat(fs_transmains.month,'-',LPAD(fs_transmains.number,4,0)) as tmnumber"))
                ->where('fs_transmains.id', '=', $id)
                ->first();
            //dd( $transmain);
            //$transmain = $transmain[0];
            if ($type == 1 || $type == 3)     // For Recieved Voucer.
            {
                $cashAccountDetail = DB::table('fs_transdetails')->where('trm_id', '=', $id)
                    ->where('credit', '=', 0)->first();
                $transDetail = DB::table('fs_transdetails')
                    ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->leftJoin('prj_projects', 'prj_projects.id', '=', 'fs_transdetails.cost_center_id')
                    ->selectRaw('fs_transdetails.*, code, fs_coas.name as name,  prj_projects.name as project_name')
                    ->where('trm_id', '=', $id)
                    ->where('debit', '=', 0)->get();
            } elseif ($type == 2 || $type == 4)     // For Payment Voucer.

            {
                $cashAccountDetail = DB::table('fs_transdetails')->where('trm_id', '=', $id)
                    ->where('debit', '=', 0)->first();
                $transDetail = DB::table('fs_transdetails')
                    ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->leftJoin('prj_projects', 'prj_projects.id', '=', 'fs_transdetails.cost_center_id')
                    ->selectRaw('fs_transdetails.*, code, fs_coas.name as name, prj_projects.name as project_name')
                    ->where('trm_id', '=', $id)
                    ->where('credit', '=', 0)->get();
            }
            $attachmentRecord = dbLib::getAttachment($id);
            return view('accounts.cashvoucher', compact('info', 'coa', 'currencies', 'transmain', 'cashAccountDetail', 'transDetail', 'attachmentRecord', 'option'));
            die();
        }


        return view('accounts.cashvoucher', compact('info', 'currencies', 'option'));
    }



    public function accounts(Request $request)
    {
        $coa = DB::table('fs_coas')->select(array('id', 'name'))->where('trans_group', '=', 1)->where('coa_id', '=', $request->coa_id)->orderby('id')->get();
        return $coa;
    }



    public function getBalance(Request $request)
    {
        $closing_balance = accountsLib::getClosingBalance($request->coa_id);
        return response()->json([
            'closing_balance' => $closing_balance,
        ]);
    }



    public function cashVoucherSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $rate = $request->rate;
            $ratePattern = '/^\d{0,4}(\.\d{0,4})?$/';
            if (!preg_match($ratePattern, $rate)) {
                session()->flash('error', 'Rate should have a maximum of 4 digits before and after the decimal point.');
                return redirect()->back()->withInput()->withErrors(['rate' => 'Rate should have a maximum of 4 digits before and after the decimal point.']);
            }
            accountsLib::delTransDetail($request->id);
            $transmains = array(
                "id" => $request->id,
                "date" => $request->date,
                "voucher_type" => $request->type,
                "note" => $request->note,
                "cheque_number" => $request->cheque_number,
                "cur_id" => $request->cur_id,
                "cur_rate" => $request->rate,
                "editable" => 1,
            );

            $trmId = accountsLib::saveTransMain($transmains);
            $nameIds = $request->name_ID;
            $i = 0;
            $totalAmount = 0;
            foreach ($nameIds as $nameId) {
                if ($request->type == 1 || $request->type == 3) {
                    $debit = 0;
                    $credit = $request->amount[$i];
                } else {
                    $debit = $request->amount[$i];
                    $credit = 0;
                }
                $totalAmount += $request->amount[$i];
                $transDetail = array(
                    "trm_id" => $trmId,
                    "coa_id" => $request->name_ID[$i],
                    "description" => $request->description[$i],
                    "cost_center_id" => ($request->has('cost_center_ID') ? $request->cost_center_ID[$i] : 'NULL'),
                    "cur_id" => $request->cur_id,
                    "cur_rate" => $request->rate,
                    "debit" => $debit,
                    "credit" => $credit,
                );
                accountsLib::saveTransDetail($transDetail);
                $i++;
            }
            if ($debit == 0) {
                $debit = $totalAmount;
                $credit = 0;
            } else {
                $debit = 0;
                $credit = $totalAmount;
            }
            $transDetail = array(
                "trm_id" => $trmId,
                "coa_id" => $request->coa_id,
                "description" => $request->note,
                "debit" => $debit,
                "cur_id" => $request->cur_id,
                "cur_rate" => $request->rate,
                "credit" => $credit,
            );
            accountsLib::saveTransDetail($transDetail);
            $print = $trmId;
            if ($request->file) { // insert attachment
                dbLib::uploadDocument($trmId, $request->file);
            }
            // throw new Exception('Manually error by zeeshan Javed');
            // Commit the transaction
            DB::commit();
            session()->flash('message', 'Transaction saved successfully');
            return redirect()->route('CashVoucher', ['type' => $request->type, "print" => $print]);
        } catch (Exception $e) {
            // dd('RollBack');
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }





    //////////////////////// Journal Voucher ////////////////////

    public function jIndex($type, $id = null)
    {
        $info = array();
        $info['type'] = $type;
        $info['title'] = 'Journal Voucher';
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();

        if ($id) {
            $transmain = DB::table('fs_transmains')
                ->select('fs_transmains.*', DB::raw("concat(fs_transmains.month,'-',LPAD(fs_transmains.number,4,0)) as tmnumber"))
                ->where('fs_transmains.id', '=', $id)->first();
            $transDetail = DB::table('fs_transdetails')
                ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                ->selectRaw('fs_transdetails.*, code, name')
                ->where('trm_id', '=', $id)->get();
            $attachmentRecord = dbLib::getAttachment($id);
            return view('accounts.journalvoucher', compact('info', 'currencies', 'transmain', 'transDetail', 'attachmentRecord'));
        }
        return view('accounts.journalvoucher', compact('info', 'currencies'));
    }


    public function journalVoucherSave(Request $request)
    {
        DB::beginTransaction();
        try {
            accountsLib::delTransDetail($request->id);
            $transmains = array(
                "id" => $request->id,
                "date" => $request->date,
                "voucher_type" => $request->type,
                "cur_id" => $request->cur_id,
                "note" => $request->note,
                "cur_rate" => $request->rate,
                "cheque_number" => $request->cheque_number,
            );
            $trmId = accountsLib::saveTransMain($transmains);
            $nameIds = $request->name_ID;
            $i = 0;
            foreach ($nameIds as $nameId) {
                $transDetail = array(
                    "trm_id" => $trmId,
                    "coa_id" => $request->name_ID[$i],
                    "description" => $request->description[$i],
                    "cur_id" => $request->cur_id,
                    "cur_rate" => $request->rate,
                    "cost_center_id" => ($request->has('cost_center_ID') ? $request->cost_center_ID[$i] : 'NULL'),
                    "debit" => $request->debit[$i],
                    "credit" => $request->credit[$i]
                );
                accountsLib::saveTransDetail($transDetail);
                $i++;
            }

            if ($request->file) { // save attachment
                dbLib::uploadDocument($trmId, $request->file);
            }
            // dd('stop');

            DB::commit();
            if ($request->print)
                return redirect('Accounts/JournalVoucherPrint/' . $trmId);
            session()->flash('message', 'Transaction saved successfully');
            return redirect()->route('JournalVoucher', ['type' => $request->type]);
        } catch (Exception $e) {
            // dd('rollBack');
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
