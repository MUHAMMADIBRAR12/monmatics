<?php

namespace App\Http\Controllers\Sale_fmcg;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use App\Libraries\accountsLib;
use App\Libraries\customerLib;
use App\Libraries\swPDF;
use PDF;
use TCPDF;

class SaleInvoiceController extends Controller
{
    public function index()
    {
       $result=DB::table('sal_invoices')
                                ->join('crm_customers','crm_customers.coa_id','sal_invoices.cst_coa_id')
                                ->join('sys_warehouses','sys_warehouses.id','=','sal_invoices.warehouse')
                                ->join('inv_delivery_order_fmcg','inv_delivery_order_fmcg.id','=','sal_invoices.fmcg_do_id') 
                                ->join('sale_order_approvel as soa','soa.id','inv_delivery_order_fmcg.soa_id')
                                ->join('sale_orders','sale_orders.id','soa.so_id')
                                ->select('sal_invoices.id','sal_invoices.editable',DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"),'sal_invoices.date',DB::raw("concat(inv_delivery_order_fmcg.month,'-',LPAD(inv_delivery_order_fmcg.number,4,0)) as do_num"),DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),
                                    'sys_warehouses.name as warehouse_name','crm_customers.name as cust_name')
                                ->where('sal_invoices.note','Sale Invoice Fmcg')
                                ->where('sal_invoices.company_id',session('companyId'))
                                ->get();
        $sale_invoices_fmcg=array();
        foreach($result as $data)
        {
            $sale_inv_detail=DB::table('sale_invoice_detail_fmcg as inv_detail')
                            ->select(DB::raw("SUM(inv_detail.qty_approved) as total_qty"),
                            DB::raw("SUM(inv_detail.amount_after_discount) as total_gross_amount"),
                            DB::raw("SUM(inv_detail.trade_offer) as total_trade_offer"),
                            DB::raw("SUM(inv_detail.discount) as total_discount"),
                            DB::raw("SUM(inv_detail.amount_after_discount) as total_net_amount"))
                            ->where('inv_id','=',$data->id)
                            ->first();
            $row=array(
                'id'=>$data->id,
                'editable'=>$data->editable,
                'inv_num'=>$data->inv_num,
                'so_num'=>$data->so_num,
                'cust_name'=>$data->cust_name,
                'date'=>$data->date,
                'do_num'=>$data->do_num,
                'warehouse_name'=>$data->warehouse_name,
                'total_qty'=>$sale_inv_detail->total_qty,
                'total_gross_amount'=>$sale_inv_detail->total_gross_amount,
                'total_trade_offer'=>$sale_inv_detail->total_trade_offer,
                'total_discount'=>$sale_inv_detail->total_discount,
                'total_net_amount'=>$sale_inv_detail->total_net_amount,
            );
            array_push($sale_invoices_fmcg,$row);
        }
        return view('sale_fmcg.sale_invoice_fmcg_list',compact('sale_invoices_fmcg'));
    }

