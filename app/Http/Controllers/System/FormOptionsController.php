<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormOptionsController extends Controller
{
    public function index()
    {
        $options = DB::table('sys_options')->get();
        return view('system.form_options_list', compact('options'));
    }

    public function form($id = null)
    {

        if ($id) {
            $optionData = DB::table('sys_options')->where('id', $id)->first();
            return view('system.form_options', compact('optionData'));
        } else {
            return view('system.form_options');
        }
    }

    public function save(Request $request)
    {
        $optionData = array(
            "type" => $request->option_type,
            "description" => $request->value,
            "status" => 1,
        );


        if ($request->id)
            DB::table('sys_options')->where('id', $request->id)->update($optionData);
        else
            DB::table('sys_options')->insert($optionData);

        return "Value updated successfully";
    }

    public function option_hide($id)
    {
        DB::table('sys_options')->where('id', $id)->delete();
        return redirect('Admin/FormOptions/List');
    }
}
