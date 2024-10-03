<?php

namespace App\Http\Controllers;

use Egulias\EmailValidator\Exception\UnclosedComment;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
//class AuthenticationController extends BaseController
class AuthenticationController extends Authenticatable
{

    //  protected $table = "sys_users";

    function login()
    {

        Auth::logout();
        if (Auth::check()) {
            return view('dashboard.index');
        } else {
            Auth::logout();
            return view('authentication.login');
        }
    }

    function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            $user_id = auth()->user()->id;

            // $total_invoice = DB::table('fs_purchase_invoices')
            //     ->where('amount_received', '=', 0)
            //     ->count();

            // $total_sale_invoice = DB::table('sal_invoices')
            //     ->where('amount_received', '>', 0)
            //     ->count();

            // $totla_inv_amount = DB::table('fs_purchase_invoices')
            //     ->where('amount_received', '=', 0)
            //     ->sum('fs_purchase_invoices.total_inv_amount');



            // $totla_sale_inv_amount = DB::table('sal_invoices')
            //     ->select(DB::raw("(SUM(total_inv_amount * cur_rate) - SUM(amount_received * cur_rate))  as balance"))
            //     ->where('status', '=', 'approved')
            //     ->where('amount_received', '>', 0)->first();

            // $cash = DB::table('fs_transmains')
            //     ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            //     ->select('fs_transdetails.*', 'fs_transmains.voucher_type')
            //     ->where('fs_transdetails.status', '=', 'posted')
            //     ->whereIn('fs_transmains.voucher_type', [1, 2])
            //     ->count();

            // $bank = DB::table('fs_transmains')
            //     ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            //     ->select('fs_transdetails.*', 'fs_transmains.voucher_type')
            //     ->where('fs_transdetails.status', '=', 'posted')
            //     ->whereIn('fs_transmains.voucher_type', [3, 4])
            //     ->count();

            // $total_cash = DB::table('fs_transmains')
            //     ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            //     ->select(DB::raw("(SUM(fs_transdetails.debit * fs_transdetails.cur_rate) - SUM(fs_transdetails.credit * fs_transdetails.cur_rate)) as total_cash"))
            //     ->where('fs_transdetails.status', '=', 'posted')
            //     ->whereIn('fs_transmains.voucher_type', [1, 2])
            //     ->first();

            // $total_bank = DB::table('fs_transmains')
            //     ->leftJoin('fs_transdetails', 'fs_transmains.id', '=', 'fs_transdetails.trm_id')
            //     ->select(DB::raw("(SUM(fs_transdetails.debit * fs_transdetails.cur_rate) - SUM(fs_transdetails.credit * fs_transdetails.cur_rate)) as total_bank"))
            //     ->where('fs_transdetails.status', '=', 'posted')
            //     ->whereIn('fs_transmains.voucher_type', [3, 4])
            //     ->first();
            $connection = DB::connection('mysql');
            $datas = $connection->table('sessions')
                ->where('user_id', $user_id)
                ->get();


            $this->saveToSessionTable($datas);

            return redirect(Auth::user()->route ?? 'Tmg/Listing');
            // return view('dashboard.index', compact('total_invoice', 'totla_inv_amount', 'total_sale_invoice', 'totla_sale_inv_amount', 'bank', 'cash', 'total_bank', 'total_cash'));
        } else {
            Session::flash('message', 'Invalid username or password!');
            return redirect('authentication/login');
        }

        // die('Invalied user');
        // return view('authentication.login');
        return view('dashboard.index');
    }
    function register()
    {
        return view('authentication.register');
    }

    function lockscreen()
    {
        return view('authentication.lockscreen');
    }

    function forgot()
    {
        return view('authentication.forgot');
    }

    function page404()
    {
        return view('authentication.page404');
    }

    function page500()
    {
        return view('authentication.page500');
    }

    function offline()
    {

        Auth::logout();
        return view('authentication.offline');
    }

    function logout()
    {

        Auth::logout();
        return view('authentication.login');
    }


    public function saveToSessionTable($datas)
    {

        foreach ($datas as $data) {
            $id = $data->id;
            $user_id = $data->user_id;
            $ip_address = $data->ip_address;
            $user_agent = $data->user_agent;
            $payload = $data->payload;
            $last_activity = $data->last_activity;

            DB::table('sessions')->updateOrInsert([
                'user_id' => $user_id,

            ], [
                'id' => $id,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'payload' => $payload,
                'last_activity' => $last_activity,
            ]);
        }
    }

    public function homemain(){

        return view('web.home');
    }


    public function page404notFound(){

        return view('authentication.404');
    }
}
