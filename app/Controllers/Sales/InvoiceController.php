<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use App\Libraries\accountsLib;
use App\Libraries\inventoryLib;
use Illuminate\Support\Facades\Auth;
use App\Libraries\swPDF;
use PDF;
use TCPDF;
use  TCPDF_FONTS;

class InvoiceController extends Controller
{
    var $amount;

    public function index($type = null)
    {
        $Invoices = DB::table('sal_invoices')
            ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
            ->select('sal_invoices.*', 'crm_customers.name')
            ->orderByRaw('month, number ASC')
            ->get();
        $totalInvAmount = DB::table('sal_invoices')
            ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
            ->select('sal_invoices.', 'crm_customers.name')
            ->sum('total_inv_amount');

        return view('sales.invoice_list', compact('Invoices','totalInvAmount','type'));
    }
    public function form($type = null, $id = null)
    {

        $invoiceId = $id;
        if ($id) {
            $Invoice = DB::table('sal_invoices')
                ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
                ->leftJoin('sal_quotation as q', 'q.id', '=', 'quot_id')
                ->select('crm_customers.name', 'q.month as qmonth', 'q.number as qnumber', 'sal_invoices.*')
                ->where('sal_invoices.id', '=', $id)
                ->orderBy('name')
                ->first();

            $InvoiceDetail = DB::table('sal_invoice_details')
                ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                ->select('sal_invoice_details.*', 'inv_products.*', 'sal_invoice_details.description as inv_description')
                ->where('invoice_id', '=', $id)
                ->get();

            $attachmentRecord = dbLib::getAttachment($id);
            $invoice_tax = DB::table('sal_invoice_tax')->where('invoice_id', $id)->get()->first();
        }

        $taxList = dbLib::getTaxes();
        // $discounts = DB::table('sys_discounts')->where('status', '=', '1')->first();
        $discountList = dbLib::getDiscounts();
        $paymentTerms = dbLib::getPaymentTerms();
        $SalesAccounts = dbLib::getSalesAccounts();
        $projects = dbLib::getProjects(1);
        $warehouses = dbLib::getWarehouses($status = 1);
        $deliveryOrders = inventoryLib::getDeliveryOrders();
        $recurings = DB::table('sys_recurings')->select('name')->get();
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();

        if ($id)
            return view('sales.invoice', compact('SalesAccounts', 'paymentTerms', 'taxList','invoiceId', 'discountList', 'projects', 'warehouses', 'Invoice', 'InvoiceDetail', 'attachmentRecord', 'recurings', 'invoice_tax', 'type', 'deliveryOrders', 'currencies'));
        else
            return view('sales.invoice', compact('SalesAccounts', 'paymentTerms', 'taxList', 'discountList', 'projects', 'warehouses', 'recurings', 'type','invoiceId', 'deliveryOrders', 'currencies'));
    }

    public function regenerate($type = null, $id = null)
    {
        if ($id) {
            $Invoice = DB::table('sal_invoices')
                ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
                ->leftJoin('sal_quotation as q', 'q.id', '=', 'quot_id')
                ->select('crm_customers.name', 'q.month as qmonth', 'q.number as qnumber', 'sal_invoices.*')
                ->where('sal_invoices.id', '=', $id)
                ->orderBy('name')
                ->first();

            $InvoiceDetail = DB::table('sal_invoice_details')
                ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                ->select('sal_invoice_details.*', 'inv_products.*', 'sal_invoice_details.description as inv_description')
                ->where('invoice_id', '=', $id)
                ->get();

            $attachmentRecord = dbLib::getAttachment($id);
            $invoice_tax = DB::table('sal_invoice_tax')->where('invoice_id', $id)->get()->first();
        }

        $taxList = dbLib::getTaxes();
        $discountList = dbLib::getDiscounts();
        $paymentTerms = dbLib::getPaymentTerms();
        $SalesAccounts = dbLib::getSalesAccounts();
        $projects = dbLib::getProjects(1);
        $warehouses = dbLib::getWarehouses($status = 1);
        $deliveryOrders = inventoryLib::getDeliveryOrders();
        $recurings = DB::table('sys_recurings')->select('name')->get();
        $currencies = DB::table('sys_currencies')->select(array('id', 'code'))->orderBy('code')->get();

        if ($id)
            return view('sales.invoice', compact('SalesAccounts', 'paymentTerms', 'taxList', 'discountList', 'projects', 'warehouses', 'Invoice', 'InvoiceDetail', 'attachmentRecord', 'recurings', 'invoice_tax', 'type', 'deliveryOrders', 'currencies'));
        else
            return view('sales.invoice', compact('SalesAccounts', 'paymentTerms', 'taxList', 'discountList', 'projects', 'warehouses', 'recurings', 'type', 'deliveryOrders', 'currencies'));
    }

