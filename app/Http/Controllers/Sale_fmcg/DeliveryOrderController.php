<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use App\Libraries\inventoryLib;
use App\Libraries\swPDF;
use PDF;
use TCPDF;

class DeliveryOrderController extends Controller
{
    public function approvel_detail(Request $request)
    {
        $approvel_detail=DB::table('sale_order_approvel as soa')
                            ->join('sale_order_detail_approvel as soa_detail','soa_detail.soa_id','=','soa.id')
                            ->join('inv_products','inv_products.id','=','soa_detail.prod_id')
                            ->join('sys_units as u1','u1.id','=','inv_products.sale_unit')
                            ->join('sys_units as u2','u2.id','=','u1.base_unit')
                            ->join('users','users.id','=','soa.user_id')
                            ->join('crm_customers as cust','cust.coa_id','=','soa.cust_id')
                            ->join('crm_customers_address as cust_add','cust_add.cust_id','=','cust.id')
                            ->select('soa_detail.*','inv_products.name as prod_name','soa.date','soa.note',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'cust.name as cust_name','cust.coa_id as cust_id','cust_add.address as cust_address','cust_add.location as cust_town','u1.operator_value','u2.name as base_unit')
                            ->where('soa.id',$request->soa_id)
                            ->orderBy('soa_detail.display')
                            ->get();
        return $approvel_detail;
    }
    public function index()
    {
        $delivery_orders=DB::table('inv_delivery_order_fmcg')
                            ->join('crm_customers','crm_customers.coa_id','inv_delivery_order_fmcg.cust_id')
                            ->join('sale_order_approvel as soa','soa.id','inv_delivery_order_fmcg.soa_id')
                            ->join('sale_orders','sale_orders.id','soa.so_id')
                            ->select('inv_delivery_order_fmcg.*',DB::raw("concat(sale_orders.month,'-',LPAD(sale_orders.number,4,0)) as so_num"),'crm_customers.name as cust_name')
                            ->where('inv_delivery_order_fmcg.company_id',session('companyId'))
                            ->orderBy('month')
                            ->orderBy('number')
                            ->get();
        return view('sale_fmcg.delivery_order_list',compact('delivery_orders'));
    }

