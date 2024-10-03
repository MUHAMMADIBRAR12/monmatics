<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
class InternalTransferController extends Controller
{
    public function inter_trans_List()
    {

        $internalTransfer = DB::table('inv_internal_transfers')
        ->join('inv_internal_transfer_details', 'inv_internal_transfers.id', '=', 'inv_internal_transfer_details.int_id')
        ->select('inv_internal_transfers.*', 'inv_internal_transfer_details.*')
        ->get();
    
    return view('inventory.internal_transfer_list', compact('internalTransfer'));
    
    }

    public function inter_trans($id=null)
    {
        //get warehouses name
        $warehouses=DB::table('sys_warehouses')->select('name')->get();
        //get units name
         $units=DB::table('sys_units')->select('name')->get();
         $internalTransfer = null; // Initialize with null

         
        if($id)
        {
            $internalTransfer=DB::table('inv_internal_transfers')->select('inv_internal_transfers.*')->first();
            $internalTransferDetail=DB::table('inv_internal_transfer_details')
                                        ->join('inv_products','inv_products.id','=','inv_internal_transfer_details.prod_id')
                                        ->select('inv_internal_transfer_details.*','inv_products.name')
                                        ->orderBy('display', 'asc')
                                        ->where('inv_internal_transfer_details.int_id','=',$id)
                                        ->get();
            
            return view('inventory.internal_transfer',compact('warehouses','units','internalTransfer','internalTransferDetail'));
        }
        else
        {
            return view('inventory.internal_transfer',compact('warehouses','units','internalTransfer'));
        }
    }
    public function inter_trans_save(Request $request)
    {
        if($request->id)
        {
            $int_Id=$request->id;
            $this->update($request);
        }
        else
        {
            
            $int_Id= $this->save($request);  
        }
        //save and update internal transfer Detail Record
        $this->updateInternalTransferDetail($int_Id, $request);
         
        // save and update internal transfer Attachment Record
        $this->updateInternalTransferAttachments($int_Id, $request);
        return redirect('Inventory/IT');
    }
    private function save($request)
    {
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $id = str::uuid()->toString();
        $internal_transfer = array (
            "id"=>$id ,
            "date"=>$request->date,
            "month"=>$month,
            "warehouse_from"=>$request->warehouse_from,
            "warehouse_to"=>$request->warehouse_to,
            "status"=>$request->status,
            "note"=>$request->note,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
            
        ); 
        DB::table('inv_internal_transfers')->insert($internal_transfer);
        return $id;
    }
    private function update($request)
    {
        $id = $request->id;
        $month = dbLib::getMonth($request->date);
        $userId = Auth::id(); 
        $internal_transfer= array (
            "id"=>$id ,
            "date"=>$request->date,
            "month"=>$month,
            "warehouse_from"=>$request->warehouse_from,
            "warehouse_to"=>$request->warehouse_to,
            "status"=>$request->status,
            "note"=>$request->note,
            "user_id"=>$userId,
            "update_user_id"=>$userId,
             
        );
        DB::table('inv_internal_transfers')->where('id', '=',$id)->update($internal_transfer);
    }
   private function updateInternalTransferDetail($int_Id, $request)
{
    // DB::table('inv_internal_transfer_details')->where('int_id', '=', $int_Id)->delete();

    if (is_array($request->item_ID)) {
        $count = count($request->item_ID);
        for ($i = 0; $i < $count; $i++) {
            $id = Str::uuid()->toString();
            $internalTransferDetail = array(
                "id" => $id,
                "int_id" => $int_Id,
                "prod_id" => $request->item_ID[$i],
                "description" => $request->description[$i],
                "unit" => $request->unit[$i],
                "qty" => $request->qty_transfer[$i],
                "rate" => $request->rate[$i],
                "amount" => $request->amount[$i],
                "packing_detail" => $request->packing_detail[$i],
                "display" => $i,
            );
            DB::table('inv_internal_transfer_details')->insert($internalTransferDetail);
        }
    } else {
        // Handle the case where $request->item_ID is not an array
        // You might want to log an error or handle this situation accordingly
    }
}

    private function updateInternalTransferAttachments($int_Id, $request)
    {
      if($request->file)
      {
             $id = str::uuid()->toString();
             $file=$request->file;
              $fileName = time().'.'.$file->getClientOriginalName();  
              $file->move(public_path('assets/attachments'), $fileName);
              $fileData = array(
                  "id"=>$id,
                  "int_id"=>$int_Id,
                  "file"=> $fileName,
              );
              DB::table('inv_internal_transfer_attachments')->insert($fileData);
      }
    }
}
