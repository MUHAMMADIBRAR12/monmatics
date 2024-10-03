<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ChartController extends BaseController
{
    function echarts(){
    	return view('chart.echarts');
    }

    function c3(){
    	return view('chart.c3');
    }

    function morris(){
    	return view('chart.morris');
    }

    function flot(){
    	return view('chart.flot');
    }

    function chartjs(){
    	return view('chart.chartjs');
    }

    function sparkline(){
    	return view('chart.sparkline');
    }

    function knob(){
    	return view('chart.knob');
    }
}