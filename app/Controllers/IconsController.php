<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class IconsController extends BaseController
{
    function material(){
    	return view('icons.material');
    }

    function themify(){
    	return view('icons.themify');
    }

    function weather(){
    	return view('icons.weather');
    }
}