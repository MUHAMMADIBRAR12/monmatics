<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{


    function index()
    {
        $total_invoice = DB::table('fs_purchase_invoices')
            ->where('amount_received', '=', 0)
            ->count();

        $total_sale_invoice = DB::table('sal_invoices')
            ->where('amount_received', '>', 0)
            ->count();

        $totla_inv_amount = DB::table('fs_purchase_invoices')
            ->where('amount_received', '=', 0)
            ->sum('fs_purchase_invoices.total_inv_amount');



        $totla_sale_inv_amount = DB::table('sal_invoices')
            ->select(DB::raw("(SUM(total_inv_amount * cur_rate) - SUM(amount_received * cur_rate))  as balance"))
            ->where('status', '=', 'approved')
            ->where('amount_received', '>', 0)->first();

        $cash = DB::table('fs_transmains')
            ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            ->select('fs_transdetails.*', 'fs_transmains.voucher_type')
            ->where('fs_transmains.post_status', '=', 'posted')
            ->whereIn('fs_transmains.voucher_type', [1, 2])
            ->count();

        $bank = DB::table('fs_transmains')
            ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            ->select('fs_transdetails.*', 'fs_transmains.voucher_type')
            ->where('fs_transmains.post_status', '=', 'posted')
            ->whereIn('fs_transmains.voucher_type', [3, 4])
            ->count();

        $total_cash = DB::table('fs_transmains')
            ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            ->select(DB::raw("(SUM(fs_transdetails.debit * fs_transdetails.cur_rate) - SUM(fs_transdetails.credit * fs_transdetails.cur_rate)) as total_cash"))
            ->where('fs_transmains.post_status', '=', 'posted')
            ->whereIn('fs_transmains.voucher_type', [1, 2])
            ->first();

        $total_bank = DB::table('fs_transmains')
            ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            ->select(DB::raw("(SUM(fs_transdetails.debit * fs_transdetails.cur_rate) - SUM(fs_transdetails.credit * fs_transdetails.cur_rate)) as total_bank"))
            ->where('fs_transmains.post_status', '=', 'posted')
            ->whereIn('fs_transmains.voucher_type', [3, 4])
            ->first();

        return view('dashboard.index', compact('total_invoice', 'totla_inv_amount', 'total_sale_invoice', 'totla_sale_inv_amount', 'bank', 'cash', 'total_bank', 'total_cash'));
    }



}
