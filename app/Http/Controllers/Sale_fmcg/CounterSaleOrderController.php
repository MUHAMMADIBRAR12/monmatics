<?php

namespace App\Http\Controllers\Sale_fmcg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\dbLib;

class CounterSaleOrderController extends Controller
{
    public function index()
    {
        return view('sale_fmcg.counter_sale_order_list');
    }

    public function form()
    {
        $warehouses= dbLib::getWarehouses();
        return view('sale_fmcg.counter_sale_order',compact('warehouses'));
    }
}
