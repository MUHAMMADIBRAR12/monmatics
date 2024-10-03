<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\accountsLib;
use App\Libraries\appLib;

class PostTransactionController extends Controller
{
    public function list()
    {
        $voucher_types = DB::table('fs_voucher_types')->whereNotNull('type')->where('id', '!=', '10')->where('id', '!=', '99')->get();
        return view('accounts.post_transaction_list', compact('voucher_types'));
    }

    public function unPostTransactionList(Request $request)
    {
        $companyId = session('companyId');
        $whereDateCluse = array();
        $transactionList = array();
        $additionalVoucherCaluse = array();

        if ($request->account_id) {
            $arrAccount = array('fs_transdetails.coa_id', '=', $request->account_id);
            array_push($whereDateCluse, $arrAccount);
        }

        if ($request->type) {
            if ($request->type < 5) {
                $voucher_type = ($request->type == 1) ? 3 : 4;
                if ($request->type == 1)
                    array_push($additionalVoucherCaluse, array('fs_transmains.voucher_type', '=', 3));
                elseif ($request->type = 2)
                    array_push($additionalVoucherCaluse, array('fs_transmains.voucher_type', '=', 4));
            }
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
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
            ->select('fs_transmains.id', 'fs_transmains.voucher_type', 'fs_transmains.editable', 'fs_transmains.date', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transdetails.recv_invoice_id', 'fs_transmains.month', 'fs_transmains.number', 'fs_coas.name as account_name')
            ->where('fs_transmains.company_id', '=', $companyId)
            ->whereNull('fs_transmains.post_status')
            ->where($whereDateCluse)
            ->orWhere($additionalVoucherCaluse)
            ->orderBy('date')
            ->orderBy('voucher_type')
            ->orderBy('month')
            ->orderBy('number')
            ->get();

        $voucher_prefix = accountsLib::$voucher_prefix;
        $path = accountsLib::$path;
        foreach ($results as $result) {
            $row = array(
                'document' => $voucher_prefix[$result->voucher_type] . '' . $result->month . '-' . appLib::padingZero($result->number, 4),
                'date' => date(appLib::showDateFormat(), strtotime($result->date)),
                'account_name' => $result->account_name,
                'description' => $result->description,
                'debit' => $result->debit,
                'credit' => $result->credit,
                'recv_invoice_id' => $result->recv_invoice_id,
                'voucher_type' => $result->voucher_type,
                'id' => $result->id,
                'print_route' => url($path[$result->voucher_type]) . 'Print/' . $result->id,
                // 'route'=>($result->editable==1)? url($path[$result->voucher_type]).'/'.$result->voucher_type.'/'.$result->id : '#',
            );
            array_push($transactionList, $row);
        }
        return $transactionList;
    }

    public function post(Request $request)
    {
        DB::table('fs_transmains')->where('id', $request->id)->update(['post_status' => 'Posted']);
        if (isset($request->recv_invoice_id))
            DB::table('sal_invoices')->where('id', $request->recv_invoice_id)->update(['editable' => 0]);
    }
}