    public function printinvoice($id) 
    {

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
                        ->where('sal_invoices.id',$id)
                        ->first();
                        ///dd(customerLib::customerAccountDetail($sale_invoice->cust_coa_id));
          //  dd($sale_invoice);
        $sale_invoice_fmcg_detailspdf=DB::table('sal_invoices')
                                    ->join('sale_invoice_detail_fmcg','sale_invoice_detail_fmcg.inv_id','=','sal_invoices.id')
                                    ->join('inv_products','inv_products.id','=','sale_invoice_detail_fmcg.prod_id')
                                    ->select('sale_invoice_detail_fmcg.*','inv_products.name as prod_name')
                                    ->where('sal_invoices.id',$id)
                                    ->get();      
      
        $pdf = new swPDF();
        //$pdf= new TCPDF; 
        $X=78; $Y=20;$Ln=5; $B=0; $LH=6; $W=50;
        $pdf->setH3();
        $pdf->AddPage('L', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
        $X=10; $Y=30;
        $pdf->SetTitle('Sale Invoice');
        $Y=$pdf->GetY()+25;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont();
        $pdf->Cell($pdf->getPageWidth()-($X*2), 10, 'Sale Invoice', $B, 0, 'R', 0, 'B', 0);
        $Y=$pdf->GetY()+16;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(70,$LH,'Bill To:', $B);
        $pdf->Ln($Ln);
        $Y=$pdf->GetY()+3;
        $pdf->SetXY($X,$Y);
        $pdf->setT1(14);
        $pdf->Cell(90,$LH,$sale_invoice->cust_name, $B);
        $pdf->Ln($Ln);
        $pdf->MultiCell(90, $LH,$sale_invoice->cust_add . $sale_invoice->cust_location, $B, 'L', 0, 0, '', '', true);
        $pdf->Ln();
        $pdf->Cell(90,$LH,$sale_invoice->cust_phone, $B);
        $X +=220;
        $Y=53;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Document:',$B, 0, 'R', 0, 'B', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Ln();
        $Y=$pdf->GetY();
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Date:',$B, 0, 'R', 0, 'B', 0);
        $Y=53;
        $X+=30;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(25,$LH,$sale_invoice->sale_inv_num,$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+6;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(25,$LH,appLib::setDateFormat($sale_invoice->date),$B, 0, 'L', 0, 'B', 0);
        $X=225;
        $Y=$pdf->GetY()+6;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(42,$LH,'Total Balance Due:',$B, 0, 'L', 0, 'B', 0);
        //$Y+=7;
        $Y=$pdf->GetY();
        $X+=35;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(37,$LH,customerLib::customerAccountDetail($sale_invoice->cust_coa_id),$B, 0,'L', 0, 'B', 0); //customerLib::customerDetail($sale_invoice->cust_coa_id)
        $X=140;
        $Y=$pdf->GetY()+10;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'DO NO:',$B, 0, 'R', 0, 'B', 0);
        $Y=$pdf->GetY()+5;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'SO NO:',$B, 0, 'R', 0, 'B', 0);
        $X=170;
        $Y=75;
        //$X=$pdf->GetX();
        //$Y=20;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$sale_invoice->do_num,$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+5;
        $pdf->setT1();
        $pdf->SetXY($X,$Y);
        $pdf->Cell(30,$LH,$sale_invoice->so_num,$B, 0, 'L', 0, 'B', 0);
        $X=210;
        $Y=75;
        //$Y=$pdf->GetY();
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'DO Date:',$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+5;
        $X=205;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Warehouse:',$B, 0, 'L', 0, 'B', 0);
        
        $X=228;
        $Y=75;
        //$X=$pdf->GetX();
        //$Y=20;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,appLib::setDateFormat($sale_invoice->due_date),$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+6;
        $pdf->SetXY($X,$Y);
        $pdf->MultiCell(70, $LH,$sale_invoice->warehouse_name, $B, 'L', 0, 0, '', '', true);

        $Y=$pdf->GetY()+5;
        $X=132;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(38,$LH,'Sale Representative:',$B, 0, 'R', 0, 'B', 0);

        $Y=$pdf->GetY();
        $X=$pdf->GetX();
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$sale_invoice->sale_order_by,$B, 0, 'L', 0, 'B', 0);
        $X=190;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(38,$LH,'Confirmed By:',$B, 0, 'R', 0, 'B', 0);
        $X=$pdf->GetX();
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(38,$LH,$sale_invoice->delivery_confirm_by,$B, 0, 'L', 0, 'B', 0);

        $pdf->Ln($Ln+5);

