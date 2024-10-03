<?php

namespace App\Http\Controllers\System;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\appLib;

class ModulesController extends Controller
{
    //
    public function index()
    {
        $modules = DB::table('sys_modules')
            ->select('sys_modules.*')
            ->where('mdl_id', -1)->get();
        return view('system.module_list', compact('modules'));
        //
    }
    public function viewData($id = null)
    {
        $module = DB::table('sys_modules')
            ->select('sys_modules.*')
            ->where('id', $id)->first();
        //dd($moduledetail);

        $moduledetail = DB::table('sys_modules')
            ->select('sys_modules.*')
            ->where('mdl_id', $id)->get();
        //dd($moduledetail);
        return view('system.module_view', compact('module', 'moduledetail'));
    }


    public function saveData(Request $request)
    {
        // return $request->all();

        $ids = $request->id;
        $parent_Module = $request->parentModule;
        $parent_status = ($request->parent_status == 'on') ? 1 : 0;
        $parent_id = $request->parent_id;
        DB::table('sys_modules')->where('id', $parent_id)->update(array('status' => $parent_status, 'module' => $parent_Module));
        $i = 0;
        $data = array();
        foreach ($ids as $id) {
            $status =   appLib::setCheckBoxValue($request->status, $i, '');
            $childModule = $request->childModule[$i];

            DB::table('sys_modules')->where('id', $id)->update(array('status' => $status, 'module' => $childModule));
            $i++;
        }

        // For New modules
        $newModules = $request->childModule_new;
        foreach ($newModules as $newModule) {
            if (trim($newModule)) {
                $data = array(
                    "mdl_id" => $parent_id,
                    "status" => 1,
                    "module" => $newModule
                );
                DB::table('sys_modules')->insert($data);
            }
        }

        // $query_update = DB::table('sys_modules')->whereIn('id',$data)->update(array('status'=>$data));
        return back();
    }
}