    public function view($type = null, $id = null)
    {
        if ($id) {
            $Invoice = DB::table('sal_invoices')
                ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
                ->leftJoin('sal_quotation as q', 'q.id', '=', 'quot_id')
                ->leftJoin('fs_coas', 'fs_coas.id', '=', 'sale_coa_id')
                ->leftJoin('prj_projects as prj', 'prj.id', '=', 'cost_center_id')
                ->select('crm_customers.name', 'q.month as qmonth', 'q.number as qnumber', 'sal_invoices.*', 'fs_coas.name as sale_account', 'prj.name as project_name')
                ->where('sal_invoices.id', '=', $id)
                ->orderBy('name')
                ->first();

            $InvoiceDetail = DB::table('sal_invoice_details')
                ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                ->select('sal_invoice_details.*', 'inv_products.*', 'sal_invoice_details.description as inv_description')
                ->where('invoice_id', '=', $id)
                ->get();
            $attachmentRecord = dbLib::getAttachment($id);

            $invoice_tax = DB::table('sal_invoice_tax')->where('invoice_id', $id)->first();
            return view('sales.invoice_views', compact('Invoice', 'InvoiceDetail', 'attachmentRecord', 'invoice_tax', 'type'));
        }
        

        /*
        $taxList = dbLib::getTaxes();
        $discountList = dbLib::getDiscounts();
        $paymentTerms = dbLib::getPaymentTerms();
        $SalesAccounts = dbLib::getSalesAccounts();
        $projects = dbLib::getProjects(1);
        $warehouses = dbLib::getWarehouses($status=1);
        $recurings=DB::table('sys_recurings')->select('name')->get();
        */
        

    }