        $PDFLineItem = "";
        $totalfurthertax =0;
        $totalqty =0;
        $totalsaletax=0;
        $totalfed=0;
        $totaldis=0;
        $totaltradeoffer=0;
        $netamount=0;
        $totaltax=0;
        $totallu=0;
        $Amount = 0;
        $totalfreight=0;
        $totaladv_payment=0;
        $i=0;
        $tax =0;
        $totalrate=0;
        $totalamount_before_discount=0;
        $totalotherdiscount=0;
        $cssClass='';
        foreach($sale_invoice_fmcg_detailspdf as $lineItem)
        {
            $cssClass = ($cssClass=="bg1")?"bg2":"bg1";
            $PDFLineItem .='            
            <tr class="'.$cssClass.'">
             <td style="text-align:left; font-size:8px;">&nbsp;&nbsp;'.$lineItem->prod_name.'</td>
             <td style="text-align:center; font-size:8px;">'.number_format($lineItem->qty_approved,0).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->base_rate,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->sales_tax,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->fed,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->further_tax,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($tax,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->lu,2).'</td>
             <td class="fontstyle1">'.number_format($lineItem->freight,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->other_discount,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->amount_before_discount,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->trade_offer,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->discount,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1">'.number_format($lineItem->adv_payment,2).'&nbsp;&nbsp;</td>
             <td class="fontstyle1" >'.number_format($lineItem->amount_after_discount,2).'&nbsp;&nbsp;</td>
        </tr>
            ';
            $i++;



            $totalfurthertax += $lineItem->further_tax;
            $totalqty +=   $lineItem->qty_approved;
            $totalsaletax +=$lineItem->sales_tax;
            $totalfed +=$lineItem->fed;
            $totaldis += $lineItem->discount;
            $totaltradeoffer += $lineItem->trade_offer;
            $netamount += $lineItem->amount_after_discount;
            $tax +=$lineItem->base_rate+$lineItem->sales_tax+$lineItem->fed+$lineItem->further_tax;
            $totalrate +=$lineItem->base_rate;
            $totaltax +=$tax;
            $totallu +=$lineItem->lu;
            $totalfreight +=$lineItem->freight;
            $totalotherdiscount +=$lineItem->other_discount;
            $totalamount_before_discount +=$lineItem->amount_before_discount;
            $totaladv_payment +=$lineItem->adv_payment;

        }
   

        $PDFLineItem .='            
            <tr style="background-color:white;">
             <td style="text-align:center; font-size: 10px;">Total</td>
             <td style="text-align:center; font-size: 8px;">'.$totalqty.'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format( $totalrate,2).'</td>
             <td class="fontstyle">'.number_format($totalsaletax,2).'</td>
             <td class="fontstyle">'.number_format($totalfed,2).' &nbsp;&nbsp;</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totalfurthertax,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totaltax,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totallu,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totalfreight,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totalotherdiscount,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totalamount_before_discount,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totaltradeoffer,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totaldis,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($totaladv_payment,2).'</td>
             <td style="text-align:right; font-size: 8px;">'.number_format($netamount,2).'</td>
        </tr>
            ';
        
        $html = '<style>'.file_get_contents(asset('public/assets/css/list.css')).'</style>';
      //  $html .= <<<EOD
      $html .= '
      
     <table class="table">
     <tr class="bg3">
        <th style="width:140;text-align:center; font-size: 10px;">Product</th>
        <th style="width:18;text-align:center;font-size: 10px;">Qty</th>
        <th style="width:50;text-align:center;font-size: 10px;">Base Rate</th>
		<th style="width:40;text-align:center;font-size: 10px;">Sales Tax</th>
        <th style="width:30;text-align:center;font-size: 10px;">FED</th>
        <th style="width:50;text-align:center;font-size: 10px;">Further Tax</th>
         <th style="width:40;text-align:center;font-size: 10px;">Tax Price</th>
         <th style="width:30;text-align:center;font-size: 10px;">L/U</th>
        <th style="width:50;text-align:center;font-size: 10px;">Freight</th>
         <th style="width:40;text-align:center;font-size: 10px;">Other</th>
         <th style="width:60;text-align:center;font-size: 10px;">Value Before Discount</th>
         <th style="width:50;text-align:center;font-size: 10px;">Trade Offer</th>
         <th style="width:50;text-align:center;">Discount</th>
         <th style="width:45;text-align:center;">Adv Payment</th>
         <th style="width: 80px;"> Total Amount </th>
        
    
     </tr>'.
     $PDFLineItem.'
</table>';
//EOD;

    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    $pdf->Ln();
    $pdf->GetY($Y);
    $X=30; $Y=150;$Ln=5; $B=0; $LH=6; $W=50;
    $Y=($Y<175)?175:$Y;  
    $B=0;
    $XW=60;
    $pdf->SetXY($X,$Y);
    $pdf->setT1();
    $pdf->Cell($XW,$LH,$sale_invoice->invoice_by,$B, 0, 'C', 0, 'B', 0);
    $Y=$pdf->GetY()+5;
    $pdf->SetXY($X,$Y);
    $pdf->setH3();
   
