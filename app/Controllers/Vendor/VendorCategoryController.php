<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use DB;
use Illuminate\Support\Facades\DB;

class VendorCategoryController extends Controller
{
    public function index()
    {
                  
        $categories=DB::table('pa_categories')->get();
        return view('vendor.vendor_category_list',compact('categories'));
    }
    public function form($id=null)
    {
        if($id)
        {  
            $category=DB::table('pa_categories')->select()->where('id','=',$id)->get()->first();
            return view('vendor.vendor_category',compact('category'));
        }
        else
        {
          return view('vendor.vendor_category');
        }
    }
    public function save(Request $request)
    {
        if($request->id)
        {
           
            $categories= array(
                "category"=>$request->category,
            );
            DB::table('pa_categories')->where('id','=',$request->id)->update($categories);
            return redirect('Vendor/VendorCategory/List');
        }
        else
        {
            $categories= array(
                "category"=>$request->category,
            );
            DB::table('pa_categories')->insert($categories);
            return redirect('Vendor/VendorCategory/List');
        }
    }
    public function hide($id)
    {
        DB::table('pa_categories')->where('id',$id)->delete();
        return back()->with('msg','Record Deleted successfully');
    }
}
