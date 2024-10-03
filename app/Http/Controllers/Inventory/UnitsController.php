<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class UnitsController extends Controller
{
    public function index()
    {
        $units=DB::table('sys_units')->get();
       // dd($units);
        return view('inventory.units_list',compact('units'));
    }

    public function form($id=null)
    {
        $base_units=DB::table('sys_units')->select('name','id')->get();
        if($id)
        {
            $unit=DB::table('sys_units')->where('id',$id)->get()->first();
            
            return view('inventory.units',compact('unit','base_units'));
        }
        else
        {
            return view('inventory.units',compact('base_units'));
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());
        $unit=array(
            "code"=>$request->code,
            "name"=>$request->name,
            "base_unit"=>$request->base_unit,
            "operator"=>$request->operator,
            "operator_value"=>$request->value,
        );
        if($request->base_unit== -1)
        {
            $unit['operator']='*';
            $unit['operator_value']= 1;
        }
        if($request->id)
        {
            DB::table('sys_units')->where('id',$request->id)->update($unit);
        }

        else
        {
            DB::table('sys_units')->insert($unit);
        }
        
        return redirect('Inventory/Unit/List');
    }



}
