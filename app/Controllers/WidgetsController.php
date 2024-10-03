<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class WidgetsController extends BaseController
{
    function app(){
    	return view('widgets.app');
    }

    function data(){
    	return view('widgets.data');
    }
}