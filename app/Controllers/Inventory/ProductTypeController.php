<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProductTypeController extends Controller
{
    public function index()
    {
        $productTypes=DB::table('inv_type')->select('id','type')->get();
        return view('inventory.prdouct_type_list',compact('productTypes'));
    }
    // remove the product type
     public function hide($id)
      {
        
        DB::table('inv_type')->where('id',$id)->delete();
        return back()->with('msg','Record Deleted successfully');
      }
    public function save(Request $request)
    {
        if($request->isMethod('get'))
        {
            return view('inventory.product_type');
        }
        else
        {
         if($request->id)
            {
               
                DB::table('inv_type')
                ->where('id',$request->id)
                ->update(
                [
                 'type' => $request->type,
                ]);
             return redirect('Inventory/ProductType/List');
            }
          DB::table('inv_type')->insert([
                [  
                 
                 'type' => $request->type,
     
                ], 
                                         ]);  
              return redirect('Inventory/ProductType/List');
        }
    }
     public function edit($id)
      {
        $productType=DB::table('inv_type')->where('id',$id)->get()->first();
        return view('inventory.product_type',compact('productType'));
      }
}
