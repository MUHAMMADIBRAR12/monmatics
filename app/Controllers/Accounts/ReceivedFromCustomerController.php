<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\accountsLib;
use App\Libraries\customerLib;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReceivedFromCustomerController extends Controller
{
    public function list($type = null)
    {

        $type = isset($type) ? $type : '1';
        //dd($type);
        //adjustment list
        if ($type == 6) {
            $received_from_customers = DB::table('fs_invoice_received')
                ->leftJoin('fs_coas as fs1', 'fs1.id', '=', 'fs_invoice_received.coa_id')
                ->leftJoin('fs_coas as fs2', 'fs2.id', '=', 'fs_invoice_received.cst_coa_id')
                ->select('fs_invoice_received.*', 'fs1.name as received_in', 'fs2.name as received_from')
                ->where('fs_invoice_received.type', $type)
                ->where('fs_invoice_received.company_id', session('companyId'))
                ->orderBy('fs_invoice_received.number')
                ->orderBy('fs_invoice_received.month')
                ->get();
            return view('accounts.received_from_customer_list', compact('received_from_customers', 'type'));
        }
        //received from customer list
        else {
            $received_from_customers = DB::table('fs_invoice_received')
                ->leftJoin('fs_coas as fs1', 'fs1.id', '=', 'fs_invoice_received.coa_id')
                ->leftJoin('fs_coas as fs2', 'fs2.id', '=', 'fs_invoice_received.cst_coa_id')
                ->leftJoin('fs_transmains', 'fs_transmains.id', '=', 'fs_invoice_received.trm_id')
                ->select('fs_invoice_received.*', 'fs1.name as received_in', 'fs2.name as received_from', 'fs_transmains.post_status')
                ->where('fs_invoice_received.type', '!=', 6)
                ->where('fs_invoice_received.company_id', session('companyId'))
                ->orderBy('fs_invoice_received.number')
                ->orderBy('fs_invoice_received.month')
                ->get();

            return view('accounts.received_from_customer_list', compact('received_from_customers', 'type'));
        }
    }
    public function receivedFromCustomer($type, $id = null)
    {
        $accounts = DB::table('fs_coas')->select('id', 'name')
            ->where('coa_id', 9)
            ->orWhere('coa_id', 10)
            ->get();

        $currencies = DB::table('sys_currencies')->select(array('id','code'))->orderBy('code')->get();

        if ($id) {
            $received_from_customer = DB::table('fs_invoice_received')
                ->leftJoin('fs_coas as fs1', 'fs1.id', '=', 'fs_invoice_received.coa_id')
                ->leftJoin('fs_coas as fs2', 'fs2.id', '=', 'fs_invoice_received.cst_coa_id')
                ->select('fs_invoice_received.*', 'fs1.name as received_in', 'fs2.name as received_from')
                ->where('fs_invoice_received.id', $id)
                ->get()
                ->first();
            //dd($received_from_customer);
            $received_from_customer_details = DB::table('fs_invoice_received')
                ->join('fs_invoice_received_details', 'fs_invoice_received_details.inr_id', 'fs_invoice_received.id')
                ->join('sal_invoices', 'sal_invoices.id', 'fs_invoice_received_details.inv_id')
                ->select('fs_invoice_received_details.*', DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"), DB::raw('sal_invoices.total_inv_amount - sal_invoices.amount_received + fs_invoice_received_details.received as amount'))
                ->where('fs_invoice_received.id', $id)
                ->get();
            //dd($received_from_customer_details);
            if ($received_from_customer->type == 1 or $received_from_customer->type == 3) {
                $coa_id = ($received_from_customer->type == 1) ? 10 : 9;
                $received_in = DB::table('fs_coas')->select('id', 'name')->where('coa_id', $coa_id)->get();
                return view('accounts.recvd_from_customer', compact('accounts', 'received_from_customer', 'received_from_customer_details', 'received_in', 'type', 'currencies'));
            }

            return view('accounts.recvd_from_customer', compact('accounts', 'received_from_customer', 'received_from_customer_details', 'type', 'currencies'));
        }

        return view('accounts.recvd_from_customer', compact('accounts', 'type', 'currencies'));
    }

    public function customerInvoices(Request $request)
    {
        $general_legder_balance = customerLib::customerAccountDetail($request->coa_id, 'Posted');
        $invoices_arr = array();
        $invoices = DB::table('sal_invoices')
            ->select('sal_invoices.id', DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"), 'sal_invoices.due_date', DB::raw('sal_invoices.total_inv_amount - sal_invoices.amount_received as balance'))
            ->where('sal_invoices.cst_coa_id', $request->coa_id)
            ->whereNull('sal_invoices.payment_status')
            ->orderBy('month', 'ASC')
            ->orderBy('number', 'ASC')
            ->get();
        foreach ($invoices as $invoice) {
            $data = array(
                'general_ledger_balance' => $general_legder_balance,
                'id' => $invoice->id,
                'inv_num' => $invoice->inv_num,
                'due_date' => $invoice->due_date,
                'balance' => $invoice->balance,
            );
            array_push($invoices_arr, $data);
        }
        return $invoices_arr;
    }

    public function receivedIn(Request $request)
    {
        $accounts = DB::table('fs_coas')->select('id', 'name')->where('coa_id', $request->parent_id)->get();
        return $accounts;
    }

    public function getLedgerBalance(Request $request)
    {
    }

    public function receivedAmountSave(Request $request)
    {
        // dd($request->input());
        //for transmains and transdetails
        if ($request->type > -1) {
            //save transmains data
            $transMains = array(
                "id" => $request->trm_id,
                "date" => $request->date,
                "voucher_type" => $request->type,
                "note" => $request->note,
            );
            $trmId = accountsLib::saveTransMain($transMains);

            accountsLib::delTransDetail($trmId);
            //save transdetails data
            $transDetails = array(
                "trm_id" => $trmId,
                "coa_id" => $request->received_from_ID,
                "description" => $request->note,
                "debit" => 0,
                "credit" => $request->total_pay_amount,
            );
            accountsLib::saveTransDetail($transDetails);

            // save transdetails data 2nd time
            $transDetails['debit'] = $request->total_pay_amount;
            $transDetails['credit'] = 0;
            $transDetails['coa_id'] = $request->received_in;
            accountsLib::saveTransDetail($transDetails);
        }

        //invoice_received_data
        $month = dbLib::getMonth($request->date);
        $invoice_received = array(
            "date" => $request->date,
            "month" => $month,
            "type" => $request->type,
            "coa_id" => $request->received_in,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->rate,
            "cheque_no" => $request->cheque_no,
            "cst_coa_id" => $request->received_from_ID,
            "note" => $request->note,
            "trm_id" => (isset($trmId) ? $trmId : 'NULL'),
            "lock_status" => '',
            "status" => '',
            "amount" => $request->total_pay_amount,
            "advance_amount" => $request->advance_payment,
        );
        if ($request->id) {
            $inr_id = $request->id;
            $invoice_received['updated_at'] = Carbon::now();
            DB::table('fs_invoice_received')->where('id', $request->id)->update($invoice_received);
        } else {
            $invoice_received['id'] = str::uuid()->toString();
            $invoice_received['number'] = dbLib::getNumber('fs_invoice_received');
            $inr_id = $invoice_received['id'];
            $invoice_received['company_id'] = session('companyId');
            $invoice_received['created_at'] = Carbon::now();
            DB::table('fs_invoice_received')->insert($invoice_received);
        }

        //here is the code for updating sal invoice
        if ($request->id) {
            $inv_recvd_detail = dB::table('fs_invoice_received_details')->select('inv_id', 'received')->where('inr_id', $request->id)->get();
            foreach ($inv_recvd_detail as $inv_recvd) {
                DB::table('sal_invoices')
                    ->where('id', $inv_recvd->inv_id)
                    ->decrement('amount_received', $inv_recvd->received);
            }
        }

        //first delete record from fs_invoice_received_details
        DB::table('fs_invoice_received_details')->where('inr_id', $request->id)->delete();
        $count = count($request->invoice_id);
        for ($i = 0; $i < $count; $i++) {
            $id = str::uuid()->toString();
            $invoice_received_details = array(
                "id" => $id,
                "inr_id" => $inr_id,
                "cur_id" => $request->cur_id,
                "cur_rate" => $request->rate,
                "inv_id" => $request->invoice_id[$i],
                "due_date" => $request->due_date[$i],
                "amount" => $request->amount[$i],
                "received" => $request->received[$i],
                "balance" => $request->balance[$i],
            );
            DB::table('fs_invoice_received_details')->insert($invoice_received_details);
        }
        // update sal_invoices
        $count = count($request->invoice_id);
        for ($i = 0; $i < $count; $i++) {

            DB::table('sal_invoices')
                ->where('id', $request->invoice_id[$i])
                ->increment('amount_received', $request->received[$i]);
            if ($request->balance[$i] == 0)
                DB::table('sal_invoices')->where('id', $request->invoice_id[$i])->update(['payment_status' => 'paid']);
        }

        return redirect('Account/ReceivedFromCustomer/List/' . $request->route_type);
    }
}
