<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Libraries\dbLib;
use Illuminate\Support\Str;
use Carbon\Carbon;
class MIRController extends Controller
{
    public function index()
    {
        $mirs=DB::table('wip_material_issue_requests as mir')
                  ->leftjoin('sys_departments','sys_departments.id','=','mir.department')
                  ->leftjoin('sys_warehouses','sys_warehouses.id','=','mir.warehouse')
                  ->select('mir.id','mir.date','mir.month','mir.number','mir.editable','mir.batch','mir.person','sys_departments.name as department','sys_warehouses.name as warehouse')
                  ->where('mir.company_id',session('companyId'))
                  ->orderBy('mir.number', 'ASC')                                    
                  ->get();
        $company=DB::table('sys_companies')->select('name')->where('id',session('companyId'))->get()->first()->name;
        $title='Title:Material Issue\nCompany:'.$company.'\n\nDate:'.Carbon::now();
        
        return view('production.mir_list',compact('mirs','title'));
    }

    public function form($id=null)
    {
        $warehouses=dbLib::getWarehouses();
        $projects=dbLib::getProjects();
        $departments=dbLib::getDepartments();
        if($id)
        {
            $mir=DB::table('wip_material_issue_requests')->where('id',$id)->first();
            $mirDetails=DB::table('wip_material_issue_details')
                            ->leftjoin('inv_products','inv_products.id','wip_material_issue_details.prod_id')
                            ->select('wip_material_issue_details.*','inv_products.name')
                            ->where('mir_id',$id)
                            ->orderBy('display')
                            ->get();
            return view('production.mir',compact('warehouses','projects','departments','mir','mirDetails'));
        }
        else
        {
            return view('production.mir',compact('warehouses','projects','departments'));
        }
    }

    public function save(Request $request)
    {
        //dd($request->input());
        if($request->id)
        {
            $mir_id=$request->id;
            $this->update($request);
        }
        else
        {
            $mir_id= $this->store($request);
        }
        $this->updateMirDetails($mir_id, $request);
        return redirect('Production/MIR/List');
    }

    private function store($request)
    {
        $userId = Auth::id(); 
        $month = dbLib::getMonth($request->date);
        $number = dbLib::getNumber('wip_material_issue_requests');
        $id = str::uuid()->toString();
        $companyId = session('companyId');
        $mir = array (
            "id"=>$id,
            "date"=>$request->date,
            "month"=>$month,
            "number"=>$number,
            "warehouse"=>$request->warehouse,
            "person"=>$request->person,
            //"project"=>$request->project,
            "department"=>$request->department,
            "status"=>$request->status,
            "note"=>$request->note,
            "user_id"=>$userId,
            "company_id"=>$companyId,
            "close_status"=>0,
        ); 
        DB::table('wip_material_issue_requests')->insert($mir);
        return $id;
    }

    private function update($request)
    {
        $month = dbLib::getMonth($request->date);
        $userId = Auth::id(); 
        $mir = array (
            "date"=>$request->date,
            "month"=>$month,
            "warehouse"=>$request->warehouse,
            "person"=>$request->person,
           // "project"=>$request->project,
            "department"=>$request->department,
            "status"=>$request->status,
            "note"=>$request->note,
            "update_user_id"=>$userId,
            "last_updated"=>Carbon::now(),
        );
        DB::table('wip_material_issue_requests')->where('id', '=',$request->id)->update($mir);
    }

    private function updateMirDetails($mir_id, $request)
    {
        DB::table('wip_material_issue_details')->where('mir_id','=',$mir_id)->delete();
        $count = count($request->item_ID);
        for($i=0;$i<$count;$i++)
        {
            $id = str::uuid()->toString();
            $mirDetail = array (     
              "id"=>$id,
              "display"=>$i+1,
              "mir_id"=>$mir_id,
              "prod_id"=>$request->item_ID[$i],
              "description"=>$request->description[$i],
              "unit"=>$request->unit[$i],
              "qty_in_stock"=>$request->qty_in_stock[$i],
              "qty_order"=>$request->qty_order[$i],
            );
            DB::table('wip_material_issue_details')->insert($mirDetail);
        }
        
    }

    public function view($id)
    {
        $mir=DB::table('wip_material_issue_requests')
                ->join('sys_warehouses','sys_warehouses.id','=','wip_material_issue_requests.warehouse')
                ->join('sys_departments','sys_departments.id','=','wip_material_issue_requests.department')
                ->select('wip_material_issue_requests.*','sys_warehouses.name as warehouse_name','sys_departments.name as department_name')
                ->where('wip_material_issue_requests.id',$id)->first();
                
        $mirDetails=DB::table('wip_material_issue_details')
                        ->leftjoin('inv_products','inv_products.id','wip_material_issue_details.prod_id')
                        ->select('wip_material_issue_details.*','inv_products.name')
                        ->where('mir_id',$id)
                        ->get();
        return view('production.mir_view',compact('mir','mirDetails'));    
    }
}
