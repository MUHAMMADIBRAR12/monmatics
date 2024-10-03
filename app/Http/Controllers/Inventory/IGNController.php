<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\dbLib;
class IGNController extends Controller
{
    public function po_record(Request $request)
    {
        $podetail=DB::table('pur_purchase_orders as po')
                    ->join('pur_purchase_order_details as pod', function ($join) {
                        $join->on('pod.po_id', '=', 'po.id')
                            ->where('pod.qty', '!=', 'pod.qty_received');
                    })
                    ->join('pa_vendors','pa_vendors.id','=','po.ven_id')
                    ->join('inv_products','inv_products.id','=','pod.prod_id')
                    ->select('po.date as po_date','po.warehouse','po.pr_id','pod.*','pa_vendors.id as vid','pa_vendors.name as vname','inv_products.name')
                    ->where('po.id','=',$request->poNum)
                    ->orderBy('display')
                    ->get();  
        return $podetail;
    } 

    public function index()
    {
        $igps=DB::table('inv_igp')
                ->join('sys_warehouses','sys_warehouses.id','=','inv_igp.warehouse')
                ->join('pa_vendors','pa_vendors.id','=','inv_igp.ven_id')
                ->join('pur_purchase_orders as po','po.id','=','inv_igp.po_id')
                ->select('inv_igp.*','sys_warehouses.name as warehouse_name','pa_vendors.name as vendor',DB::raw("concat(po.month,'-',LPAD(po.number,4,0)) as po_num"))
                ->where('inv_igp.company_id',session('companyId'))
                ->orderBy('inv_igp.month','ASC')
                ->orderBy('inv_igp.number','ASC')
                ->get();
        return view('inventory.igp_list',compact('igps'));
    }

    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        $whereDateCluse = array();
        array_push($whereDateCluse,array ('pur_purchase_orders.close_status', '=',0));
        $purchaseOrders=dbLib::getDocumentNumbers('pur_purchase_orders',$whereDateCluse);
        if($id)
        {
            $igp=DB::table('inv_igp')
                     ->leftjoin('pa_vendors','pa_vendors.id','inv_igp.ven_id')
                     ->join('pur_purchase_orders','pur_purchase_orders.id','=','inv_igp.po_id')
                     ->select('inv_igp.*','pa_vendors.name as ven_name','pur_purchase_orders.number as po_number','pur_purchase_orders.month as po_month','pur_purchase_orders.date as po_date')
                     ->where('inv_igp.id',$id)
                     ->first();
            $igpDetails=DB::table('inv_igp_details')
                           ->leftjoin('inv_products','inv_products.id','=','inv_igp_details.prod_id')
                           ->select('inv_igp_details.*','inv_products.name')
                           ->where('inv_igp_details.igp_id','=',$id)
                           ->get(); 
            $attachmentRecord=dbLib::getAttachment($id);
            return view('inventory.igp',compact('warehouses','igp','igpDetails','attachmentRecord'));
        }
        else
        {
            return view('inventory.igp',compact('warehouses','purchaseOrders'));
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if($request->id)
        {
            $igp_id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save igp Record
            $igp_id= $this->store($request); 
            DB::table('pur_purchase_orders')->where('id',$request->poNum)->update(['editable'=>0]); 
        }
        //save and update igp Detail Record
        $this->updateIGPDetails($igp_id, $request);

        if($request->file)  // save and update igp Attachment Record
            dbLib::uploadDocument($igp_id,$request->file);
            
        $balance=DB::table('pur_purchase_order_details')->select(DB::raw('SUM(qty)-SUM(qty_received) as balance'))->where('po_id',$request->poNum)->get();
        if($balance[0]->balance==0)
        {
            DB::table('pur_purchase_orders')->where('id',$request->poNum)->update(['close_status'=>1]);
        }
      
        return redirect('Inventory/IGP/List');
    }

