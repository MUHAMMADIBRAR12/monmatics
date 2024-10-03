<?php



namespace App\Http\Controllers\Accounts;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Libraries\accountsLib;

use App\Libraries\dbLib;

use Illuminate\Support\Str;

use Carbon\Carbon;
use Exception;

class VendorPaymentController extends Controller

{

    public function index()
    {
        $vendor_payment_list = DB::table('fs_invoice_payments')
            ->join('fs_coas as fs1', 'fs1.id', '=', 'fs_invoice_payments.coa_id')
            ->join('fs_coas as fs2', 'fs2.id', '=', 'fs_invoice_payments.ven_coa_id')
            ->select('fs_invoice_payments.*', 'fs1.name as received_in', 'fs2.name as received_from')
            ->where('fs_invoice_payments.company_id', session('companyId'))
            ->orderBy('fs_invoice_payments.number')
            ->orderBy('fs_invoice_payments.month')
            ->get();
        return view('accounts.vendor_payment_list', compact('vendor_payment_list'));
    }


    public function form($id = null)
    {
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();
        if ($id) {
            $invoice_payment = DB::table('fs_invoice_payments')
                ->join('fs_coas as fs1', 'fs1.id', '=', 'fs_invoice_payments.coa_id')
                ->join('fs_coas as fs2', 'fs2.id', '=', 'fs_invoice_payments.ven_coa_id')
                ->select('fs_invoice_payments.*', 'fs1.name as received_in', 'fs2.name as received_from')
                ->where('fs_invoice_payments.id', $id)
                ->get()
                ->first();
            $invoice_payment_details = DB::table('fs_invoice_payment_details')
                ->join('fs_purchase_invoices', 'fs_purchase_invoices.id', 'fs_invoice_payment_details.inv_id')
                ->select('fs_invoice_payment_details.*', DB::raw("concat(fs_purchase_invoices.month,'-',LPAD(fs_purchase_invoices.number,4,0)) as inv_num"), DB::raw('fs_purchase_invoices.total_inv_amount - fs_purchase_invoices.amount_received + fs_invoice_payment_details.received as amount'))
                ->where('fs_invoice_payment_details.inp_id', $id)
                ->get();
            if ($invoice_payment->type == 2 or  $invoice_payment->type == 4) {

                $coa_id = ($invoice_payment->type == 2) ? 10 : 9;

                $pay_from = DB::table('fs_coas')->select('id', 'name')->where('coa_id', $coa_id)->get();

                return view('accounts.vendor_payment', compact('invoice_payment', 'invoice_payment_details', 'pay_from', 'currencies'));
            }

            return view('accounts.vendor_payment', compact('invoice_payment', 'invoice_payment_details', 'currencies'));
        }



        return view('accounts.vendor_payment', compact('currencies'));
    }



    public function vendorInvoices(Request $request)

    {

        $invoices_arr = array();

        $invoices = DB::table('fs_purchase_invoices as pur_inv')

            ->select('pur_inv.id', DB::raw("concat(pur_inv.month,'-',LPAD(pur_inv.number,4,0)) as inv_num_old"), 'vendor_reference as inv_num', 'pur_inv.date', DB::raw('pur_inv.total_inv_amount - pur_inv.amount_received as balance'))

            ->where('pur_inv.ven_coa_id', $request->coa_id)

            ->whereNull('pur_inv.payment_status')

            // ->whereNotNull('pur_inv.approve_by')

            ->orderBy('pur_inv.month', 'ASC')

            ->orderBy('pur_inv.number', 'ASC')

            ->get();

        foreach ($invoices as $invoice) {

            $data = array(

                'id' => $invoice->id,

                'inv_num' => $invoice->inv_num,

                'date' => $invoice->date,

                'balance' => $invoice->balance,

            );

            array_push($invoices_arr, $data);
        }

        return $invoices_arr;
    }



