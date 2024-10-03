<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProductCategoryController extends Controller
{
    public function prod_cate_List()
    {
        $productCategories=DB::table('inv_categories')->select('id','category')->get();
        return view('inventory.prdouct_category_list',compact('productCategories'));
    }
    // remove the product type
     public function prod_cate_remove($id)
      {
        
        DB::table('inv_categories')->where('id',$id)->delete();
        return back()->with('msg','Record Deleted successfully');
      }
    public function prod_cate_save(Request $request)
    {
        if($request->isMethod('get'))
        {
            return view('inventory.product_category');
        }
        else
        {
         if($request->id)
            {
               
                DB::table('inv_categories')
                ->where('id',$request->id)
                ->update(
                [
                 'category' => $request->category
                ]);
             return redirect('Inventory/ProductCategoryList');
            }
          DB::table('inv_categories')->insert([
                [  
                 
                 'category' => $request->category,
     
                ], 
                                         ]);  
              return redirect('Inventory/ProductCategoryList');
        }
    }
     public function prod_cate_edit($id)
      {
        $productCategory=DB::table('inv_categories')->where('id',$id)->get()->first();
        return view('inventory.product_category',compact('productCategory'));
      }
}
