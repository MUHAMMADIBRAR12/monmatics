<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class FormController extends BaseController
{
    function basic(){
    	return view('form.basic');
    }

    function advanced(){
    	return view('form.advanced');
    }

    function examples(){
    	return view('form.examples');
    }

    function validation(){
    	return view('form.validation');
    }

    function wizard(){
    	return view('form.wizard');
    }

    function editors(){
    	return view('form.editors');
    }

    function upload(){
    	return view('form.upload');
    }

    function summernote(){
    	return view('form.summernote');
    }
}