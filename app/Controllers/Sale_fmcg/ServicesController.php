<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
class ServicesController extends Controller
{
    //this return the services list
    public function index()
    {
        $services=DB::table('inv_products')->select('id','name','primary_unit','sale_price')->where('prod_services','services')->get();
        return view('sale_fmcg.serviceList',compact('services'));
    }
    public function  servicesSave(Request $request)
    {
        if($request->isMethod('get'))
        {
            $units=DB::table('sys_units')->get();
            return view('sale_fmcg.service',compact('units'));
        }
        else
        {
            if($request->id)
            {
               
                DB::table('inv_products')
                ->where('id',$request->id)
                ->update(
                [
                 'name' => $request->service,
                 'primary_unit'=>$request->unit,
                 'sale_price'=>$request->rate,
                ]);
             return redirect('Sales/Services/List');
            }
            $id = str::uuid()->toString();
            DB::table('inv_products')->insert([
                [  
                 'id'=>$id,
                 'name' => $request->service,
                 'primary_unit'=>$request->unit,
                 'sale_price'=>$request->rate,
                 'prod_services'=>'services'
                ], 
                                         ]);  
              return redirect('Sales/Services/List');
        }
    }
   //this function delete the service 
    public function removeService($id)
      {
        
        DB::table('inv_products')->where('id',$id)->delete();
        return back()->with('msg','Record Deleted successfully');
      }
   public function editService($id)
      {
        $units=DB::table('sys_units')->get();
        $service=DB::table('inv_products')->select('id','name','primary_unit','sale_price')->where('id',$id)->get()->first();
        return view('sale_fmcg.service',compact('service','units'));
      }
}
