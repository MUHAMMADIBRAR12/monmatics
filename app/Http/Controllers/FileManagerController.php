<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class FileManagerController extends BaseController
{
    function all(){
    	return view('file-manager.all');
    }

    function documents(){
    	return view('file-manager.documents');
    }

    function media(){
        return view('file-manager.media');
    }

    function image(){
    	return view('file-manager.image');
    }
}
