<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ComponentsController extends BaseController
{
    function ui(){
    	return view('components.ui');
    }

    function alerts(){
    	return view('components.alerts');
    }

    function collapse(){
    	return view('components.collapse');
    }

    function colors(){
    	return view('components.colors');
    }

    function dialogs(){
        return view('components.dialogs');
    }

    function list(){
    	return view('components.list');
    }

    function media(){
    	return view('components.media');
    }

    function modals(){
    	return view('components.modals');
    }

    function notifications(){
    	return view('components.notifications');
    }

    function progressbars(){
    	return view('components.progressbars');
    }

    function range(){
    	return view('components.range');
    }

    function sortable(){
    	return view('components.sortable');
    }

    function tabs(){
    	return view('components.tabs');
    }

    function waves(){
    	return view('components.waves');
    }
}
