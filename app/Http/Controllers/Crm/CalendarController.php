<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function getEvent()
    {
        $date = Carbon::now();
        $results = DB::table('crm_tasks')
            ->select('crm_tasks.*')
            // ->whereDate('crm_tasks.start_date', '<=', $date)
            ->get();

        $finalData = array();
        foreach ($results as $row) {
            $data = array(
                'title' => $row->subject,
                'start' => $row->start_date,
                // 'className'=>$row->className
            );
            array_push($finalData, $data);
        }
        return json_encode($finalData);
    }

    function calendarView()
    {
        $date = Carbon::now();
        $events = array();
        $results = DB::table('crm_tasks')
            ->whereDate('crm_tasks.start_date', '>=', $date)
            ->where('crm_tasks.assigned_to', '=', Auth::user()->id)
            ->get();
        foreach ($results as $res) {
            $events[] = [
                'id' => $res->id,
                'title' => $res->subject,
                'start' => $res->start_date,
            ];
        }

        return view('crm.calendar_view', compact('events'));
    }
}
