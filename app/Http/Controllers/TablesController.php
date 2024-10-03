<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class TablesController extends BaseController
{
    function normal(){
    	return view('tables.normal');
    }

    function datatable(){
    	return view('tables.datatable');
    }

    function editable(){
    	return view('tables.editable');
    }

    function footable(){
    	return view('tables.footable');
    }

    function color(){
    	return view('tables.color');
    }
}