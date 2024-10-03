<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class GeoLocationController extends Controller
{
    public function index()
    {
        $geoLocations=DB::table('geo_locations')->select('id','name','label')->get();
        return view('sale_fmcg.geo_location_list',compact('geoLocations'));
    }


    public function form($id=null)
    {
        $parent_location=DB::table('geo_locations')->select('id','name')->get();
        if($id)
        {
            $geoLoaction=DB::table('geo_locations')->where('id',$id)->first();
            return view('sale_fmcg.geo_location',compact('parent_location','geoLoaction'));
        }
        
        return view('sale_fmcg.geo_location',compact('parent_location'));
    }

    public function save(Request $request)
    {
        $geoLoaction=array(
            "location_id"=>$request->parent_id,
            "name"=>$request->name,
            "label"=>$request->label,
            "trans"=>$request->trans_location,
        );
        if($request->id)
        {
            DB::table('geo_locations')->where('id',$request->id)->update($geoLoaction);
        }
        else
        {
            
            DB::table('geo_locations')->insert($geoLoaction);
        }
        return redirect('Sales_Fmcg/GeoLocation/List');
    }

    
    public function locationRemove($id)
    {
        DB::table('geo_locations')->where('id',$id)->delete();
        return back();
    }
}