    $pdf->Cell($XW,$LH,'Prepared By',$B, 0, 'C', 0, 'B', 0);
    $pdf->SetX($pdf->GetX());
    $pdf->Cell($XW,$LH,'Verified By',$B, 0, 'C', 0, 'B', 0);
    $pdf->SetX($pdf->GetX());
    $pdf->Cell($XW,$LH,'Approved By',$B, 0, 'C', 0, 'B', 0);
    $pdf->SetX($pdf->GetX());
    $pdf->Cell($XW,$LH,'Received By',$B, 0, 'C', 0, 'B', 0);
    $Y=150;
    $X=$pdf->GetX()+40;
    $pdf->SetXY($X,$Y);
    $pdf->setT1();
    $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

    $Y=$pdf->GetY()+5;
    $pdf->SetXY($X,$Y);
    $pdf->setH3();
    


    $Y=150;
    $X=$pdf->GetX()+40;
    $pdf->SetXY($X,$Y);
    $pdf->setT1();
    $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

    $Y=$pdf->GetY()+5;
    $pdf->SetXY($X,$Y);
    $pdf->setH3();
    
    $Y=150;
    $X=$pdf->GetX()+40;
    $pdf->SetXY($X,$Y);
    $pdf->setT1();
    $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

    $Y=$pdf->GetY()+5;
    $pdf->SetXY($X,$Y);
    $pdf->setH3();
    
    
        $pdf->CreatedAt= $sale_invoice->created_at;

   


