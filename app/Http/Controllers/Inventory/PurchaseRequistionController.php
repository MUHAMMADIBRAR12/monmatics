<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use App\Libraries\appLib;
use Illuminate\Support\Str;
use DB;
use Illuminate\Support\Facades\Auth;
use File;
use Carbon\Carbon;
class PurchaseRequistionController extends Controller
{
    public function index()
    {
        $purchaseRequestions=DB::table('inv_purchase_requisitions')
                                 ->join('sys_warehouses','sys_warehouses.id','=','inv_purchase_requisitions.warehouse')
                                 ->select('inv_purchase_requisitions.*','sys_warehouses.name')
                                 ->orderBy('inv_purchase_requisitions.month','ASC')
                                 ->orderBy('inv_purchase_requisitions.number','ASC')
                                 ->get();
        return view('inventory.purchase_requistion_list',compact('purchaseRequestions'));
    }
    public function form($id=null)
    {
        //get warehouses
        $warehouses= dbLib::getWareHouses();
        if(!isset($id))
        {
           
            return view('inventory.purchase_requistion',compact('warehouses'));
        } 
        else
        {
            $purchaseRequestion=DB::table('inv_purchase_requisitions')->select()->where('id','=',$id)->get()->first();
            $purchaseRequestionDetails= DB::table('inv_purchase_requistion_details')
                                        ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                                        ->select('inv_purchase_requistion_details.*','inv_products.name')
                                        ->where('pr_id','=',$id)
                                        ->orderBy('display','asc')
                                        ->get();
            
            $purchaseRequestionAttachment=DB::table('inv_purchase_requistion_attachments')->select()->where('pr_id','=',$id)->get();
            return view('inventory.purchase_requistion',compact('warehouses','purchaseRequestion','purchaseRequestionDetails','purchaseRequestionAttachment'));
            
        }
    }
    public function save(Request $request)
    {
        
        if($request->id)
        {
            $pr_Id=$request->id;
            $this->update($request);
        }
        else
        {
            //Save Purchase Requestion Record
            $pr_Id= $this->store($request);
        }

        //save and update Purchase Requestion Detail Record
        $this->updatePurchaseRequestionDetail($pr_Id, $request);
         
        // save and update Purchase Requestion Attachment Record
        $this->updatePurchaseRequestionAttachments($pr_Id, $request);
        return redirect('Inventory/PurchaseRequistion/List');
    }

    private function store($request)
    {
        $companyId = session('companyId');
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number =  dbLib::getNumber('inv_purchase_requisitions');
        $id = str::uuid()->toString();
        $purchase_requestion = array (
            "id"=>$id ,
            "date"=>$request->date,
            "month"=>$month ,
            "number"=>$number,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "note"=>$request->note,
            "user_id"=>$userId,
            "created_at"=>Carbon::now(), 
            "updated_at"=>Carbon::now(),
             
        );
        
        DB::table('inv_purchase_requisitions')->insert($purchase_requestion);
        return $id;
        
    }
    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $companyId = session('companyId'); 
        $userId = Auth::id(); 
        $purchase_requestion = array (
            "id"=>$id ,
            "date"=>$request->date,
            "month"=>$month ,
            "warehouse"=>$request->warehouse,
            "status"=>$request->status,
            "company_id"=>$companyId,
            "note"=>$request->note,
            "update_user_id"=>$userId,
            "updated_at"=>Carbon::now(),
             
        );
        DB::table('inv_purchase_requisitions')->where('id', '=',$id)->update($purchase_requestion);
    }

    private function updatePurchaseRequestionDetail($pr_Id, $request)
    {

        DB::table('inv_purchase_requistion_details')->where('pr_id','=',$pr_Id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $purchaseRequestionDetail = array (     
                "id"=>$id,
                "pr_id"=>$pr_Id,
                "prod_id"=>$request->item_ID[$i],
                "description"=>$request->description[$i],
                "unit"=>$request->purchase_unit[$i],
                "qty_in_stock"=>$request->qty_in_stock[$i],
                "reorder_level"=>$request->reorder[$i],
                "qty_ordered"=>$request->qty_order[$i],
                "required_by_date"=>$request->required_date[$i],                
                "packing_detail"=>$request->packing_detail[$i],
                "display"=>$i+1,
            );
            DB::table('inv_purchase_requistion_details')->insert($purchaseRequestionDetail);  
        }
    }
    private function updatePurchaseRequestionAttachments($pr_Id, $request)
    {
        if($request->file)
        {
               $id = str::uuid()->toString();
               $file=$request->file;
                $fileName = time().'.'.$file->getClientOriginalName();  
                $file->move(public_path('assets/attachments'), $fileName);
                $fileData = array(
                    "id"=>$id,
                    "pr_id"=>$pr_Id,
                    "file"=> $fileName,
                );
                DB::table('inv_purchase_requistion_attachments')->insert($fileData);
               
            
        }
    }
    public function attach_remove(Request $request)
    {
        //remove file from folder
        File::delete(public_path('assets/attachments'.$request->attachment));
        DB::table('inv_purchase_requistion_attachments')->where('file','=',$request->attachment)->delete();
    }

    public function view($id)
    {
        $purchaseRequestion=DB::table('inv_purchase_requisitions')
                                ->join('sys_warehouses','sys_warehouses.id','=','inv_purchase_requisitions.warehouse')
                                ->select('inv_purchase_requisitions.*','sys_warehouses.name as warehouse_name')
                                ->where('inv_purchase_requisitions.id','=',$id)
                                ->get()
                                ->first();
        $purchaseRequestionDetails= DB::table('inv_purchase_requistion_details')
                                        ->leftJoin('inv_products', 'inv_products.id', '=', 'prod_id')
                                        ->select('inv_purchase_requistion_details.*','inv_products.name')
                                        ->where('pr_id','=',$id)
                                        ->orderBy('display','asc')
                                        ->get();
           
        $purchaseRequestionAttachment=DB::table('inv_purchase_requistion_attachments')->select()->where('pr_id','=',$id)->get();
        return view('inventory.purchase_requistion_view',compact('purchaseRequestion','purchaseRequestionDetails','purchaseRequestionAttachment'));
    }

}