    public function form($id=null)
    {
        $warehouses= dbLib::getWareHouses();

        $whereDateCluse = array();
        array_push($whereDateCluse,array ('sale_order_approvel.close_status', '=',0));
        $so_approvels=dbLib::getDocumentNumbers('sale_order_approvel',$whereDateCluse);
        if($id)
        {
            $do=DB::table('inv_delivery_order_fmcg as do')
                    ->join('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                    ->join('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                    ->join('users','users.id','=','sale_order_approvel.user_id')
                    ->join('crm_customers','crm_customers.coa_id','=','do.cust_id')
                    ->join('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                    ->select('do.*','sys_warehouses.name as warehouse_name',DB::raw("concat(sale_order_approvel.month,'-',LPAD(sale_order_approvel.number,4,0)) as approvel_no"),'sale_order_approvel.date as soa_date',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name','cust_add.address','cust_add.location')
                    ->where('do.id',$id)
                    ->first();
            $result=DB::table('inv_delivery_order_detail_fmcg as do_detail')
                        ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                        ->join('sys_units as u1','u1.id','=','inv_products.sale_unit')
                        ->join('sys_units as u2','u2.id','=','u1.base_unit')
                        ->select('do_detail.*','inv_products.name as prod_name','u1.operator_value','u2.name as base_unit')
                        ->where('do_detail.do_id',$id)
                        ->get();
            $do_detail=array();
            foreach($result as $row)
            {
                $data=array(
                    "soa_detail_id"=>$row->soa_detail_id,
                    "prod_name"=>$row->prod_name,
                    "prod_id"=>$row->prod_id,
                    "unit"=>$row->unit,
                    "base_unit"=>$row->base_unit,
                    "operator_value"=>$row->operator_value,
                    "qty_stock"=>inventoryLib::getStock($row->prod_id,$do->warehouse)->qty + dbLib::convertUnit($row->qty_delivery,$row->unit),
                    "qty_approved"=>$row->qty_approved,
                    "qty_delivery"=>$row->qty_delivery,
                    "remarks"=>$row->remarks,
                );
                array_push($do_detail,$data);
            }      
            return view('sale_fmcg.delivery_order',compact('so_approvels','warehouses','do','do_detail'));
        }
        else
        {
            return view('sale_fmcg.delivery_order',compact('so_approvels','warehouses'));
        }
       
    }

    public function stock(Request $request)
    {
        
        $stock= inventoryLib::getStock($request->prod_id,$request->warehouse_id);
        return $stock->qty;
        
    }
    public function printdelivery($id=null)
    {
        $dopdf=DB::table('inv_delivery_order_fmcg as do')
                    ->leftJoin('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                    ->leftJoin('users','users.id','=','sale_order_approvel.user_id')
                    ->leftJoin('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                    ->leftJoin('crm_customers','crm_customers.coa_id','=','do.cust_id')
                    ->leftJoin('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                    ->select('do.*','sale_order_approvel.date as soa_date','sys_warehouses.name as wareh','sale_order_approvel.user_name as approvedby','do.date as dod','do.warehouse','users.name as uname'
                            ,DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name',
                            'cust_add.phone as phon','cust_add.address','cust_add.location',
                            DB::raw("concat(sale_order_approvel.month,'-',LPAD(sale_order_approvel.number,4,0)) as approvel_no"),
                            DB::raw("concat(do.month,'-',LPAD(do.number,4,0)) as del_no"))
                    ->where('do.id',$id)
                    ->get()
                    ->first();

        $do_detail=DB::table('inv_delivery_order_detail_fmcg as do_detail')
                    ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                    ->select('do_detail.*','inv_products.name as prod_name')
                    ->where('do_detail.do_id',$id)
                    ->get();

        $pdf = new swPDF();

        //$pdf= new TCPDF; 
        $X=78; $Y=20;$Ln=4; $B=0; $LH=6; $W=50;
        $pdf->setH3();
        $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
        $X=10; $Y=30;
        $pdf->SetTitle('Delivery Invoice');
        $Y=$pdf->GetY()+25;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont();
        $pdf->Cell($pdf->getPageWidth()-($X*2), 10, 'Delivery Order', $B, 0, 'R', 0, 'B', 0);
        $Y=$pdf->GetY()+16;
        $pdf->SetXY($X,$Y);
        $pdf->setT1(14);
        $pdf->Cell(70,$LH,'Delivered To:', $B);
        $pdf->Ln($Ln);
        $Y=$pdf->GetY()+3;
        $pdf->SetXY($X,$Y);
        $pdf->Cell(90,$LH,$dopdf->cust_name, $B);
        $pdf->Ln();
        $pdf->MultiCell(90, $LH,$dopdf->address.$dopdf->location, $B, 'L', 0, 0, '', '', true);
        // $Y=$pdf->GetY();
        // $pdf->SetXY($X, $Y);
        $pdf->Ln();
        $pdf->Cell(70,$LH,  $dopdf->phon, $B);
        $X +=110;
        $Y=53;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'DO No:',$B, 0, 'R', 0, 'B', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Ln();
        $Y=$pdf->GetY();
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Do Date:',$B, 0, 'R', 0, 'B', 0);
        $Y=53;
        $X+=30;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$dopdf->del_no,$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+6;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$dopdf->dod,$B, 0, 'L', 0, 'B', 0);
        // $X=150;
        // $Y=$pdf->GetY()+14;
        // $pdf->SetXY($X,$Y);
        // $pdf->setH3();
        // $pdf->Cell(42,$LH,'Total Balance Due:',$B, 0, 'L', 0, 'B', 0);
        // $Y+=7;
        // $X+=5;
        // $pdf->SetXY($X,$Y);
        // $pdf->setT1();
        // $pdf->Cell(37,$LH,'XXXXXXXX',$B, 0, 'L', 0, 'B', 0);
        $X=120;
        $Y=$pdf->GetY()+6;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Approval No:',$B, 0, 'R', 0, 'B', 0);
        $Y=$pdf->GetY()+7;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(30,$LH,'Date:',$B, 0, 'R', 0, 'B', 0);
        $X=150;
        $Y=65;
        //$X=$pdf->GetX();
        //$Y=20;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$dopdf->approvel_no,$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+7;
        $pdf->setT1();
        $pdf->SetXY($X,$Y);
        $pdf->Cell(30,$LH,appLib::setDateFormat($dopdf->soa_date),$B, 0, 'L', 0, 'B', 0);
        $X=130;
        $Y=78;
        //$Y=$pdf->GetY();
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(20,$LH,'Sale Order By:',$B, 0, 'R', 0, 'B', 0);
        $Y=$pdf->GetY()+6;
        $X=125;
        $pdf->SetXY($X,$Y);
        $pdf->setH3();
        $pdf->Cell(25,$LH,'Warehouse:',$B, 0, 'R', 0, 'B', 0);
        
        $pdf->CreatedAt=$dopdf->created_at;
        $Y=78;
        $X=150;
        //$Y=20;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell(30,$LH,$dopdf->uname,$B, 0, 'L', 0, 'B', 0);
        $Y=$pdf->GetY()+7;
        $X=150;
        $pdf->SetXY($X,$Y);
        $pdf->MultiCell(70,$LH,$dopdf->wareh, $B, 'L', 0, 0, '', '', true);
        $pdf->Ln($Ln+5);
        $i=1;
        // Start table code
        $PDFLineItem ="";
        $totalqty=0;
        $cssClass='';
        foreach( $do_detail as $lineItem)
        {
            $cssClass = ($cssClass=="bg1")?"bg2":"bg1";
            $PDFLineItem .='            
            <tr class="'.$cssClass.'">
             <td style="text-align:center;">'.$i.'</td>
             <td style="text-align:left; font-size:10px;" >
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$lineItem->prod_name.'</td>
             <td style="text-align:right; font-size:10px;">'.number_format($lineItem->qty_approved,0).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
             
             </tr>
             ';
             $i++;
             $totalqty +=$lineItem->qty_approved;
             //$total=$lineItem->amount+$total;
             //$NetAmt=$lineItem->$total+$Invoicepdf->tax_amount;
        }

        $PDFLineItem .='<tr style="background-color:white;">
        <td style="text-align:center;"></td>
         <td style="text-align:center; font-size:12px;">Sub Total</td>
         <td class="fontstyle" style="text-align:right; font-size:12px;">'.$totalqty.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>';
        $titleFont= $pdf->setTableHeading();
        $html = '<style>'.file_get_contents(asset('public/assets/css/list.css')).'</style>';
       // $html .= <<<EOD
        $html .= '
        <table class="table" style=" font-family: Arial, Helvetica, sans-serif; width: 100%; align:center;" > 
            <tr class="bg" style=" border: 1px solid #ddd; padding: 8px;">
            <th style="width:40;font-size: 13px; text-align:left;" >SR</th>
            <th style="width:300;font-size: 13px; text-align:center;">Product</th>
            <th style="width:194; font-size: 13px; text-align:center;">Qty Approved</th>
        </tr>
        '.$PDFLineItem.'
        </table>';
        //EOD; 
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdf->Ln();
        $pdf->GetY($Y);
        $X=15; $Y=160;$Ln=5; $B=0; $LH=6; $W=50;
        $Y=($Y<265)?265:$Y;
        $B=0;
        $XW=45;
        $pdf->SetXY($X,$Y);
        $pdf->setT1();
        $pdf->Cell($XW,$LH,$dopdf->user_name,$B, 0, 'C', 0, 'B', 0);
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

      
        // $Y=160;
        // $X=$pdf->GetX()+10;
        // $pdf->SetXY($X,$Y);
        // $pdf->setT1();
        // $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

        // $Y=$pdf->GetY()+5;
        // $pdf->SetXY($X,$Y);
        // $pdf->setH3();


        // $Y=160;
        // $X=$pdf->GetX()+10;
        // $pdf->SetXY($X,$Y);
        // $pdf->setT1();
        // $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

        // $Y=$pdf->GetY()+5;
        // $pdf->SetXY($X,$Y);
        // $pdf->setH3();
       
        // $Y=160;
        // $X=$pdf->GetX()+20;
        // $pdf->SetXY($X,$Y);
        // $pdf->setT1();
        // $pdf->Cell(30,$LH,'',$B, 0, 'L', 0, 'B', 0);

        // $Y=$pdf->GetY()+5;
        // $pdf->SetXY($X,$Y);
        // $pdf->setH3();
        


        $pdf->Output('Delivery_Invoice.pdf', 'I');
    }
    public function save(Request $request)
    {
       // dd($request->input());
        if($request->id)
        {
            $do_id=$request->id;
            $this->update($request);
        }
        else
        {
            //
            $do_id= $this->store($request);
            DB::table('sale_order_approvel')->where('id',$request->approvel_no)->update(['editable'=>0]);
        }
        //save and update sale order detail
        $this->updateDo_Detail($do_id, $request);

        //for closing sal order approvl 
        $remaining_qty=DB::table('sale_order_detail_approvel')->select(DB::raw('SUM(qty_approved)-SUM(remain_qty) as balance'))->where('soa_id',$request->approvel_no)->get();
        if($remaining_qty[0]->balance==0)
        {
            DB::table('sale_order_approvel')->where('id',$request->approvel_no)->update(['close_status'=>1]);
        }
        return redirect('Sales_Fmcg/DeliveryOrder/List');
    }

    private function store($request)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_delivery_order_fmcg');
        $id = str::uuid()->toString();
        $do = array (
            "id"=>$id ,
            "soa_id"=>$request->approvel_no,
            "month"=>$month,
            "number"=>$number,
            "date"=>$request->date,
            "cust_id"=>$request->customer_id,
            "warehouse"=>$request->warehouse,
            "company_id"=>$companyId,
            "veh_no"=>$request->veh_no,
            "bilty_no"=>$request->bilty_no,
            "delivery_name"=>$request->delivery_name,
            "remarks"=>$request->remarks,
            "created_by"=>$userId,
            "created_at"=>Carbon::now(),

        ); 
        DB::table('inv_delivery_order_fmcg')->insert($do);
        return $id;
    }

    private function update($request)
    {
        $month = dbLib::getMonth($request->date); 
        $do = array (
            "month"=>$month,
            "date"=>$request->date,
            "cust_id"=>$request->customer_id,
            "veh_no"=>$request->veh_no,
            "bilty_no"=>$request->bilty_no,
            "delivery_name"=>$request->delivery_name,
            "remarks"=>$request->remarks,
            "updated_at"=>Carbon::now(),
        ); 
        DB::table('inv_delivery_order_fmcg')->where('id', '=',$request->id)->update($do);
    }

    private function updateDo_Detail($do_id, $request)
    {
        $count = count($request->item_ID);
        //this section execute only when we update delivery order detail
        if($request->id)
        {
            for($i=0;$i<$count;$i++)
            {
                $data=DB::table('inv_delivery_order_detail_fmcg')->select('qty_delivery')->where('do_id',$request->id)->where('soa_detail_id',$request->soa_detail_id[$i])->first();
                //dd($data);
                DB::table('sale_order_detail_approvel')->where('id',$request->soa_detail_id[$i])->decrement('remain_qty',$data->qty_delivery);
            }
            
            //add issue qnty in stock 
            $issue_stocks=DB::table('inv_inventories')->where('source_id',$request->id)->get();
            foreach($issue_stocks as $row)
            {
                $data= array(
                    "date"=>$row->date,
                    "warehouse_id"=>$row->warehouse_id,
                    "source"=>'do update',
                    "source_id"=>$request->id,
                    "source_detail_id"=>$request->id,
                    "status"=>$row->status,  
                    "prod_id"=>$row->prod_id,  
                    "qty_in"=>$row->qty_out,   
                    "balance"=>$row->qty_out,   
                    "unit"=>$row->unit,   
                    "rate"=>$row->rate,   
                    "amount"=>$row->amount,
                    "description"=>'Stock adjustment while D.O edit'   
                );
                inventoryLib::addInventory($data);
            }
        }
        DB::table('inv_delivery_order_detail_fmcg')->where('do_id','=',$do_id)->delete();

        for($i=0;$i<$count;$i++)
        {
            if($request->qty_delivered[$i]>0)
            {
                $id = str::uuid()->toString();
                $do_detail = array (     
                    "id"=>$id,
                    "do_id"=>$do_id,
                    "soa_detail_id"=>$request->soa_detail_id[$i],
                    "prod_id"=>$request->item_ID[$i],
                    "unit"=>$request->unit[$i],
                    "qty_approved"=>$request->qty_approved[$i],
                    "qty_delivery"=>$request->qty_delivered[$i],
                // "batch_no"=>$request->batch[$i],
                    //"expiry_date"=>$request->expire_date[$i],
                    "remarks"=>$request->remark[$i],
                    "display"=>$i+1,
                );
                DB::table('inv_delivery_order_detail_fmcg')->insert($do_detail); 

                //also update the sale_order_detail_approvel table because when qty approved=remain_qty we close this sale order approvel got it.
                DB::table('sale_order_detail_approvel')->where('id',$request->soa_detail_id[$i])->where('prod_id',$request->item_ID[$i])->increment('remain_qty',$request->qty_delivered[$i]);
                //inventory data
                $inventory = array (
                    "date"=>$request->date,
                    "warehouse_id"=>$request->warehouse,
                    "source"=>'delivery_order',
                    "source_id"=>$do_id,
                    "source_detail_id"=>$id,
                    "prod_id"=>$request->item_ID[$i],
                    "qty_in"=>0,
                    "qty_out"=>$request->qty_delivered[$i] * $request->operator_value[$i],
                    "unit"=>$request->base_unit[$i],
                    "description"=>$request->remarks,
                ); 
                $inventoryData = inventoryLib::issueInventory($inventory); 
            }
        }
    }

    public function view($id)
    {
        $do=DB::table('inv_delivery_order_fmcg as do')
                    ->leftJoin('sys_warehouses','sys_warehouses.id','=','do.warehouse')
                    ->leftJoin('sale_order_approvel','sale_order_approvel.id','=','do.soa_id')
                    ->leftJoin('users','users.id','=','sale_order_approvel.user_id')
                    ->leftJoin('crm_customers','crm_customers.coa_id','=','do.cust_id')
                    ->leftJoin('crm_customers_address as cust_add','cust_add.cust_id','=','crm_customers.id')
                    ->select('do.*','sys_warehouses.name as warehouse_name',DB::raw("concat(sale_order_approvel.month,'-',LPAD(sale_order_approvel.number,4,0)) as approvel_no"),'sale_order_approvel.date as soa_date',DB::raw("concat(users.firstName,' ',users.lastName) as user_name"),'crm_customers.name as cust_name','cust_add.address','cust_add.location')
                    ->where('do.id',$id)
                    ->first();
        $do_detail=DB::table('inv_delivery_order_detail_fmcg as do_detail')
                    ->join('inv_products','inv_products.id','=','do_detail.prod_id')
                    ->join('sys_units as u1','u1.id','=','inv_products.sale_unit')
                    ->join('sys_units as u2','u2.id','=','u1.base_unit')
                    ->select('do_detail.*','inv_products.name as prod_name','u1.operator_value','u2.name as base_unit')
                    ->where('do_detail.do_id',$id)
                    ->get();
        return view('sale_fmcg.delivery_order_view',compact('do','do_detail'));
    }
}