       $pdf->Output('sale_invoice.pdf', 'I');

    }
    public function form($id=null)
    {
        if($id)
        {
            $sal_invoice=DB::table('sal_invoices')
                        ->join('inv_delivery_order_fmcg as do','do.id','=','sal_invoices.fmcg_do_id')
                        ->join('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                        ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                        ->join('crm_customers_address','crm_customers_address.cust_id','=','crm_customers.id')
                        ->join('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                        ->join('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                        //->join('users','users.id','=','sale_orders.created_by')
                        ->select('sal_invoices.*','do.date as do_date',DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_num"),'do.do_tracking_date',
                                'sys_warehouses.name as warehouse_name',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'sale_orders.date as so_date',
                                'crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id','crm_customers_address.address as cust_add','crm_customers_address.phone as cust_phone','crm_customers_address.location as cust_location')
                        ->where('sal_invoices.id',$id)
                        ->first();
            $current_balance=customerLib::customerAccountDetail($sal_invoice->cust_coa_id);
            
            //dd($sal_invoice);
            $sal_invoice_detail=DB::table('sale_invoice_detail_fmcg')
                                ->join('inv_products','inv_products.id','=','sale_invoice_detail_fmcg.prod_id')
                                ->select('sale_invoice_detail_fmcg.*','inv_products.name as prod_name')
                                ->where('sale_invoice_detail_fmcg.inv_id',$id)
                                ->get();
            return view('sale_fmcg.sale_invoice_fmcg',compact('sal_invoice','sal_invoice_detail','current_balance'));
        }
        else
        {
            $whereDateCluse = array();
            array_push($whereDateCluse,array ('inv_delivery_order_fmcg.status', '=','Delivered'));
            array_push($whereDateCluse,array ('inv_delivery_order_fmcg.close_status', '=',0));
            $delivery_orders=dbLib::getDocumentNumbers('inv_delivery_order_fmcg',$whereDateCluse);
            return view('sale_fmcg.sale_invoice_fmcg',compact('delivery_orders'));
        }
    }

    public function do_detail(Request $request)
    {
        $do_detail_arr=array();
        $do_details=DB::table('inv_delivery_order_fmcg as do')
                        ->join('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                        ->join('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                        ->join('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                        ->join('users','users.id','=','sale_orders.created_by')
                        ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                        ->join('crm_customers_address','crm_customers_address.cust_id','=','crm_customers.id')
                        ->select('do.date as do_date','do.cust_id as do_cust_id','sys_warehouses.name as warehouse','sys_warehouses.id as warehouse_id',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'sale_orders.date as so_date',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id','crm_customers_address.address as cust_add','crm_customers_address.phone as cust_phone','crm_customers_address.location as cust_location')
                        ->where('do.id',$request->do_id)
                        ->first();
        $result=DB::table('inv_delivery_order_fmcg as do')
                        ->join('inv_delivery_order_detail_fmcg as do_detail','do_detail.do_id','=','do.id')
                        ->join('sale_order_detail_approvel as soa_detail_tb','soa_detail_tb.id','=','do_detail.soa_detail_id')
                        ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                        ->select('do_detail.*','do_detail.id as do_detail_id','inv_products.name as prod_name','inv_products.coa_id as prod_coa_id','soa_detail_tb.*')
                        ->where('do.id',$request->do_id)
                        ->get();
        foreach($result as $do_detail)
        {
            $data=array(
                'header'=>$do_details,
                'balance'=>customerLib::customerAccountDetail($do_details->cust_coa_id),
                'prod_id'=>$do_detail->prod_id,
                'prod_name'=>$do_detail->prod_name,
                'prod_coa_id'=>$do_detail->prod_coa_id,
                'do_detail_id'=>$do_detail->do_detail_id,
                'soa_detail_id'=>$do_detail->soa_detail_id,
                'qty'=>$do_detail->qty_delivered,
                'base_rate'=>$do_detail->base_rate / $do_detail->qty_approved,
                'sales_tax'=>$do_detail->sales_tax / $do_detail->qty_approved * $do_detail->qty_delivered,
                'fed'=>$do_detail->fed / $do_detail->qty_approved * $do_detail->qty_delivered,
                'further_tax'=>$do_detail->further_tax / $do_detail->qty_approved * $do_detail->qty_delivered,
                'tax_price'=>($do_detail->sales_tax / $do_detail->qty_approved * $do_detail->qty_delivered)+($do_detail->fed / $do_detail->qty_approved * $do_detail->qty_delivered)+($do_detail->further_tax / $do_detail->qty_approved * $do_detail->qty_delivered),
                'lu'=>$do_detail->lu / $do_detail->qty_approved * $do_detail->qty_delivered,
                'freight'=>$do_detail->freight / $do_detail->qty_approved * $do_detail->qty_delivered,
                'other'=>$do_detail->other_discount / $do_detail->qty_approved * $do_detail->qty_delivered,
                'fixed_margin'=>$do_detail->fixed_margin / $do_detail->qty_approved * $do_detail->qty_delivered,
                'amount_before_discount'=>$do_detail->amount_before_discount / $do_detail->qty_approved * $do_detail->qty_delivered,
                'trade_offer'=>$do_detail->trade_offer / $do_detail->qty_approved * $do_detail->qty_delivered,
                'discount'=>$do_detail->discount / $do_detail->qty_approved * $do_detail->qty_delivered,
                'adv_payment'=>$do_detail->adv_payment / $do_detail->qty_approved * $do_detail->qty_delivered,
                'total_amount'=>$do_detail->amount_after_discount / $do_detail->qty_approved * $do_detail->qty_delivered,
            );
            array_push($do_detail_arr,$data);
        }
        return $do_detail_arr; 
    }

    public function save(Request $request)
    {
       
        //dd($request->input());
        //sale invoice fmcg record and this record insert into sale-invoice table
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('sal_invoices');
        $sale_invoice_fmcg=array(
           // 'id'=>$id,
            'cst_coa_id'=>$request->do_cust_id,
            'fmcg_do_id'=>$request->do_id,
            'date'=>$request->date,
            'due_date'=>$request->due_date,
            'note'=>'Sale Invoice Fmcg',
            'month'=>$month,
            'number'=>$number,
            'warehouse'=>$request->warehouse_id,
            'total_inv_amount'=>$request->total_net_amount,
        );
        if($request->id)
        {
            $sale_invoice_fmcg['updated_at']= Carbon::now();
            $sale_invoice_fmcg['date']=$request->date;
            $sale_invoice_fmcg['due_date']= $request->due_date;
            DB::table('sal_invoices')->where('id',$request->id)->update($sale_invoice_fmcg);
            $transmains=DB::table('fs_transdetails')->select('trm_id')->where('recv_invoice_id',$request->id)->groupBy('trm_id')->get();
            foreach($transmains as $trm)
            {
                DB::table('fs_transmains')->where('id',$trm->trm_id)->update(['date'=>$request->date]);
            }
            return redirect('Sales_Fmcg/SaleInvoice/List');
        }
        else
        {
            $inv_id=str::uuid()->toString();
            $sale_invoice_fmcg['number']= dbLib::getNumber('sal_invoices');
            $sale_invoice_fmcg['id']=$inv_id;
            $sale_invoice_fmcg['company_id']= session('companyId');
           // $sale_invoice_fmcg['trans_id']= $trm_id;
            $sale_invoice_fmcg['created_by']= Auth::id();
            $sale_invoice_fmcg['created_at']= Carbon::now();
            DB::table('sal_invoices')->insert($sale_invoice_fmcg);
        }
        // ***************************************** First Sale transaction start ************************************* //
        $transMains=array(
            "id"=>$request->trans_id,
            "date"=>$request->date,
            "voucher_type"=>8,
            "note"=>$request->note,
        );
        $trm_id=accountsLib::saveTransMain($transMains);
        $description='Sale Invoice Against invoice: '.dbLib::getSpecialDocument('sal_invoices',$inv_id).' & D.O: '.dbLib::getSpecialDocument('inv_delivery_order_fmcg',$request->do_id);
        // Customer Debit Transaction
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>$request->do_cust_id,
            "description"=>$description,
            "debit"=>$request->total_net_amount,
            "credit"=>0,
            "recv_invoice_id"=>$inv_id,
        );
        accountsLib::saveTransDetail($transDetails);
        // sale Credit Transaction. 
        $transDetails=array(
            "trm_id"=>$trm_id,
            "coa_id"=>67,
            "description"=>$description,
            "debit"=>0,
            "credit"=>$request->total_net_amount,
            "recv_invoice_id"=>$inv_id,
        );
        accountsLib::saveTransDetail($transDetails);
        // ***************************************** First transaction complete ************************************* //
        //save and update Good Received Detail Record
        $this->updateSaleInvoiceDetail($inv_id,$description, $request);
        //also update do tracking status when sale invoice created
        DB::table('inv_delivery_order_fmcg')->where('id',$request->do_id)->update(['do_tracking_edit'=>0]);
        DB::table('inv_delivery_order_fmcg')->where('id',$request->do_id)->update(['close_status'=>1]);
        return redirect('Sales_Fmcg/SaleInvoice/List');
    }

    private function updateSaleInvoiceDetail($inv_id,$description, $request)
    {
        //sale invoice detail FMCG.
        $count = count($request->item_ID);
        $inv_total_amount=0;
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $sale_inv_det_fmcg=array(
                'id'=>$id,
                'inv_id'=>$inv_id,
                'do_detail_id'=>$request->do_detail_id[$i],
                'soa_detail_id'=>$request->soa_detail_id[$i],
                'prod_id'=>$request->item_ID[$i],
                'qty_approved'=>$request->qty[$i],
                'base_rate'=>$request->base_rate[$i],
                'sales_tax'=>$request->sales_taX[$i],
                'fed'=>$request->fed[$i],
                'further_tax'=>$request->further_tax[$i],
                'inclusive_value'=>$request->tax_price[$i],
                'lu'=>$request->lu[$i],
                'freight'=>$request->freight[$i],
                'other_discount'=>$request->other[$i],
                'fixed_margin'=>$request->fixed_margin[$i],
                'amount_before_discount'=>$request->amount_before_discount[$i],
                'amount_after_discount'=>$request->net_amount[$i],
                'adv_payment'=>$request->adv_payment[$i],
                'trade_offer'=>$request->trade_offer[$i],
                'discount'=>$request->discount[$i],
                'display'=>$i+1,
            );
            DB::table('sale_invoice_detail_fmcg')->insert($sale_inv_det_fmcg);
            $inv_total_amount +=$request->qty[$i] * $request->base_rate[$i];
        }

        // ********************************************** 2nd Transaction ********************************************************
        $transMains=array(
            "id"=>$request->trans_id,
            "date"=>$request->date,
            "voucher_type"=>8,
            "note"=>$request->note,
        );
        $trm_id=accountsLib::saveTransMain($transMains);
            //****************************************************  2nd  Transactions details  **************************************
            // ***************************** Discounts Effect in Transdetail

            //sales debit
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>67,
                "description"=>$description,
                "debit"=>$request->total_net_amount,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            //trans detail total l/u
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','L/U')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_lu,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total freight
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','Freight')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_freight,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total other
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','Other')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_other,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total margin
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','Margin')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_margin,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);
            
            //trans detail total trade offer
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','Trade Offer')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_trade_offer,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total discounts
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_discounts')->select('coa_id')->where('name','=','discount')->first()->coa_id,
                "description"=>$description,
                "debit"=>$request->total_discount,
                "credit"=>0,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail inventory
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>$request->prod_coa_id,
                "description"=>$description,
                "debit"=>0,
                "credit"=>$inv_total_amount,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);
    
            //trans detail total sales tax
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_taxes')->select('coa_id')->where('name','Sales Tax')->first()->coa_id,
                "description"=>$description,               
                "debit"=>0,
                "credit"=>$request->total_sales_tax,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total further tax
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_taxes')->select('coa_id')->where('name','Further Tax')->first()->coa_id,
                "description"=>$description,
                "debit"=>0,
                "credit"=>$request->total_further_tax,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

            //trans detail total fed
            $transDetails=array(
                "trm_id"=>$trm_id,
                "coa_id"=>DB::table('sys_taxes')->select('coa_id')->where('name','Fed Tax')->first()->coa_id,
                "description"=>$description,
                "debit"=>0,
                "credit"=>$request->total_fed_tax,
                "recv_invoice_id"=>$inv_id,
            );
            accountsLib::saveTransDetail($transDetails);

    }

    public function view($id)
    {

        $sale_invoice=DB::table('sal_invoices')
                        ->join('sys_warehouses','sys_warehouses.id','=','sal_invoices.warehouse')
                        ->join('inv_delivery_order_fmcg as do','do.id','=','sal_invoices.fmcg_do_id')
                        ->join('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                        ->join('sale_orders','sale_orders.id','=','sale_order_approvel.so_id')
                        ->join('users','users.id','=','sale_orders.created_by')
                        ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                        ->join('crm_customers_address','crm_customers_address.cust_id','=','crm_customers.id')
                        ->select('sal_invoices.*',DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as do_num"),'do.date as do_date','sys_warehouses.name as warehouse_name',
                                DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'sale_orders.date as so_date',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name','crm_customers.coa_id as cust_coa_id','crm_customers_address.address as cust_add','crm_customers_address.phone as cust_phone','crm_customers_address.location as cust_location')
                        ->where('sal_invoices.id',$id)
                        ->first();
        $cust_coa_id=(isset($sale_invoice->cust_coa_id)) ? $sale_invoice->cust_coa_id : '';
        $current_balance=customerLib::customerAccountDetail($cust_coa_id);
        $sale_invoice_fmcg_details=DB::table('sal_invoices')
                                    ->join('sale_invoice_detail_fmcg','sale_invoice_detail_fmcg.inv_id','=','sal_invoices.id')
                                    ->join('inv_products','inv_products.id','=','sale_invoice_detail_fmcg.prod_id')
                                    ->select('sale_invoice_detail_fmcg.*','inv_products.name as prod_name')
                                    ->where('sal_invoices.id',$id)
                                    ->get();
        return view('sale_fmcg.sale_invoice_fmcg_view',compact('sale_invoice','sale_invoice_fmcg_details','current_balance'));
        
    }
}