    private function store($request)
    {
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('inv_igp');
        $id = str::uuid()->toString();
        $inv_igp = array (
            "id"=>$id ,
            "pr_id"=>$request->pr_id,
            "po_id"=>$request->poNum,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "ven_id"=>$request->vendor_ID,
            "company_id"=> $companyId,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "note"=>$request->note,
            "do_ref"=>$request->do_ref,
            "veh_type"=>$request->veh_type,
            "delivery_man"=>$request->delivery_man,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
            "created_at"=>Carbon::now(), 
            "updated_at"=>Carbon::now(), 
            
        ); 
        DB::table('inv_igp')->insert($inv_igp);
        return $id;
    
    }

    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $inv_igp = array (
            "date"=>$request->date,
            "month"=>$month,
            "ven_id"=>$request->vendor_ID,
            "company_id"=> $companyId,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "do_ref"=>$request->do_ref,
            "veh_type"=>$request->veh_type,
            "delivery_man"=>$request->delivery_man,
            "note"=>$request->note,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
            "updated_at"=>Carbon::now(),
             
        );
        DB::table('inv_igp')->where('id', '=',$id)->update($inv_igp);
    }

    private function updateIGPDetails($igp_id, $request)
    {
        $count = count($request->item_ID);
        //this section execute only when we update igp.
        if($request->id)
        {
            for($i=0;$i<$count;$i++)
            {
                $data=DB::table('inv_igp_details')->select('qty_received')->where('inv_igp_details.igp_id',$request->id)->where('inv_igp_details.po_detail_id',$request->po_detail_id[$i])->first();
                DB::table('pur_purchase_order_details')->where('id',$request->po_detail_id[$i])->decrement('qty_received',$data->qty_received);
            }
        }
        //delet  igp detail record for updating or adding igp
        DB::table('inv_igp_details')->where('igp_id','=',$igp_id)->delete();
        
        for($i=0;$i<$count;$i++)
        {
            if($request->qty_received[$i]>0)
            {
                $id = str::uuid()->toString();
                $igpDetail = array (     
                "id"=>$id,
                "igp_id"=>$igp_id,
                "po_id"=>$request->poNum,
                "po_detail_id"=>$request->po_detail_id[$i],
                "prod_id"=>$request->item_ID[$i],
                "description"=>$request->description[$i],
                "unit"=>$request->unit[$i],
                "rate"=>$request->rate[$i],
                "qty_order"=>$request->qty_order[$i],
                "qty_received"=>$request->qty_received[$i],
                "amount"=>$request->rate[$i] * $request->qty_received[$i],
                "base_rate"=>($request->base_rate[$i])?$request->base_rate[$i] :$request->rate[$i],
                "packing_detail"=>$request->packing_detail[$i],
                "display"=>$i+1,
                );
                DB::table('inv_igp_details')->insert($igpDetail);             
                DB::table('pur_purchase_order_details')->where('id',$request->po_detail_id[$i])->where('prod_id',$request->item_ID[$i])->increment('qty_received',$request->qty_received[$i]);
            }
        }
    }

    public function view($id)
    {
        $igp=DB::table('inv_igp')
                ->join('sys_warehouses','sys_warehouses.id','=','inv_igp.warehouse')
                ->join('pa_vendors','pa_vendors.id','inv_igp.ven_id')
                ->join('pur_purchase_orders','pur_purchase_orders.id','=','inv_igp.po_id')
                ->select('inv_igp.*','pa_vendors.name as vendor_name','sys_warehouses.name as warehouse_name','pur_purchase_orders.date as po_date',DB::raw("concat(pur_purchase_orders.month,'-',LPAD(pur_purchase_orders.number,4,0)) as po_num"))
                ->where('inv_igp.id',$id)
                ->first();
        $igpDetails=DB::table('inv_igp_details')
                           ->leftjoin('inv_products','inv_products.id','=','inv_igp_details.prod_id')
                           ->select('inv_igp_details.*','inv_products.name')
                           ->where('inv_igp_details.igp_id','=',$id)
                           ->get(); 
        $attachmentRecord=dbLib::getAttachment($id);
        return view('inventory.igp_view',compact('igp','igpDetails','attachmentRecord'));    
    }


}
