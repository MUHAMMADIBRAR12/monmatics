<?php

namespace App\Http\Controllers\production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ReciepeController extends Controller
{
    public function index()
    {
        $reciepes = DB::table('wip_reciepes')
        ->join('inv_products', 'inv_products.name', '=', 'wip_reciepes.name')
        ->join('sys_units', 'sys_units.id', '=', 'inv_products.primary_unit')
        ->select('wip_reciepes.id', 'wip_reciepes.name', 'wip_reciepes.*', 'sys_units.name as unit_name')
        ->where('wip_reciepes.company_id', session('companyId'))
        // ->groupBy('wip_reciepes.*', 'wip_reciepes.name', 'sys_units.name')
        ->orderBy('wip_reciepes.name', 'ASC')
        ->get();

        return view('production.reciepe_list',compact('reciepes'));
    }

    public function form($id=null)
    {
        if($id)
        {
            $product=DB::table('wip_reciepes')->select('id','name')->where('id',$id)->get()->first();
            $reciepe=DB::table('wip_reciepes')
                        ->join('inv_products','inv_products.id','wip_reciepes.prod_id')
                        ->select('wip_reciepes.*','inv_products.name as prod_name')
                        ->where('wip_reciepes.id',$id)->get();
            //dd($reciepe);
            return view('production.reciepe',compact('product','reciepe'));
        }
        else
        {
            return view('production.reciepe');
        }
    }

    public function save(Request $request)
    {
        DB::table('wip_reciepes')->where('id',$request->id)->delete();
            $companyId = session('companyId');
            $id = str::uuid()->toString();
            $count = count($request->item_ID);
            for($i=0;$i<$count;$i++)
            {
                $reciepe = array (
                    'id'=>$id,
                    'name'=>$request->product,
                    'type'=>$request->type[$i],
                    'prod_id'=>$request->item_ID[$i],
                    'description'=>$request->description[$i],
                    'unit'=>$request->unit[$i],
                    'qty'=>$request->qty[$i],
                    'company_id'=>$companyId,
                );
                DB::table('wip_reciepes')->insert($reciepe);
            }

        return redirect('Production/Reciepe/List');
    }
}