    public function printinvoice($type = null, $id = null)
    {

        if ($id) {
            $Invoicepdf = DB::table('sal_invoices')
            ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
            ->leftJoin('sal_invoice_tax', 'sal_invoice_tax.invoice_id', '=', 'sal_invoices.id')
            ->leftJoin('fs_coas', 'fs_coas.id', '=', 'sal_invoice_tax.tax_coa_id')
            ->leftJoin('fs_coas as f1', 'f1.id', '=', 'sal_invoice_tax.disc_coa_id')
            ->leftJoin('crm_customers_address', 'crm_customers_address.cust_id', '=', 'crm_customers.id')
            ->leftJoin('sal_quotation as q', 'q.id', '=', 'quot_id')
            ->leftJoin('sys_currencies', 'sys_currencies.id', '=', 'sal_invoices.cur_id') // Joining the sys_currencies table
            ->select('crm_customers.name as crm_name', 'sal_invoice_tax.net_payable',
             'sal_invoice_tax.disc_rate', 'sal_invoice_tax.advance_amount', 'sal_invoice_tax.disc_amount', 'sal_invoice_tax.tax_rate as rate', 'tax_amount', 'fs_coas.name as tax_name', 'f1.name as disc_name', DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"), 'q.month as qmonth', 'crm_customers_address.phone', 'q.number as qnumber', 'sal_invoices.*', 'crm_customers_address.address', 'crm_customers_address.location', 'sys_currencies.name as currency_name', 'sys_currencies.symbol') // Added currency_name and symbol columns
            ->where('sal_invoices.id', '=', $id)
            ->orderBy('crm_customers.name')
            ->first();
            $InvoiceDetail = DB::table('sal_invoice_details')
            ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
            ->leftJoin('sal_invoices', 'sal_invoices.id', '=', 'invoice_id')
            ->select('sal_invoice_details.*', 'inv_products.*', 'sal_invoices.cur_id', 'sal_invoice_details.description as inv_description')
            ->where('invoice_id', '=', $id)
            ->get();
        
        }
        //dd($sale_invoice);
        // dd($Invoice);
        

        $pdf = new swPDF();
        //$pdf->pageType = 1;
        //$pdf= new TCPDF;
        $X = 62;
        $Y = 20;
        $Ln = 5;
        $B = 0;
        $LH = 6;
        $W = 50;

        $pdf->setH3();
        $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
        $X = 10;
        $Y = 30;
        $pdf->SetTitle('Sale Invoice');
        $Y = $pdf->GetY() + 25;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont();
        $pdf->Cell($pdf->getPageWidth() - ($X * 2), 10, 'INVOICE', $B, 0, 'C', 0, '', 0);
        $Y = $pdf->GetY() + 18;
        $pdf->SetXY($X, $Y);
        $pdf->setH3();
        $pdf->Cell(70, $LH, $Invoicepdf->crm_name, $B);
        $pdf->Ln($Ln);
        $pdf->setT1();
        $pdf->MultiCell(70, $LH, $Invoicepdf->address . $Invoicepdf->location, $B, 'L', 0, 0, '', '', true);

        $X += 95;
        $pdf->SetXY($X, $Y);
        $pdf->setH3();
        $pdf->Cell($W, $LH, 'Sale Invoice#:', $B);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($W, $LH, 'Date:', $B);
        //$pdf->SetX($X+130);
        $pdf->Ln($Ln);

        $pdf->SetX($X);

        $pdf->Cell($W, 4, $Invoicepdf->inv_num, $B);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($W, $LH, appLib::setDateFormat($Invoicepdf->date), $B);
        $pdf->Ln($Ln + 2);
        $pdf->SetX($X);
        $pdf->setH3();
        $pdf->Cell($W, $LH, 'Due Date:', $B);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($W, $LH, 'Customer Ref.#:', $B);
        $pdf->Ln($Ln);
        $pdf->setT1();
        $pdf->SetX($X);
        $pdf->Cell($W, $LH, appLib::setDateFormat($Invoicepdf->due_date, $B));
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($W, $LH, $Invoicepdf->customer_ref, $B);
        $pdf->Ln($Ln + 2);

        if ($Invoicepdf->warehouse) {
            $pdf->SetX($X);
            $pdf->setH3();
            $pdf->Cell($W, $LH, 'Warehouse:', $B);
            $pdf->Ln($Ln);
            $pdf->setT1();
            $pdf->SetX($X);
            $pdf->Cell($W, $LH, $Invoicepdf->warehouse, $B);
        }
        $pdf->Ln(9);
        $PDFLineItem = "";
        $total = 0;
        $NetAmt = 0;
        $i = 1;
        $cssClass = '';
        foreach ($InvoiceDetail as $lineItem) {
            // Retrieve currency name
            $currency = DB::table('sys_currencies')->select('name')->where('id', '=', $lineItem->cur_id)->value('name');

            
            $cssClass = ($cssClass == "bg1") ? "bg2" : "bg1";
            $PDFLineItem .= '
               <tr class="' . $cssClass . '">
               <td style="text-align:center;">' . $i . '</td>
                <td style="text-align:center;">' . $lineItem->inv_description . '</td>
                <td style="text-align:Right;">' . Str::currency($lineItem->qty) . '</td>
                <td style="text-align:Right;">' . Str::currency($lineItem->rate) . '</td>
                <td style="text-align:Right;">'   . ' ' . Str::currency($lineItem->amount) . '</td>
                </tr>
                ';
            $i++;
            $total = $lineItem->amount + $total;
        }
        

        //style="border: 1px solid black"
        $titleFont = $pdf->setTableHeading();
        // $html = '<style>'.file_get_contents(asset('public/assets/css/list.css')).'</style>';
        $html = '<style>' . @include (public_path('assets/css/list.css')) . '</style>';
        $html .= <<<EOD
        <table class="table" style="heigh:900px; font-family: Arial, Helvetica, sans-serif; width: 100%; ">
        <tr class="bg" style=" border: 1px solid #ddd; padding: 8px;">
        <th style="width:60; $titleFont  " >Sr:#</th>
        <th style="width:260;  $titleFont ">Description</th>
        <th style="width:60; $titleFont ">Qty</th>
        <th style="width:80; $titleFont ">Rate</th>
        <th style="width:70; {{ $titleFont }}">Amount <br><span style= "font-size:10px">($currency)</span></th>
        </tr>
        $PDFLineItem
        </table>        
        EOD;


        $X = 10;
        $Y = $pdf->GetY();
        $TabX = $X;
        $pdf->Ln(50);
        $TabY = $Y;
        
        $pdf->SetXY($X, $Y);
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $Y = ($pdf->GetY() < 250) ? 180 : $Y;
        $noteY = $Y;
        $pdf->Rect($TabX + 1, $TabY, $pdf->getPageWidth() - ($X * 2) - 3, ($Y - $TabY), '', '', array(220, 220, 200));
        $X = 118;
        $Ln = 5;
        $B = 1;
        $LH = 6;
        $LW = 50;
        $VW = 30;
        
        $pdf->setH3();
        $pdf->SetXY($X, $Y);
        $pdf->MultiCell($LW, $Ln, 'Total:', 'LB', 'R', 0, 0, '', '', true, 0, false, true, 0);
        
        $pdf->SetXY($X + $LW, $Y);
        $pdf->setT1();
        $pdf->MultiCell($VW, $Ln, $Invoicepdf->currency_name . ' ' . Str::currency($total) . ' ', 'LRB', 'R', 0, 0, '', '', true, 0, false, true, 0);
        $pdf->Ln();
        


        if ($Invoicepdf->disc_amount > 0) {
            $Y = $pdf->GetY();
            $pdf->setH3();
            $pdf->SetXY($X, $Y);
            $pdf->MultiCell($LW, $Ln, $Invoicepdf->disc_name . '@' . Str::currency($Invoicepdf->disc_rate), 'LB', 'R', 0, 0, '', '', true, 0, false, true, 0);
            $pdf->SetXY($X + $LW, $Y);
            $pdf->setT1();
            $pdf->MultiCell($VW, $Ln, Str::currency($Invoicepdf->disc_amount), 'LRB', 'R', 0, 0, '', '', true, 0, false, true, 0);
            $pdf->Ln();
        }
        if ($Invoicepdf->advance_amount > 0) {
            $Y = $pdf->GetY();
            $pdf->setH3();
            $pdf->SetXY($X, $Y);
            $pdf->MultiCell($LW, $Ln, 'Advance:', 'LB', 'R', 0, 0, '', '', true, 0, false, true, 0);
            $pdf->SetXY($X + $LW, $Y);
            $pdf->setT1();
            $pdf->MultiCell($VW, $Ln, Str::currency($Invoicepdf->advance_amount), 'LRB', 'R', 0, 0, '', '', true, 0, false, true, 0);
            $pdf->Ln();
        }

        if ($Invoicepdf->net_payable != $total) {
            $Y = $pdf->GetY();
            $pdf->setH3();
            $pdf->SetXY($X, $Y);
            $pdf->MultiCell($LW, $Ln, 'Net Payable:', 'LB', 'R', 0, 0, '', '', true, 0, false, true, 0);
            $pdf->SetXY($X + $LW, $Y);
            $pdf->setT1();
            $pdf->MultiCell($VW, $Ln, Str::currency($Invoicepdf->net_payable), 'LRB', 'R', 0, 0, '', '', true, 0, false, true, 0);
        }

                
        $X = 10;
        $Y = $pdf->GetY(); + 2;
        $Ln = 5;
        $B = 0;
        $LH = 5;
        $W = 100;
        $pdf->ln(2);
        $pdf->SetXY($X, $Y);
        // $pdf->SetFont($pdf->setSWFont, 'U');
        
        // Calculate the height required for the wrapped text
        $textHeight = $pdf->getStringHeight($W, 'Amount In Word: ' . appLib::amountInWords($total) . '.');
        
        $pdf->MultiCell($W, $LH, 'Amount In Word: ' . appLib::amountInWords($total) . '.', $B, 'L');
        
        // Update the Y position to the next line
        $pdf->SetXY($X, $Y + $textHeight);
        


            // Note Text Goes Here.
            $X = 10;
            $Y = $pdf->GetY() + 12;
            $pdf->SetXY($X, $Y);
            $pdf->setT1();
            
            if (!empty($Invoicepdf->note)) {
                $pdf->MultiCell(90, $Ln, 'Note: ' . $Invoicepdf->note, 0, 'L', 0, 0, '', '', true, 0, false, true, 0);
            }
            


            $pdf->Output($Invoicepdf->crm_name . '  ' . $Invoicepdf->inv_num . '.pdf', 'I');
    }


    public function save(Request $request)
    {
        //dd($request->input());
        // Invoice Record
        if ($request->id) {
            $invoiceId = $request->id;
            $this->update($request);
            //transdetial record delete
            accountsLib::delTransDetail($request->trans_id);
            $trm_id = $request->trans_id;
        } else {
            //transmain data
            $transMains = array(
                "id" => $request->trans_id,
                "date" => $request->date,
                "voucher_type" => 7,
                "post_status" => "Posted",
                "note" => $request->note,
            );
            //dd($transMains);
            $trm_id = accountsLib::saveTransMain($transMains);
            $request['trm_id'] = $trm_id;
            $invoiceId = $this->store($request);
        }
        //get invoice number
        $invoice = DB::table('sal_invoices')->select('month', 'number')->where('id', $invoiceId)->get()->first();
        $inv_number = ($invoice->month ?? '') . '-' . appLib::padingZero($invoice->number ?? '');

        //trans detail data 1
        $transDetails = array(
            "trm_id" => $trm_id,
            "coa_id" => $request->customer_ID,
            "description" => "invoice generated invoice number=" . $inv_number,
            "cost_center_id" => $request->cost_center,
            "debit" => $request->sub_total,
            "credit" => 0,
            "recv_invoice_id" => $invoiceId,
        );

        //dd($transDetails);
        accountsLib::saveTransDetail($transDetails);

        //trans detail data 2
        $transDetails['coa_id'] = $request->sale_coa_id;
        $transDetails['credit'] = $request->sub_total;
        $transDetails['debit'] = 0;
        accountsLib::saveTransDetail($transDetails);

        // Invoice Detail
        $this->updateInvoiceDetail($invoiceId, $request);

        //Invoice Tax Detail
        $this->updateInvoiceTaxDetail($invoiceId, $request);

        if ($request->file)  // Invoice Attachments
            dbLib::uploadDocument($invoiceId, $request->file);

        if ($request->post_voucher) {
            // Sales voucher will be places here. SV, voucher type is  7.
            $trmResult = DB::table('fs_transdetails')->select('trm_id')->where('recv_invoice_id', '=', $invoiceId)->first();
            if ($trmResult)
                $trmId = $trmResult->trm_id;
            else
                $trmId = null;

            //Financial Transactions
            if ($trmId) {
                $tranMain = array(
                    "id" => $trmId,
                    "date" => $request->date,
                    "note" => $request['note'],
                    "post_status" => 'posted',
                );
            } else {
                $tranMain = array(
                    "date" => $request->date,
                    "voucher_type" => 7,
                    "note" => $request['note'],
                    "post_status" => 'posted',
                );
            }


            // Get Invoice Number
            $result = DB::table('sal_invoices')->select('month', 'number')->where('id', $invoiceId)->first();
            $InvoiceNumber = $result->month . '-' . appLib::padingZero($result->number);

            $trmId = accountsLib::saveTransMain($tranMain);

            // Delete transactions and add new transdetails values.
            // Customer Will be debite by total invoice amount
            // Sales account will credit by total invoice amount.
            // currently we are not handling tax and other services or COSG account
            // Delete existing transdetial values.
            DB::table('fs_transdetails')->where('trm_id', '=', $trmId)->delete();
            // Transdetail for Customer
            $transDetail  = array(
                "trm_id" => $trmId,
                "coa_id" => $request->customer_ID,
                "description" => "Sales invoice agains Invoice #$InvoiceNumber",
                "debit" => $this->amount,
                "credit" => 0,
                "cost_center_id" => $request->cost_center,
                "recv_invoice_id" => $invoiceId,
            );
            accountsLib::saveTransDetail($transDetail);

            // TransDetail for Sales account
            $transDetail  = array(
                "trm_id" => $trmId,
                "coa_id" => $request->sale_coa_id,
                "description" => "Sales invoice agains Invoice #$InvoiceNumber",
                "debit" => 0,
                "credit" => $this->amount,
                "cost_center_id" => $request->cost_center,
                "recv_invoice_id" => $invoiceId,
            );
            accountsLib::saveTransDetail($transDetail);
            //
        }
        $type = $request->input('type');
        $printValue = $request->input('print');
        
        if ($printValue) {
            $url = 'Sales/Invoice/Create/pdf/' . $type;
            session()->flash('message', 'Transaction saved successfully');
            return redirect()->to($url . '/' . $invoiceId);
        } else {
            session()->flash('message', 'Transaction saved successfully');
            return redirect()->route('Sales/Invoice/List');
        }
        
        
        
    
    }

    private function store($request)
    {
        $info = dbLib::getInfo();
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('sal_invoices');
        $id = str::uuid()->toString();
        $invoice = array(
            "id" => $id,
            "cst_coa_id" => $request->customer_ID,
            "date" => $request->date,
            "due_date" => $request->due_date,
            "type" => ($request->type == 'p') ? 'Inventory' : '',
            "month" => $month,
            "number" => $number,
            "payment_terms" => $request->payment_terms,
            "recuring" => $request->recuring,
            "customer_ref" => $request->customer_ref,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->cur_rate,
            "sale_coa_id" => $request->sale_coa_id,
            "cost_center_id" => $request->cost_center,
            "warehouse" => $request->warehouse,
            "note" => $request->note,
            "quot_id" => $request->quotation_id,
            "trans_id" => $request->trm_id,
            "company_id" => $info['companyId'],
            "created_by" => $info['userId'],
            "created_at" => date('Y-m-d h:i:s'),
            "status" => (($request->post_voucher) ? 'approved' : 'draft'),
            "total_inv_amount" => $request->sub_total,
            //"total_inv_amount"=>
            "delete" => 0,

        );

        DB::table('sal_invoices')->insert($invoice);
        return $id;
    }

    private function update($request)
    {
        $info = dbLib::getInfo();
        $month = dbLib::getMonth($request->date);

        $id = $request->id;
        $invoice = array(
            "date" => $request->date,
            "due_date" => $request->due_date,
            "month" => $month,
            "type" => $request->type,
            "payment_terms" => $request->payment_terms,
            "recuring" => $request->recuring,
            "customer_ref" => $request->customer_ref,
            "cur_id" => $request->cur_id,
            "cur_rate" => $request->cur_rate,
            "sale_coa_id" => $request->sale_coa_id,
            "cost_center_id" => $request->cost_center,
            "warehouse" => $request->warehouse,
            "note" => $request->note,
            "quot_id" => $request->quotation_id,
            // "trnas_id"=>$request->quotation_id ,
            "company_id" => $info['companyId'],
            "updated_by" => $info['userId'],
            "updated_at" => date('Y-m-d h:i:s'),
            "status" => (($request->post_voucher) ? 'approved' : 'draft'),
            "total_inv_amount" => $request->sub_total,
            "delete" => 0,
        );
        DB::table('sal_invoices')->where('id', '=', $id)->update($invoice);
    }

    private function updateInvoiceDetail($invoiceId, $request)
    {
        // // insert values in sal_invoice_detail
        DB::table('sal_invoice_details')->where('invoice_id', '=', $invoiceId)->delete();
        $count = count($request->name_ID);
        // dd($request);
        $totalAmount = 0;
        for ($i = 0; $i < $count; $i++) {
            $id = str::uuid()->toString();
            $invoiceDetail = array(
                "id" => $id,
                "invoice_id" => $invoiceId,
                "prod_id" => $request->name_ID[$i],
                "description" => $request->description[$i],
                //  "qty_in_stock"=>$request->qty_in_stock[$i],
                //  "prod_unit"=>$request->unit[$i],
                "qty" => $request->qty[$i],
                "qty_issue" => 0,
                "qty_balance" => $request->qty[$i],
                "rate" => $request->rate[$i],
                "amount" => $request->amount[$i],
                "tax_class" => $request->tax_class[$i],
                "tax_amount" => $request->tax_amount[$i],
                "total_amount" => $request->total_amount[$i],
                "instruction" => $request->insturction[$i],
                "required_by" => $request->required_by[$i],
            );
            DB::table('sal_invoice_details')->insert($invoiceDetail);
            $totalAmount += $request->total_amount[$i];
        }
        $this->amount = $totalAmount;
    }
    private function updateInvoiceTaxDetail($invoiceId, $request)
    {

        DB::table('sal_invoice_tax')->where('invoice_id', '=', $invoiceId)->delete();
        $id = str::uuid()->toString();
        $invoiceTax = array(
            "id" => $id,
            "invoice_id" => $invoiceId,
            "tax_coa_id" => $request->tax_coa_id,
            "tax_rate" => $request->total_tax_rate,
            "tax_amount" => $request->total_tax_amount,
            "disc_coa_id" => $request->discount_coa_id,
            "disc_rate" => $request->total_discount_rate,
            "disc_amount" => $request->total_discount_amount,
            "advance_amount" => $request->advance_amount,
            "net_payable" => $request->net_payable_amount,
        );
        DB::table('sal_invoice_tax')->insert($invoiceTax);
    }


    private function postSalesVoucher()
    {
        // Add value to transmain
        // add value to trandetail

    }

    public function getTaxRate(Request $request)
    {
        $tax_rate = DB::table('sys_taxes')->select('rate')->where('coa_id', $request->tax_coa_id)->get();
        return $tax_rate;
    }

    public function getDiscountRate(Request $request)
    {
        $discount_rate = DB::table('sys_discounts')->select('rate')->where('coa_id', $request->discount_coa_id)->get();
        return $discount_rate;
    }

    public function getInvoices(Request $request)
    {
        $invoices = DB::table('sal_invoices')->where('date', $request->to_date)->get();
        return $invoices;
    }

    public function report(Request $request)
    {
        $companyId = session('companyId');
        $whereDateCluse = array();


        if ($request->customer_id) {
            $arrCustomer = array('sal_invoices.cst_coa_id', '=', $request->customer_id);
            array_push($whereDateCluse, $arrCustomer);
        }
        if ($request->from_date) {
            $arrfrom = array('sal_invoices.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrfrom);
        }
        if ($request->to_date) {
            $arrto = array('sal_invoices.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrto);
        }

        if ($request->status) {
            $arrStatus = array('sal_invoices.status', '=', $request->status);
            array_push($whereDateCluse, $arrStatus);
        }


        // if ($request->status == 'approved' || $request->status == 'draft') {
        //     $arrStatus = array('sal_invoices.status', '=', $request->status);
        //     array_push($whereDateCluse, $arrStatus);
        // } else {
        //     if ($request->status == 'partialpaid') {
        //         $arrStatus = array('sal_invoices.amount_received', '>', 0);
        //         array_push($whereDateCluse, $arrStatus);
        //     } else if ($request->status == 'paid') {
        //         $arrStatus = array('sal_invoices.total_inv_amount', '=', 'sal_invoices.amount_received');
        //         array_push($whereDateCluse, $arrStatus);
        //     } else if ($request->status == 'unpaid') {
        //         $arrStatus = array('sal_invoices.amount_received', '=', 0);
        //         array_push($whereDateCluse, $arrStatus);
        //     }
        // }

        $Invoices = DB::table('sal_invoices')
            ->leftJoin('crm_customers', 'crm_customers.coa_id', '=', 'cst_coa_id')
            ->select('sal_invoices.*', 'crm_customers.name', DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"))
            ->where($whereDateCluse)
            ->where('sal_invoices.company_id', '=', $companyId)
            ->orderByRaw('month, number ASC')
            ->get();
        return $Invoices;
    }
}
