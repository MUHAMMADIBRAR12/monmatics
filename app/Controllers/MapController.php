<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class MapController extends BaseController
{
    function yandex(){
    	return view('map.yandex');
    }

    function jvector(){
        return view('map.jvector');
    }
}
