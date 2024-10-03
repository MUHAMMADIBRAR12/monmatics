<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ProjectController extends BaseController
{
    function projectList(){
    	return view('project.project-list');
    }

    function taskboard(){
    	return view('project.taskboard');
    }

    function ticketList(){
    	return view('project.ticket-list');
    }

    function ticketDetail(){
    	return view('project.ticket-detail');
    }
}
