<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\Auth;
use App\Libraries\appLib;
use App\Libraries\inventoryLib;
use App\Libraries\swPDF;
use PDF;
use TCPDF;

class InventoryReportsController extends Controller
{
    public function product()
    {
        return view('reports.product_detail');
    }

    public function productDetail(Request $request)
    {
        $companyId = session('companyId');
        $productId = $request->item_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $result = DB::table('inv_inventories')
            ->select()
            ->where('prod_id', '=', $productId)
            ->where('company_id', '=', $companyId)
            ->orWhere(function ($query) use ($productId, $companyId, $from_date, $to_date) {
                $query->where('prod_id', '=', $productId)
                    ->where('company_id', '=', $companyId)
                    ->whereBetween('date', [$from_date, $to_date]);
            })
            ->get();
        return $result;
    }

    public function printtcpdflist()
    {
        return view('reports.good_received');
    }

    public function printtcpdf(Request $request)
    {
        $whereVendorCluse = array();
        $whereDateCluse = array();
        if ($request->vendor_ID) {
            array_push($whereVendorCluse, array('pa_vendors.id', '=', $request->vendor_ID));
        }
        if ($request->from_date) {
            $arrFromDate = array('inv_grns.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('inv_grns.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
        $vendors = DB::table('pa_vendors')->select('id')->where($whereVendorCluse)->get();
        //dd($vendors);
        $X = 78;
        $Y = 20;
        $Ln = 5;
        $B = 0;
        $LH = 5;
        $W = 50;
        $pdf = new swPDF();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetHeaderMargin(50);
        $pdf->setH3();
        $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);

        $X = 10;
        $Y = 30;
        $pdf->SetTitle('Goods Receipt Note');
        $Y = $pdf->GetY() + 15;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont2();
        $pdf->Cell($pdf->getPageWidth() - ($X * 2), 10, 'Goods Receipt Note', $B, 0, 'C', 0, 'B', 0);
        $Y = $pdf->GetY() + 15;
        $X = 10;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(10, $LH, 'From:', $B, 0, 'R', 0, 'B', 0);
        $pdf->setH22();
        $pdf->Cell(20, $LH, $request->from_date, $B, 0, 'L', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, 'To:', $B, 0, 'R', 0, 'B', 0);
        $pdf->setH22();
        $pdf->Cell(20, $LH, $request->to_date, $B, 0, 'L', 0, 'B', 0);
        $X = $pdf->GetX() + 50;
        $pdf->SetXY($X, $Y);
        $user = DB::table('users')->select(DB::raw("concat(users.firstName,' ',users.lastName) as user_name"))->where('id', Auth::id())->first();
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, 'User:', $B, 0, 'R', 0, 'B', 0);
        $pdf->Cell(40, $LH, $user->user_name, $B, 0, 'L', 0, 'B', 0);
        foreach ($vendors as $vendor) {
            $vendordetails = DB::table('inv_grns')
                ->join('pa_vendors', 'pa_vendors.id', '=', 'inv_grns.ven_id')
                ->join('inv_grn_details as grnd', 'grnd.grn_id', '=', 'inv_grns.id')
                ->join('inv_products as p', 'p.id', '=', 'grnd.prod_id')
                ->join('pur_purchase_orders as po', 'po.id', '=', 'inv_grns.po_id')
                ->join('pur_purchase_order_details', function ($join) {
                    $join->on('pur_purchase_order_details.po_id', '=', 'po.id');
                    $join->on('pur_purchase_order_details.prod_id', '=', 'grnd.prod_id');
                })
                ->select(
                    'inv_grns.*',
                    'po.date as po_date',
                    'pa_vendors.name as ven_name',
                    'pur_purchase_order_details.qty_received as pqtyr',
                    DB::raw("concat(inv_grns.month,'-',LPAD(inv_grns.number,4,0)) as grn_num"),
                    'inv_grns.date as grn_date',
                    'grnd.qty_received as grn_qtyr',
                    'grnd.rate as grn_rate',
                    'p.name as pname',
                    'p.code as pcode',
                    DB::raw("concat(po.month,'-',LPAD(po.number,4,0)) as po_num")
                )
                ->where('inv_grns.ven_id', $vendor->id)
                ->where($whereDateCluse)
                ->get();

            $vendor = DB::table('inv_grns as grn')
                ->join('pa_vendors as paven', 'paven.id', '=', 'grn.ven_id')
                ->join('sys_warehouses as wh', 'wh.id', '=', 'grn.warehouse')
                ->join('users', 'users.id', '=', 'grn.user_id')
                // ->join('inv_products as p','p.id','=','grn.ven_id')
                ->join('pa_vendor_details as pavend', 'paven.id', '=', 'pavend.pa_ven_id')
                ->select(
                    'grn.*',
                    'paven.name as ven_name',
                    'wh.name as whname',
                    'users.name as usname',
                    'pavend.location as venloc',
                    'pavend.address as venadd'
                )
                ->where('grn.ven_id', $vendor->id)
                ->first();

            if ($vendordetails && $vendor) {
                $X = 10;
                $Y = $pdf->GetY() + 5;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10);
                $pdf->Cell(10, $LH, 'Name:', $B, 0, 'R', 0, 'B', 0);
                $pdf->Cell(50, $LH, $vendor->ven_name, $B, 0, 'L', 0, 'B', 0);
                $X = $pdf->GetX();
                $pdf->SetXY($X, $Y);
                $pdf->Cell(20, $LH, 'Address:', $B, 0, 'R', 0, 'B', 0);
                $pdf->Cell(20, $LH, $vendor->venadd . $vendor->venloc, $B, 0, 'L', 0, 'B', 0);
                $X = $pdf->GetX() + 20;
                $pdf->SetXY($X, $Y);
                $pdf->Cell(20, $LH, 'Store:', $B, 0, 'R', 0, 'B', 0);
                $pdf->Cell(40, $LH, $vendor->whname, $B, 0, 'L', 0, 'B', 0);
                $pdf->SetXY($X, $Y);
                $pdf->Ln($Ln + 5);
                $X = 10;
                $Y = $pdf->GetY() + 5;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(10, 'B');
                $pdf->Cell(15, $LH, 'PO No', $B, 0, 'L', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(18, $LH, 'PO Date', $B, 0, 'C', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(17, $LH, 'GRN No', $B, 0, 'C', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(18, $LH, 'GRN Date', $B, 0, 'C', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(58, $LH, 'Product Name', $B, 0, 'L', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(13, $LH, 'Pcs Order', $B, 0, 'C', 0, 'B', 0);
                $X = $pdf->GetX();
                $pdf->SetXY($X, $Y);

                $pdf->Cell(22, $LH, 'Pcs Receipt', $B, 0, 'R', 0, 'B', 0);
                $X = $pdf->GetX();

                $pdf->Cell(13, $LH, 'Rate', $B, 0, 'C', 0, 'B', 0);
                $X = $pdf->GetX();

                $X = 10;
                $pdf->Cell(13, $LH, 'Amount', $B, 0, 'C', 0, 'B', 0);
                $pdf->rect($X, $Y, 188, $LH + 1);
                $i = 0;
                $pcstotal = 0;
                $receipttotal = 0;
                $amt = 0;
                foreach ($vendordetails as $lineItem) {
                    if ($pdf->GetY() > 250) {
                        $pdf->AddPage();
                        $pdf->SetY(30);
                    }
                    $X = 10;
                    $pdf->Ln();
                    $Y = $pdf->GetY() + 3;
                    $pdf->SetXY($X, $Y);
                    $pdf->setT1(8);
                    $pdf->Cell(15, $LH, $lineItem->po_num, $B, 0, 'L', 0, 'B', 0);
                    $pdf->setT1(8);
                    $pdf->Cell(18, $LH, $lineItem->po_date, $B, 0, 'R', 0, 'B', 0);
                    $pdf->Cell(17, $LH, $lineItem->grn_num, $B, 0, 'R', 0, 'B', 0);
                    $pdf->Cell(18, $LH, $lineItem->grn_date, $B, 0, 'R', 0, 'B', 0);
                    $PX = $pdf->GetX();
                    $Y = $pdf->GetY() + 1;
                    $pdf->SetXY($X, $Y);
                    $pdf->SetX($PX + 58);
                    $X = $pdf->GetX();
                    $pdf->Cell(13, $LH, number_format($lineItem->pqtyr, 0), $B, 0, 'R', 0, 'B', 0);
                    $pdf->Cell(22, $LH, number_format($lineItem->grn_qtyr, 0), $B, 0, 'R', 0, 'B', 0);
                    $pdf->Cell(13, $LH, number_format($lineItem->grn_rate, 2), $B, 0, 'R', 0, 'B', 0);
                    $pdf->Cell(13, $LH, $lineItem->grn_qtyr * $lineItem->grn_rate, $B, 0, 'R', 0, 'B', 0);
                    $pdf->SetX($PX);
                    $pdf->MultiCell(58, $LH, $lineItem->pname, $B, 'L', 0, 0, '', '', true);
                    $i++;
                    $pcstotal += $lineItem->pqtyr;
                    $receipttotal += $lineItem->grn_qtyr;
                    $amt += $lineItem->grn_qtyr * $lineItem->grn_rate;
                }
                $pdf->Ln();
                $Y = $pdf->GetY() + 5;
                $X = 20;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(9, 'B');
                $pdf->Cell(20, $LH, 'No Of Order:', $B, 0, 'R', 0, 'B', 0);
                $X = $pdf->GetX();
                $pdf->setT1(8);
                $pdf->SetXY($X, $Y);
                $pdf->Cell(20, $LH, $i, $B, 0, 'L', 0, 'B', 0);
                $X = $pdf->GetX() + 44;
                $pdf->SetXY($X, $Y);
                $pdf->setT1(9, 'B');
                $pdf->Cell(15, $LH, 'Total:', $B, 0, 'R', 0, 'B', 0);
                $X = $pdf->GetX();
                $pdf->SetXY($X, $Y);
                $pdf->setT1(8);
                $pdf->Cell(15, $LH, $pcstotal, $B, 0, 'R', 0, 'B', 0);
                $X = $pdf->GetX();
                $pdf->SetXY($X, $Y);
                $pdf->Cell(16, $LH, $receipttotal, $B, 0, 'R', 0, 'B', 0);
                $X = $pdf->GetX() + 13;
                $pdf->SetXY($X, $Y);
                $pdf->Cell(16, $LH, $amt, $B, 0, 'R', 0, 'B', 0);
                $pdf->Ln($Ln);
                $X = 10;
                $pdf->rect($X, $Y, 188, $LH + 1);
            }
        }
        $pdf->Output('Good Receipt.pdf', 'I');
    }

    public function grn_wip_print($id)
    {

        $grn = DB::table('inv_grns')
            ->leftJoin('inv_grn_wip_outsource as grn_wip', 'grn_wip.grn_id', '=', 'inv_grns.id')
            ->leftJoin('inv_inventories', 'inv_inventories.source_id', '=', 'inv_grns.id')
            ->leftJoin('inv_products', 'inv_products.id', '=', 'inv_inventories.prod_id')
            ->leftJoin('sys_warehouses', 'sys_warehouses.id', '=', 'inv_grns.warehouse')
            ->leftJoin('users', 'users.id', '=', 'inv_grns.user_id')
            ->select('inv_grns.*', 'grn_wip.batch', 'users.name as uname', 'sys_warehouses.name as wname', 'grn_wip.project', 'inv_inventories.prod_id', 'inv_products.name as prod_name', 'inv_inventories.unit', 'inv_inventories.qty_in', 'inv_inventories.rate', 'inv_inventories.amount')
            ->where('inv_grns.id', $id)
            ->distinct('grn_wip.grn_id')
            ->first();
        //dd($grn);

        //Product Issue Details

        $pi_detail = DB::table('inv_grns')
            ->leftJoin('inv_grn_wip_outsource as grn_wip', function ($join) {
                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                    ->where('grn_wip.name', '=', 'Own Product');
            })
            ->leftJoin('inv_products', 'inv_products.id', '=', 'grn_wip.prod_id')
            ->select('grn_wip.*', 'inv_products.name as prod_name')
            ->where('inv_grns.id', $id)
            ->orderBy('grn_wip.display')
            ->get();
        //dd($pi_detail);
        $pv_detail = DB::table('inv_grns')
            ->leftJoin('inv_grn_wip_outsource as grn_wip', function ($join) {
                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')

                    ->where('grn_wip.name', '=', 'Ven Product');
            })
            ->leftJoin('inv_products', 'inv_products.id', '=', 'grn_wip.prod_id')
            ->select('grn_wip.*', 'inv_products.name as prod_name')
            ->where('inv_grns.id', $id)
            ->orderBy('grn_wip.display')
            ->get();
        $sv_detail = DB::table('inv_grns')
            ->leftJoin('inv_grn_wip_outsource as grn_wip', function ($join) {
                $join->on('inv_grns.id', '=', 'grn_wip.grn_id')
                    ->where('grn_wip.category', '=', 'Service');
            })
            ->leftJoin('inv_products', 'inv_products.id', '=', 'grn_wip.prod_id')
            ->select('grn_wip.*', 'inv_products.name as prod_name')
            ->where('inv_grns.id', $id)
            ->orderBy('grn_wip.display')
            ->get();
        $totalPiQty = 0;
        $totalPiAmount = 0;
        $X = 78;
        $Y = 20;
        $Ln = 5;
        $B = 0;
        $LH = 5;
        $W = 20;
        $pdf = new swPDF();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetHeaderMargin(50);
        $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
        $X = 11;
        $Y = 30;
        $pdf->setH3(50);
        $pdf->SetTitle('Production');
        $Y = $pdf->GetY() + 25;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont2();
        $pdf->Cell($pdf->getPageWidth() - ($X * 4), 10, 'Production', $B, 0, 'C', 0, 'B', 0);
        $Y = $pdf->GetY() + 15;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        // $pdf->Cell(30,$LH,'Date:', $B, 0, 'R', 0, 'B', 0);
        $Y = $pdf->GetY() + 4;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(30, $LH, 'Product Name:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $Y = 49;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        //$pdf->Cell(30,$LH,$grn->date, $B, 0, 'L', 0, 'B', 0);
        $Y = $pdf->GetY() + 5;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(60, $LH, $grn->prod_name, $B, 0, 'L', 0, 'B', 0);
        $X = $pdf->GetX() + 18;
        $Y = 45;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(25, $LH, 'Date:', $B, 0, 'R', 0, 'B', 0);
        $Y = $pdf->GetY();
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(65, $LH, $grn->date, $B, 0, 'L', 0, 'B', 0);

        //$X=$pdf->GetX()+20;

        $X = 109;
        $Y = $pdf->GetY() + 5;
        $pdf->SetXY($X, $Y);

        $X = $pdf->GetX() + 15;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, 'User Name:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(25, $LH, $grn->uname, $B, 0, 'L', 0, 'B', 0);
        $X = 124;
        $Y = 55;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, 'Batch No:', $B, 0, 'R', 0, 'B', 0);
        $Y = $pdf->GetY();
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, $grn->batch, $B, 0, 'L', 0, 'B', 0);
        $Y = 59;
        $X = 11;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(30, $LH, 'Warehouse:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(60, $LH, $grn->wname, $B, 0, 'L', 0, 'B', 0);
        $X = 91;
        $Y = $pdf->GetY() + 1;
        $pdf->setT1(10);
        $pdf->SetXY($X, $Y);
        $pdf->Cell(25, $LH, 'Qty:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, $grn->qty_in, $B, 0, 'L', 0, 'B', 0);
        $X = 119;
        $Y = $pdf->GetY();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(25, $LH, 'Unit Cost:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, $grn->rate, $B, 0, 'L', 0, 'B', 0);
        $X = 160;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(20, $LH, 'Total Cost:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell(25, $LH, $grn->amount, $B, 0, 'L', 0, 'B', 0);
        $Y = $pdf->GetY() + 5;
        $pdf->SetXY($X, $Y);
        $pdf->Ln($Ln);
        $product = '';

        foreach ($pi_detail as $line) {
            $product .= '<tr>
             <td style="font-size:8px;">&nbsp;&nbsp;' . $line->prod_name . '</td>
             <td style="font-size:8px;">&nbsp;&nbsp;' . $line->description . '</td>
             <td style="text-align:left;font-size:8px;">&nbsp;&nbsp;' . $line->unit . '</td>
             <td style="text-align:right;font-size:8px;">' . $line->qty . '</td>
             <td style="text-align:right;font-size:8px;">' . $line->rate . '</td>
             <td style="text-align:right;font-size:8px;">' . $line->amount . '</td>
            </tr>';
            $totalPiQty = $totalPiQty + $line->qty;
            $totalPiAmount += $line->amount;
        }
        $product .= '<tr>
         <td style="text-align:right;" colspan="2">Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
         <td style="text-align:left"></td>
         <td style="text-align:right;font-size:8px;">' . $totalPiQty . '</td>
         <td style="text-align:right;"></td>
         <td style="text-align:right;font-size:8px;">' . $totalPiAmount . '</td>
      </tr>';


        $html = '

            <table cellspacing="0" cellpadding="1" border="0.1" style="border-color:gray;">
            <tr style="background-color:dark-gray;color:white;">
                <th style="text-align:left; width:140px;">Product</th>
                <th style="text-align:left;width:130px;">Description</th>
                <th style="text-align:center;width:40px;">Unit</th>
                <th style="text-align:center;width:63px;">Qty</th>
                <th style="text-align:center;width:70px;">Rate</th>
                <th style="text-align:center;">Amount</th>
            </tr>' . $product .
            '</table>';
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $totalPvQty = 0;
        $totalPvAmount = 0;
        $vendor = '';
        foreach ($pv_detail as $line) {

            $vendor .= '<tr>
            <td style="font-size:8px;">&nbsp;&nbsp;' . $line->prod_name . '</td>
            <td style="font-size:8px;">&nbsp;&nbsp;' . $line->description . '</td>
            <td style="text-align:left;font-size:8px;">&nbsp;&nbsp;' . $line->unit . '</td>
            <td style="text-align:right;font-size:8px;">' . $line->qty . '</td>
            <td style="text-align:right;font-size:8px;">' . $line->rate . '</td>
            <td style="text-align:right;font-size:8px;">' . $line->amount . '</td>
            </tr>';
            $totalPvQty += $line->qty;
            $totalPvAmount += $line->amount;
        }
        $vendor .= '<tr>
            <td style="text-align:right;" colspan="2">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="text-align:left"></td>
            <td style="text-align:right;font-size:8px;">' . $totalPvQty . '</td>
            <td style="text-align:right;"></td>
            <td style="text-align:right;font-size:8px;">' . $totalPvAmount . '</td>
        </tr>';


        $html = '
            <br>
            <h4> Product Used By Vendor </h4>
            <table cellspacing="0" cellpadding="1" border="0.1" style="border-color:gray;">
            <tr style="background-color:dark-gray;color:white;">
                <th style="text-align:left; width:140px;">Product</th>
                <th style="text-align:left;width:130px;">Description</th>
                <th style="text-align:center;width:40px;">Unit</th>
                <th style="text-align:center;width:63px;">Qty</th>
                <th style="text-align:center;width:70px;">Rate</th>
                <th style="text-align:center;">Amount</th>
            </tr>' . $vendor .
            '</table>';
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $totalSvQty = 0;
        $totalSvAmount = 0;
        $services = '';
        foreach ($sv_detail as $line) {
            $services .= '<tr>
        <td style="font-size:8px;">&nbsp;&nbsp;' . $line->prod_name . '</td>
        <td style="font-size:8px;">&nbsp;&nbsp;' . $line->description . '</td>
        <td style="text-align:left;font-size:8px;">&nbsp;&nbsp;' . $line->unit . '</td>
        <td style="text-align:right;font-size:8px;">' . $line->qty . '</td>
        <td style="text-align:right;font-size:8px;">' . $line->rate . '</td>
        <td style="text-align:right;font-size:8px;">' . $line->amount . '</td>
        </tr>';

            $totalSvQty += $line->qty;
            $totalSvAmount += $line->amount;
        }
        $services .= '<tr>
           <td style="text-align:right;" colspan="2">Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="text-align:left"></td>
            <td style="text-align:right;font-size:8px;">' . $totalSvQty . '</td>
            <td style="text-align:right;"></td>
            <td style="text-align:right;font-size:8px;">' . $totalSvAmount . '</td>
        </tr>';


        $html = '
        <br>
        <h4> Service Used </h4>
        <table cellspacing="0" cellpadding="1" border="0.1" style="border-color:gray;">
        <tr style="background-color:dark-gray;color:white;">
            <th style="text-align:left; width:140px;">Product</th>
            <th style="text-align:left;width:130px;">Description</th>
            <th style="text-align:center;width:40px;">Unit</th>
            <th style="text-align:center;width:63px;">Qty</th>
            <th style="text-align:center;width:70px;">Rate</th>
            <th style="text-align:center;">Amount</th>
        </tr>' . $services .
            '</table>';
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        $pdf->Output('production.pdf', 'I');
    }
}