    public function save(Request $request)
    {
        DB::beginTransaction();
        try{

            if ($request->type > -1) {
                $transMains = array(
                    "id" => $request->trm_id,
                    "date" => $request->date,
                    "voucher_type" => $request->type,
                    "note" => $request->note,
                );
                $trmId = accountsLib::saveTransMain($transMains);
                accountsLib::delTransDetail($trmId);
                $transDetails = array(
                    "trm_id" => $trmId,
                    "coa_id" => $request->pay_from_coa_id,
                    "description" => $request->note,
                    "debit" => 0,
                    "credit" => $request->total_pay_amount,
                );
                accountsLib::saveTransDetail($transDetails);
                $transDetails['debit'] = $request->total_pay_amount;
                $transDetails['credit'] = 0;
                $transDetails['coa_id'] = $request->pay_to_ID;
                accountsLib::saveTransDetail($transDetails);
            } else
                $trmId = null;
            $inpId = $this->invoicePayment($trmId, $request);
            $this->invoicePaymentDetail($inpId, $request);

            // throw new Exception('Manually Error By Zeeshan Javed');
            // dd('stop');
            DB::commit();
            return redirect('Accounts/VendorPayment/List');
        }catch(Exception $e){
            // dd('rollBack');

            DB::rollBack();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }



    private function invoicePayment($trmId, $request)

    {

        $month = dbLib::getMonth($request->date);

        $invoice_payment = array(
            'date' => $request->date,
            'month' => $month,
            'type' => $request->type,
            'coa_id' => $request->pay_from_coa_id,
            'cheque_no' => $request->cheque_no,
            'ven_coa_id' => $request->pay_to_ID,
            'note' => $request->note,
            'trm_id' => $trmId,
            'amount' => $request->total_pay_amount,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->rate,
            'advance_amount' => $request->advance_payment,

        );



        if ($request->id) {
            $inv_payment_detail = dB::table('fs_invoice_payment_details')->select('inv_id', 'received')->where('inp_id', $request->id)->get();
            foreach ($inv_payment_detail as $inv_payment) {
                DB::table('fs_purchase_invoices')
                    ->where('id', $inv_payment->inv_id)
                    ->decrement('amount_received', $inv_payment->received);
            }
            $inp_id = $request->id;
            $invoice_payment['updated_at'] = Carbon::now();
            DB::table('fs_invoice_payments')->where('id', $request->id)->update($invoice_payment);
        } else {
            $invoice_payment['id'] = str::uuid()->toString();
            $inp_id = $invoice_payment['id'];
            $invoice_payment['number'] = dbLib::getNumber('fs_invoice_payments');
            $invoice_payment['company_id'] = session('companyId');
            $invoice_payment['created_at'] = Carbon::now();
            DB::table('fs_invoice_payments')->insert($invoice_payment);
        }
        return $inp_id;
    }
    private function invoicePaymentDetail($inpId, $request)
    {
        DB::table('fs_invoice_payment_details')->where('inp_id', $inpId)->delete();
        $count = is_array($request->invoice_id) ? count($request->invoice_id) : 0;
        for ($i = 0; $i < $count; $i++) {
            $id = str::uuid()->toString();
            $invoice_payment_details = array(
                "id" => $id,
                "inp_id" => $inpId,
                "cur_id" => $request->cur_id,
                "cur_rate" => $request->rate,
                "inv_id" => $request->invoice_id[$i],
                "inv_date" => $request->inv_date[$i],
                "amount" => $request->amount[$i],
                "received" => $request->received[$i],
                "balance" => $request->balance[$i],
            );
            DB::table('fs_invoice_payment_details')->insert($invoice_payment_details);
            DB::table('fs_purchase_invoices')->where('id', $request->invoice_id[$i])->increment('amount_received', $request->received[$i]);
            if ($request->balance[$i] == 0)
                DB::table('fs_purchase_invoices')->where('id', $request->invoice_id[$i])->update(['payment_status' => 'paid']);
        }
    }
}
