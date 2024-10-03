<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Libraries\swPDF;
use PDF;
use TCPDF;
class SaleFmcgReportsController extends Controller
{
    public function index()
    {
        $pending_sales_orders=DB::table('sale_orders')
                                ->join('crm_customers','crm_customers.coa_id','=','sale_orders.cust_id')
                                ->leftJoin('sale_order_approvel as so_approvel','so_approvel.so_id','=','sale_orders.id')
                                ->leftJoin('inv_delivery_order_fmcg as do','do.soa_id','=','so_approvel.id')
                                ->leftJoin('sal_invoices as invoice','invoice.fmcg_do_id','=','do.id')
                                ->leftjoin('fs_transmains','fs_transmains.id','=','invoice.trans_id')
                                ->select('sale_orders.date as so_date',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_no"),'crm_customers.name as cust_name',
                                        DB::raw("concat(so_approvel.month,'-',LPAD(so_approvel.number,4,0)) as soa_no"),'so_approvel.date as soa_date','do.date as do_date',
                                        DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_no"),'do.do_tracking_date','invoice.date as inv_date',
                                        DB::raw("concat(invoice.month,'-',LPAD(invoice.number,4,0)) as inv_no"))
                                ->whereNull('fs_transmains.post_status')
                                ->where('sale_orders.company_id',session('companyId'))
                                ->orderBy('sale_orders.date')
                                ->get();
        return view('reports.pending_sales_order_list',compact('pending_sales_orders'));
    }
    //pending sale order between dates
    public function pendingSalesOrder(Request $request)
    {
        $whereDateCluse = array();
        if($request->from_date)
        {
            $arrFromDate = array ('sale_orders.date', '>=',$request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if($request->to_date)
        {
            $arrToDate = array ('sale_orders.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
        $pending_sales_orders=DB::table('sale_orders')
                                ->join('crm_customers','crm_customers.coa_id','=','sale_orders.cust_id')
                                ->leftJoin('sale_order_approvel as so_approvel','so_approvel.so_id','=','sale_orders.id')
                                ->leftJoin('inv_delivery_order_fmcg as do','do.soa_id','=','so_approvel.id')
                                ->leftJoin('sal_invoices as invoice','invoice.fmcg_do_id','=','do.id')
                                ->leftjoin('fs_transmains','fs_transmains.id','=','invoice.trans_id')
                                ->select('sale_orders.date as so_date',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_no"),'crm_customers.name as cust_name',
                                        DB::raw("concat(so_approvel.month,'-',LPAD(so_approvel.number,4,0)) as soa_no"),'so_approvel.date as soa_date','do.date as do_date',
                                        DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_no"),'do.do_tracking_date','invoice.date as inv_date',
                                        DB::raw("concat(invoice.month,'-',LPAD(invoice.number,4,0)) as inv_no"))
                                ->whereNull('fs_transmains.post_status')
                                ->where('sale_orders.company_id',session('companyId'))
                                ->where($whereDateCluse)
                                ->orderBy('sale_orders.date')
                                ->get();
        return $pending_sales_orders;
    }

    public function saleInvoiceForm($type=null)
    {
        return view('reports.sale_invoice',compact('type'));
    }

    public function SaleInvoicePrint(Request $request)
    {
        if($request->type)
        {
            $whereCustomerCluse=array();
                $whereDateCluse=array();
                if($request->customer_ID)
                {
                    array_push($whereCustomerCluse,array ('crm_customers.coa_id', '=',$request->customer_ID));
                }
                if($request->from_date)
                {
                    $arrFromDate = array ('sal_invoices.date', '>=',$request->from_date);
                    array_push($whereDateCluse, $arrFromDate);
                }
                if($request->to_date)
                {
                    $arrToDate = array ('sal_invoices.date', '<=', $request->to_date);
                    array_push($whereDateCluse, $arrToDate);
                }
                $customers=DB::table('crm_customers')->select('coa_id')->where($whereCustomerCluse)->get();
                //dd($customers);
                
                $X=78; $Y=20;$Ln=5; $B=0; $LH=5; $W=20;
                $pdf = new swPDF();
                $pdf->SetAutoPageBreak(true, 30);
                $pdf->SetHeaderMargin(50);
                $pdf->setH3();
                $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
                $X=11; $Y=30;
                $pdf->SetTitle('Production');
                $Y=$pdf->GetY()+25;
                $pdf->SetXY($X, $Y);
                $pdf->setTitleFont2(18);
                $pdf->Cell($pdf->getPageWidth()-($X*2), 10, 'Sale Invoice Summary', $B, 0, 'C', 0, 'B', 0);
                $Y=$pdf->GetY()+13;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10);
                $pdf->Cell(28,$LH,'From Date:', $B, 0, 'R', 0, 'B', 0);
                $X=$pdf->GetX();
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10);
                $pdf->Cell(30,$LH,$request->from_date, $B, 0, 'L', 0, 'B', 0);
                $X=$pdf->GetX()+7;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10);
                $pdf->Cell(15,$LH,'To Date:', $B, 0, 'R', 0, 'B', 0);
                $X=$pdf->GetX();
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10);
                $pdf->Cell(30,$LH,$request->to_date, $B, 0, 'L', 0, 'B', 0);
                
                foreach($customers as $customer)
                {
                    $i=1;
                    $invoicereport='';
                    $totalqty=0;
                    $totalbeforediscount=0;
                    $totaltradeoffer=0;
                    $totaldiscount=0;
                    $totaladvpayment=0;
                    $amounttotal=0;
                    $sale_invoice=DB::table('sal_invoices')
                                    ->leftJoin('sys_warehouses','sys_warehouses.id','=','sal_invoices.warehouse')
                                    ->leftJoin('sys_companies','sys_companies.id','=','sal_invoices.company_id')
                                    ->leftJoin('inv_delivery_order_fmcg as do','do.id','=','sal_invoices.fmcg_do_id')
                                    ->leftJoin('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                                    ->leftJoin('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                                    ->leftJoin('users as u1','u1.id','=','sale_orders.created_by')
                                    ->leftJoin('users as u2','u2.id','=','sale_order_approvel.user_id')
                                    ->leftJoin('users as u3','u3.id','=','sal_invoices.created_by')
                                    ->leftJoin('crm_customers','crm_customers.coa_id','=','do.cust_id')
                                    ->join('crm_customers_address','crm_customers_address.cust_id','=','crm_customers.id')
                                    ->select('sal_invoices.*','sys_companies.name as cn','sys_companies.phone as pn','sys_companies.email as em','do.delivery_confirm_by','sys_companies.address as add' ,DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as sale_inv_num"),DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_num"),'do.date as do_date','sys_warehouses.name as warehouse_name',
                                        DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'sale_orders.date as so_date'
                                        ,DB::raw("concat(u1.firstName,' ',u1.lastName) as sale_order_by"),DB::raw("concat(u2.firstName,' ',u2.lastName) as sale_approved_by"),DB::raw("concat(u3.firstName,' ',u3.lastName) as invoice_by"),'crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id',
                                        'crm_customers_address.address as cust_add','crm_customers_address.phone as cust_phone',
                                        'crm_customers_address.location as cust_location')
                                    ->where('sal_invoices.cst_coa_id',$customer->coa_id)
                                    
                                    ->first();   
                        
                    $sale_invoice_detail=DB::table('sal_invoices')
                                                    ->join('sale_invoice_detail_fmcg','sale_invoice_detail_fmcg.inv_id','=','sal_invoices.id')
                                                    ->join('inv_products','inv_products.id','=','sale_invoice_detail_fmcg.prod_id')
                                                    ->select('sale_invoice_detail_fmcg.*','inv_products.name as prod_name','sal_invoices.date as inv_date')
                                                    ->where('sal_invoices.cst_coa_id',$customer->coa_id)
                                                    ->where($whereDateCluse)
                                                    ->get(); 
                                                    
                    if($sale_invoice_detail && $sale_invoice)
                    {
                        $X=11;
                    $Y=$pdf->GetY()+8;
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(30,$LH,'Customer Name:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(50,$LH,$sale_invoice->cust_name, $B, 0, 'L', 0, 'B', 0);
                    $X=$pdf->GetX()+5;
                    $pdf->SetXY($X, $Y);
                    
                    $X=$pdf->GetX()+10;
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(30,$LH,'Warehouse:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetX($X);
                    $pdf->Cell(50,$LH,$sale_invoice->warehouse_name, $B, 0, 'L', 0, 'B', 0);

                    $pdf->ln();               
                    $pdf->Cell(30,$LH,'Address:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetX($X);
                    $pdf->MultiCell(145, $LH,$sale_invoice->cust_add.$sale_invoice->cust_location, $B, 'L', 0, 0, '', '', true);
        
                    
                    $Y=$pdf->GetY()+10;
                    $pdf->SetXY($X, $Y);
                    $pdf->Ln($Ln);
                        foreach($sale_invoice_detail as $detail)
                        {
                            if($pdf->GetY()>300)
                            {
                                $pdf->AddPage();
                                $pdf->SetY(100);
                                
                            }
                       
                            $invoicereport .= '<tr>
                            <td style="font-size:9px; text-align:center;">'.$i.'</td>
                            <td style="font-size:9px;">'.$detail->inv_date.'</td>
                            <td style="font-size:9px;text-align:right">'.number_format($detail->qty_approved).'</td>
                            <td style="font-size:9px;text-align:right">'.$detail->base_rate.'</td>
                            <td style="font-size:9px;text-align:right">'.$detail->amount_before_discount.'</td>
                            <td style="font-size:9px;text-align:right">'.$detail->trade_offer.'</td>
                            <td style="font-size:9px;text-align:right">'.$detail->discount.'</td>
                            <td style="font-size:9px;text-align:right">'.$detail->adv_payment.'</td>
                            <td style="font-size:9px; text-align:right">'.$detail->amount_after_discount.'</td>
                            </tr>';
                            $i++;
                            $totalqty += $detail->qty_approved;
                            $totalbeforediscount += $detail->amount_before_discount;
                            $totaltradeoffer += $detail->trade_offer;
                            $totaldiscount += $detail->discount;
                            $totaladvpayment += $detail->adv_payment;
                            $amounttotal += $detail->amount_after_discount;
                        }
                        $invoicereport .='<tr>
                                <td style="text-align:center;" colspan="2">Total </td>
                                <td style="text-align:right;font-size:9px;">'.$totalqty.'</td>
                                <td style="text-align:left;font-size:9px;"></td>
                                <td style="text-align:right;font-size:9px">'.$totalbeforediscount.'</td>
                                <td style="text-align:right;font-size:9px;">'.$totaltradeoffer.'</td>
                                <td style="text-align:right;font-size:9px;">'.$totaldiscount.'</td>
                                <td style="text-align:right;font-size:9px">'.$totaladvpayment.'</td>
                                <td style="text-align:right;font-size:9px;">'.$amounttotal.'</td>
                            </tr>';      
                                $html ='
                                <table cellspacing="0" cellpadding="1" border="0.2" style="border-color:gray;">
                                <tr style="background-color:dark-gray;color:white;">
                                <th style="width:25px;">No</th>
                                <th style="text-align:center;">Date</th>
                                <th style="width:40px; text-align:center;">Qty</th>
                                <th style="text-align:center; width:70px;">Base Rate</th>
                                <th style="width:100px;text-align:center;">Value Before Discount</th>
                                <th style="width:55px;text-align:center;">Trade Offer</th>
                                <th style="width:45px;text-align:center;">Discount</th>
                                <th style="width:66px;text-align:center;">Adv Payment</th>
                                <th style="width:70px;text-align:center;">Total Amount</th>
                                </tr>'.$invoicereport.
                                '</table>';
                                // Print text using writeHTMLCell()
                                $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                    }
                }
          
                $pdf->Output('sale_invoice_summery.pdf', 'I');
        }
        else
        {
            $whereCustomerCluse=array();
            $whereDateCluse=array();
            if($request->customer_ID)
            {
                array_push($whereCustomerCluse,array ('crm_customers.coa_id', '=',$request->customer_ID));
            }
            if($request->from_date)
            {
                $arrFromDate = array ('sal_invoices.date', '>=',$request->from_date);
                array_push($whereDateCluse, $arrFromDate);
            }
            if($request->to_date)
            {
                $arrToDate = array ('sal_invoices.date', '<=', $request->to_date);
                array_push($whereDateCluse, $arrToDate);
            }
            $customers=DB::table('crm_customers')->select('coa_id')->where($whereCustomerCluse)->get();
            //dd($customers);
            
            $X=78; $Y=20;$Ln=5; $B=0; $LH=5; $W=20;
            $pdf = new swPDF();
            $pdf->SetAutoPageBreak(true, 30);
            $pdf->SetHeaderMargin(50);
            $pdf->setH3();
            $pdf->AddPage('L', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
            $X=11; $Y=30;
            $pdf->SetTitle('Production');
            $Y=$pdf->GetY()+25;
            $pdf->SetXY($X, $Y);
            $pdf->setTitleFont2(18);
            $pdf->Cell($pdf->getPageWidth()-($X*2), 10, 'Sale Invoice Report', $B, 0, 'C', 0, 'B', 0);
            $Y=$pdf->GetY()+13;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell(30,$LH,'From Date:', $B, 0, 'R', 0, 'B', 0);
            $X=$pdf->GetX();
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell(30,$LH,$request->from_date, $B, 0, 'L', 0, 'B', 0);
            $X=$pdf->GetX()+30;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell(30,$LH,'To Date:', $B, 0, 'R', 0, 'B', 0);
            $X=$pdf->GetX();
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell(30,$LH,$request->to_date, $B, 0, 'L', 0, 'B', 0);
            
            foreach($customers as $customer)
            {
                $i=1;
                $invoicereport='';
                $taxprice=0;
                $totalqty=0;
                $totalsaletax=0;
                $totalfed=0;
                $totalfurthertax=0;
                $totaltaxprice=0;
                $totallu=0;
                $totalfreight=0;
                $totalother=0;
                $totalbeforediscount=0;
                $totaltradeoffer=0;
                $totaldiscount=0;
                $totaladvpayment=0;
                $amounttotal=0;
                $sale_invoice=DB::table('sal_invoices')
                                ->leftJoin('sys_warehouses','sys_warehouses.id','=','sal_invoices.warehouse')
                                ->leftJoin('sys_companies','sys_companies.id','=','sal_invoices.company_id')
                                ->leftJoin('inv_delivery_order_fmcg as do','do.id','=','sal_invoices.fmcg_do_id')
                                ->leftJoin('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                                ->leftJoin('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                                ->leftJoin('users as u1','u1.id','=','sale_orders.created_by')
                                ->leftJoin('users as u2','u2.id','=','sale_order_approvel.user_id')
                                ->leftJoin('users as u3','u3.id','=','sal_invoices.created_by')
                                ->leftJoin('crm_customers','crm_customers.coa_id','=','do.cust_id')
                                ->join('crm_customers_address','crm_customers_address.cust_id','=','crm_customers.id')
                                ->select('sal_invoices.*','sys_companies.name as cn','sys_companies.phone as pn','sys_companies.email as em','do.delivery_confirm_by','sys_companies.address as add' ,DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as sale_inv_num"),DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_num"),'do.date as do_date','sys_warehouses.name as warehouse_name',
                                    DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'sale_orders.date as so_date'
                                    ,DB::raw("concat(u1.firstName,' ',u1.lastName) as sale_order_by"),DB::raw("concat(u2.firstName,' ',u2.lastName) as sale_approved_by"),DB::raw("concat(u3.firstName,' ',u3.lastName) as invoice_by"),'crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id',
                                    'crm_customers_address.address as cust_add','crm_customers_address.phone as cust_phone',
                                    'crm_customers_address.location as cust_location')
                                ->where('sal_invoices.cst_coa_id',$customer->coa_id)
                                
                                ->first();   
                    
                $sale_invoice_detail=DB::table('sal_invoices')
                                                ->join('sale_invoice_detail_fmcg','sale_invoice_detail_fmcg.inv_id','=','sal_invoices.id')
                                                ->join('inv_products','inv_products.id','=','sale_invoice_detail_fmcg.prod_id')
                                                ->select('sale_invoice_detail_fmcg.*','inv_products.name as prod_name','sal_invoices.date as inv_date')
                                                ->where('sal_invoices.cst_coa_id',$customer->coa_id)
                                                ->where($whereDateCluse)
                                                ->get(); 
                                                
                if($sale_invoice_detail && $sale_invoice)
                {
                    $X=11;
                    $Y=$pdf->GetY()+8;
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(30,$LH,'Customer Name:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(50,$LH,$sale_invoice->cust_name, $B, 0, 'L', 0, 'B', 0);
                    $X=$pdf->GetX()+5;
                    $pdf->SetXY($X, $Y);
                    
                    $X=$pdf->GetX()+10;
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(10);
                    $pdf->Cell(30,$LH,'Warehouse:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetX($X);
                    $pdf->Cell(50,$LH,$sale_invoice->warehouse_name, $B, 0, 'L', 0, 'B', 0);

                    $pdf->ln();               
                    $pdf->Cell(30,$LH,'Address:', $B, 0, 'R', 0, 'B', 0);
                    $X=$pdf->GetX();
                    $pdf->SetX($X);
                    $pdf->Cell(80,$LH,$sale_invoice->cust_add.$sale_invoice->cust_location, $B, 0, 'L', 0, 'B', 0);
                    
                    
                    $Y=$pdf->GetY()+10;
                    $pdf->SetXY($X, $Y);
                    $pdf->Ln($Ln);
                    foreach($sale_invoice_detail as $detail)
                    {
                        if($pdf->GetY()>160)
                        {
                            
                            $pdf->AddPage();
                            $pdf->SetY(30);
                            // $tableHeader ='<tr style="background-color:dark-gray;color:white;">
                            //         <th style="width:25px;">No</th>
                            //         <th>Date</th>
                            //         <th style="width:30px;">Qty</th>
                            //         <th>Base Rate</th>
                            //         <th style="width:60px;text-align:center;">Sales Tax</th>
                            //         <th style="width:47px;text-align:center;">FED</th>
                            //         <th style="width:50px;text-align:center;">Further Tax</th>
                            //         <th style="width:55px;text-align:center;">Tax Price</th>
                            //         <th style="width:50px;text-align:center;">L/U</th>
                            //         <th style="width:50px;text-align:center;">Freight</th>
                            //         <th style="width:50px;text-align:center;">Other</th>
                            //         <th style="width:70px;text-align:center;">Value Before Discount</th>
                            //         <th style="width:35px;text-align:center;">Trade Offer</th>
                            //         <th style="width:45px;text-align:center;">Discount</th>
                            //         <th style="width:45px;text-align:center;">Adv Payment</th>
                            //         <th style="width:70px;text-align:center;">Total Amount</th>
                            //         </tr>';
                            // $pdf->writeHTMLCell(0, 0, '', '', $tableHeader, 0, 1, 0, true, '', true);
                        }
                
                        $invoicereport .= '<tr>
                        <td style="font-size:8px; text-align:center;">'.$i.'</td>
                        <td style="font-size:8px;">'.$detail->inv_date.'</td>
                        <td style="font-size:8px;text-align:right">'.number_format($detail->qty_approved).'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->base_rate.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->sales_tax.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->fed.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->further_tax.'</td>
                        <td style="font-size:8px;text-align:right">'.$taxprice.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->lu.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->freight.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->other_discount.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->amount_before_discount.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->trade_offer.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->discount.'</td>
                        <td style="font-size:8px;text-align:right">'.$detail->adv_payment.'</td>
                        <td style="font-size:8px; text-align:right">'.$detail->amount_after_discount.'</td>
                        </tr>';
                        $i++;
                        $taxprice +=$detail->sales_tax + $detail->further_tax + $detail->fed;
                        $totalqty += $detail->qty_approved;
                        $totalsaletax += $detail->sales_tax;
                        $totalfed += $detail->fed;
                        $totalfurthertax += $detail->further_tax;
                        $totaltaxprice += $taxprice;
                        $totallu += $detail->lu;
                        $totalfreight += $detail->freight;
                        $totalother += $detail->other_discount;
                        $totalbeforediscount += $detail->amount_before_discount;
                        $totaltradeoffer += $detail->trade_offer;
                        $totaldiscount += $detail->discount;
                        $totaladvpayment += $detail->adv_payment;
                        $amounttotal += $detail->amount_after_discount;
                    }
                    $invoicereport .='<tr>
                            <td style="text-align:center;" colspan="2">Total </td>
                            <td style="text-align:right;font-size:8px;">'.$totalqty.'</td>
                            <td style="text-align:left;font-size:8px;"></td>
                            <td style="text-align:right;font-size:8px;">'.$totalsaletax.'</td>
                            <td style="text-align:right;font-size:8px">'.$totalfed.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totalfurthertax.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totaltaxprice.'</td>
                            <td style="text-align:right;font-size:8px">'.$totallu.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totalfreight.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totalother.'</td>
                            <td style="text-align:right;font-size:8px">'.$totalbeforediscount.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totaltradeoffer.'</td>
                            <td style="text-align:right;font-size:8px;">'.$totaldiscount.'</td>
                            <td style="text-align:right;font-size:8px">'.$totaladvpayment.'</td>
                            <td style="text-align:right;font-size:8px;">'.$amounttotal.'</td>
                        </tr>';      
                    $html ='
                    <table cellspacing="0" cellpadding="1" border="0.2" style="border-color:gray;">
                    <tr style="background-color:dark-gray;color:white;">
                    <th style="width:25px;">No</th>
                    <th>Date</th>
                    <th style="width:30px;">Qty</th>
                    <th>Base Rate</th>
                    <th style="width:60px;text-align:center;">Sales Tax</th>
                    <th style="width:47px;text-align:center;">FED</th>
                    <th style="width:50px;text-align:center;">Further Tax</th>
                    <th style="width:55px;text-align:center;">Tax Price</th>
                    <th style="width:50px;text-align:center;">L/U</th>
                    <th style="width:50px;text-align:center;">Freight</th>
                    <th style="width:50px;text-align:center;">Other</th>
                    <th style="width:70px;text-align:center;">Value Before Discount</th>
                    <th style="width:35px;text-align:center;">Trade Offer</th>
                    <th style="width:45px;text-align:center;">Discount</th>
                    <th style="width:45px;text-align:center;">Adv Payment</th>
                    <th style="width:70px;text-align:center;">Total Amount</th>
                    </tr>'.$invoicereport.
                    '</table>';
                    // Print text using writeHTMLCell()
                    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                }
            }
    
            $pdf->Output('SaleInvoice.pdf', 'I');
        }
    }

}
