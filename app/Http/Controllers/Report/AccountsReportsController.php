<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Libraries\accountsLib;
use App\Libraries\appLib;
use App\Libraries\swPDF;

use PDF;
use TCPDF;

class AccountsReportsController extends Controller
{
    public function coa()
    {
        return view('reports.coa');
    }

    public function coaList(Request $request)
    {

        //For Trialbalance report, a temp table is created and
        // all required data will be populated in temp table and
        // data will be fetched by simple query and send to blad.

        // Create a temp table to which buil temp
        $tempTable = "coas_temp" . time();
        Schema::create($tempTable, function (Blueprint $table) {
            //  $table->temporary();
            $table->id();
            $table->string('name');
            $table->tinyInteger('group_trans');
            //    $table->integer('order');
            $table->decimal('debit', $precision = 12, $scale = 2)->default('0');
            $table->decimal('credit', $precision = 12, $scale = 2)->default('0');
        });


        // dd($tempTable);
        $date = $request->date;
        accountsLib::getCoaTree(-1, $date, $tempTable, null);

        // Now send to blad
        $results = DB::table($tempTable)->select()->get();
        // dd($results);
        $trialBalance = array();
        foreach ($results as $result) {
            $row = array(
                'account' => $result->name,
                'group_trans' => $result->group_trans,
                'debit' => $result->debit,
                'credit' => $result->credit,
            );
            array_push($trialBalance, $row);
        }
        Schema::dropIfExists($tempTable);
        return $trialBalance;
    }


    public function trialBalance()
    {
        return view('reports.trialbalance');
    }

    public function trialBalanceList(Request $request)
    {
        //For Trialbalance report, a temp table is created and
        // all required data will be populated in temp table and
        // data will be fetched by simple query and send to blad.

        // Create a temp table to which buil temp
        $tempTable = "coas_trial_temp" . time();
        Schema::create($tempTable, function (Blueprint $table) {
            //  $table->temporary();
            $table->id();
            $table->string('name');
            $table->tinyInteger('group_trans');
            //    $table->integer('order');
            $table->decimal('debit', $precision = 12, $scale = 2)->default('0');
            $table->decimal('credit', $precision = 12, $scale = 2)->default('0');
        });


        // dd($tempTable);
        $date = $request->date;
        $from_date = $request->from_date;
        accountsLib::getCoaTree(-1, $date, $tempTable, $from_date);

        //accountsLib::getCoaTree(3, $date, $tempTable, $from_date);
        //accountsLib::getCoaTree(4, $date, $tempTable, $from_date);



        // if group account check is checked then delete all record with group_count = 1
        if ($request->groupAccount) {
            DB::table($tempTable)->where('group_trans', 1)->delete();
        }



        // Now send to blad
        $results = DB::table($tempTable)->select()->get();
        // dd($results);
        $trialBalance = array();
        foreach ($results as $result) {
            $row = array(
                'account' => $result->name,
                'group_trans' => $result->group_trans,
                'debit' => $result->debit,
                'credit' => $result->credit,
            );
            array_push($trialBalance, $row);
        }

        // Delete temp table
        Schema::dropIfExists($tempTable);

        return $trialBalance;
    }

    public function incomeStatement()
    {
        return view('reports.income_statement');
    }

    public function incomeStatementList(Request $request)
    {
        //For Trialbalance report, a temp table is created and
        // all required data will be populated in temp table and
        // data will be fetched by simple query and send to blad.

        // Create a temp table to which buil temp
        $tempTable = "coas_income_temp" . time();
        Schema::create($tempTable, function (Blueprint $table) {
            //  $table->temporary();
            $table->id();
            $table->string('name');
            $table->tinyInteger('group_trans');
            //    $table->integer('order');
            $table->decimal('debit', $precision = 12, $scale = 2)->default('0');
            $table->decimal('credit', $precision = 12, $scale = 2)->default('0');
        });


        $date = $request->date;
        $from_date = $request->from_date;

        accountsLib::getCoaTree(3, $date, $tempTable, $from_date);
        accountsLib::getCoaTree(4, $date, $tempTable, $from_date);

        $amount = DB::table($tempTable)
            ->select(DB::raw("(SUM(debit) - SUM(credit)) as balance"))->where('group_trans', 1)->first();

        if ($amount->balance > 0) {
            $debit = $amount->balance;
            $credit = 0;
        } else {
            $debit = 0;
            $credit = $amount->balance * -1;
        }

        $data = array(
            "name" => 'Profit / Loss',
            "group_trans" => 0,
            "debit" => $debit,
            "credit" => $credit,
        );
        DB::table($tempTable)->insert($data);

        // if group account check is checked then delete all record with group_count = 1
        if ($request->groupAccount) {
            DB::table($tempTable)->where('group_trans', 1)->delete();
        }

        // Now send to blad
        $results = DB::table($tempTable)->select()->get();
        // dd($results);
        $incomeStatement = array();
        foreach ($results as $result) {
            $row = array(
                'account' => $result->name,
                'group_trans' => $result->group_trans,
                'debit' => $result->debit,
                'credit' => $result->credit,
            );
            array_push($incomeStatement, $row);
        }

        // Delete temp table
        Schema::dropIfExists($tempTable);

        return $incomeStatement;
    }

    public function projectLedger()
    {
        $title = "Project Ledger";
        return view('reports.project_ledger', compact('title'));
    }

    public function projectLedgerList(Request $request)
    {
        $companyId = session('companyId');
        $month = session('month');
        $project_id = $request->project_id;
        $whereDateCluse = array();
        $gl = array();

        //        $opening_balance= accountsLib::getOpeningBalance($account,$request->from_date);
        //        $debit = ($opening_balance->balance>0)?$opening_balance->balance:0;
        //        $credit = ($opening_balance->balance<=0)?$opening_balance->balance:0;

        //        // Opening Balance
        //        $row= array(
        //            'document'=>' ',
        //            'date'=>'',
        //            'description'=>'Opening Balance',
        //            'debit'=>$debit,
        //            'credit'=> (isset($credit)) ? $credit : 0,
        //            'voucher_type'=>'',
        //            'id'=>'',
        //            'print_route'=>'',
        //            'route'=> '#',
        //        );
        //        array_push($gl,$row);

        if ($request->from_date) {
            $arrFromDate = array('fs_transmains.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('fs_transmains.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
        $results = DB::table('fs_transmains')
            ->leftjoin('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select('fs_transmains.*', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit')
            ->where('fs_transdetails.cost_center_id', '=', $project_id)
            ->where('fs_transdetails.company_id', '=', $companyId)
            //                    ->where('fs_transmains.month','=',$month)
            ->where('fs_transmains.post_status', '=', 'posted')
            ->where($whereDateCluse)
            ->orderBy('fs_transmains.date')
            ->get();
        $voucher_prefix = accountsLib::$voucher_prefix;
        $path = accountsLib::$path;
        foreach ($results as $result) {
            $row = array(
                'document' => $voucher_prefix[$result->voucher_type] . '' . $result->month . Str::documentPadding($result->number),
                'date' => date(appLib::showDateFormat(), strtotime($result->date)),
                'description' => $result->description,
                'debit' => $result->debit,
                'credit' => $result->credit,
                'voucher_type' => $result->voucher_type,
                'id' => $result->id,
                'print_route' => url($path[$result->voucher_type]) . 'Print/' . $result->id,
                'route' => ($result->post_status !== 'Posted' and $result->editable == 1) ? url($path[$result->voucher_type]) . '/' . $result->voucher_type . '/' . $result->id : '#',
            );
            array_push($gl, $row);
        }
        return $gl;
    }

    public function generalLedger()
    {
        return view('reports.general_ledger');
    }

    public function generalLedgerList(Request $request)
    {
        // return $request->genralopeningBalance;
        $companyId = session('companyId');
        $month = session('month');
        $account = $request->account_id;
        $whereDateCluse = array();
        $gl = array();
        $debit = 0;
        $credit = 0;

        if ($request->genralopeningBalance) {
            $opening_balance = accountsLib::getOpeningBalance($account, $request->from_date);
            $debit = ($opening_balance->balance > 0) ? $opening_balance->balance : 0;
            $credit = ($opening_balance->balance <= 0) ? ($opening_balance->balance * -1) : 0;

            // Opening Balance
            $row = array(
                'document' =>  '',
                'date' => '',
                'description' => 'Opening Balance',
                'debit' => $debit,
                'credit' => $credit,
                'voucher_type' => '',
                'id' => '',
                'print_route' => '',
                'route' => '#',
            );
            array_push($gl, $row);
        }


        if ($request->from_date) {
            $arrFromDate = array('fs_transmains.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('fs_transmains.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }

        $results = DB::table('fs_transmains')
            ->leftjoin('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select('fs_transmains.*', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transdetails.cur_rate')
            ->where('fs_transdetails.coa_id', '=', $account)
            ->where('fs_transdetails.company_id', '=', $companyId)
            ->where('fs_transmains.post_status', '=', 'Posted')
            ->where($whereDateCluse)
            ->orderBy('fs_transmains.date')
            ->get();
        $voucher_prefix = accountsLib::$voucher_prefix;
        $path = accountsLib::$path;
        foreach ($results as $result) {

            if ($request->foreignCurrency)   // Foreign Currency
            {

                $debit = $result->debit;
                $credit = $result->credit;
            } else        // Local Currency
            {
                $debit = $result->debit * $result->cur_rate;
                $credit = $result->credit * $result->cur_rate;
            }
            $row = array(
                'document' => $voucher_prefix[$result->voucher_type] . '' . $result->month . '-0000' . $result->number,
                'date' => date(appLib::showDateFormat(), strtotime($result->date)),
                'description' => $result->description,
                'debit' => $debit,
                'credit' => $credit,
                'voucher_type' => $result->voucher_type,
                'id' => $result->id,
                'print_route' => url($path[$result->voucher_type]) . 'Print/' . $result->id,
                'route' => ($result->post_status !== 'Posted' and $result->editable == 1) ? url($path[$result->voucher_type]) . '/' . $result->voucher_type . '/' . $result->id : '#',
            );
            array_push($gl, $row);
        }
        return $gl;
    }


    //Book function is used for both cash and bank
    public function book($coa_id)
    {
        if ($coa_id == 10)
            $title = 'Cash Book';
        else
            $title = 'Bank Book';
        $accounts = DB::table('fs_coas')->select('id', 'name')->where('coa_id', $coa_id)->get();
        return view('reports.book_list', compact('accounts', 'title'));
    }

    //book detail function get  opening balance and detail of cash and bank
    public function bookDetails(Request $request)
    {
        $companyId = session('companyId');
        $month = session('month');
        $account = $request->account_id;
        $whereDateCluse = array();
        $bookList = array();
        $debit = 0;
        $credit = 0;

        if ($request->openingBalance) {
            $opening_balance = accountsLib::getOpeningBalance($account, $request->from_date);
            $debit = ($opening_balance->balance > 0) ? $opening_balance->balance : 0;
            $credit = ($opening_balance->balance <= 0) ? ($opening_balance->balance * -1) : 0;
            // Opening Balance
            $row = array(
                'document' => ' ',
                'date' => '',
                'description' => 'Opening Balance',
                'debit' => $debit,
                'credit' => $credit,
                'voucher_type' => '',
                'id' => '',
                'print_route' => '',
                'route' => '#',
            );
            array_push($bookList, $row);
        }

        if ($request->type) {
            $arrFromDate = array('fs_transmains.voucher_type', '=', $request->type);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->from_date) {
            $arrFromDate = array('fs_transmains.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('fs_transmains.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }

        // End of Opening Balance
        $results = DB::table('fs_transmains')
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->select('fs_transmains.*', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit')
            ->whereIn('trm_id', function ($query) use ($account, $companyId) {
                $query->select('trm_id')
                    ->from('fs_transdetails')
                    ->where('coa_id', '=', $account)
                    ->where('company_id', $companyId);
            })
            ->where('coa_id', '!=', $account)
            // ->where('month','=',$month)
            ->where('fs_transmains.post_status', '=', 'posted')
            ->where($whereDateCluse)
            ->orderBy('date')
            ->get();

        $voucher_prefix = accountsLib::$voucher_prefix;
        $path = accountsLib::$path;
        foreach ($results as $result) {
            $row = array(
                'document' => $voucher_prefix[$result->voucher_type] . '' . $result->month . '-0000' . $result->number,
                'date' => date(appLib::showDateFormat(), strtotime($result->date)),
                'description' => $result->description,
                'debit' => $result->debit,
                'credit' => $result->credit,
                'voucher_type' => $result->voucher_type,
                'id' => $result->id,
                'print_route' => url($path[$result->voucher_type]) . 'Print/' . $result->id,
                'route' => ($result->post_status !== 'Posted' and $result->editable == 1) ? url($path[$result->voucher_type]) . '/' . $result->voucher_type . '/' . $result->id : '#',
            );
            array_push($bookList, $row);
        }
        return $bookList;
    }




    public function customerOutstanding()
    {
        return view('reports.customer_outstanding');
    }

    public function customerOutstandingDetail(Request $request)
    {
        $wahereClausees = appLib::whereData();
        $coa_id = $request->coa_id;
        $companyId = session('companyId');
        $month = session('month');
        $whereDateCluse = array();
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if ($request->from_date) {
            $arrFromDate = array('sal_invoices.date', '>=', $request->from_date);
            array_push($whereDateCluse, $arrFromDate);
        }
        if ($request->to_date) {
            $arrToDate = array('sal_invoices.date', '<=', $request->to_date);
            array_push($whereDateCluse, $arrToDate);
        }
        $formate = appLib::showDateFormat();
        if ($formate == 'd-M-Y') {
            $mysqlDateFormat = str_replace(['d', 'M', 'Y'], ['%d', '%M', '%Y'], $formate);
        } else if ($formate == 'M-d-Y') {
            $mysqlDateFormat = str_replace(['d', 'M', 'Y'], ['%d', '%M', '%Y'], $formate);
        } else if ($formate == 'Y-m-d') {
            $mysqlDateFormat = str_replace(['Y', 'm', 'd'], ['%Y', '%m', '%d'], $formate);
        }
        $customer = DB::table('sal_invoices')
            ->join('crm_customers', 'crm_customers.coa_id', '=', 'sal_invoices.cst_coa_id')
            ->select('crm_customers.name', 'sal_invoices.id', DB::raw("DATE_FORMAT(sal_invoices.date, '$mysqlDateFormat') as date1"), DB::raw("DATE_FORMAT(sal_invoices.due_date, '$mysqlDateFormat') as date2"),
            'sal_invoices.due_date', DB::raw("concat(sal_invoices.month,'-',LPAD(sal_invoices.number,4,0)) as inv_num"), 'sal_invoices.total_inv_amount', 'sal_invoices.amount_received')
            ->where('sal_invoices.cst_coa_id', $coa_id)
            ->where('sal_invoices.company_id', $companyId)
            ->whereRaw('sal_invoices.total_inv_amount != sal_invoices.amount_received')
            //->where('sal_invoices.month',$month)
            ->where($whereDateCluse)
            ->get();
        return $customer;
    }

    public function customerSummery()
    {
        return view('reports.customer_summery');
    }

    public function customerSummeryDetail(Request $request)
    {
        $date = $request->date;
        $companyId = session('companyId');
        $month = session('month');
        $customer = DB::table('fs_transmains')
            ->join('fs_transdetails', 'fs_transdetails.trm_id', '=', 'fs_transmains.id')
            ->join('crm_customers', 'crm_customers.coa_id', '=', 'fs_transdetails.coa_id')
            ->select('fs_transdetails.coa_id', 'crm_customers.name', DB::raw("(SUM(fs_transdetails.debit) - sum(fs_transdetails.credit)) as balance"))
            ->groupBy('fs_transdetails.coa_id', 'crm_customers.name')
            ->where('fs_transmains.date', '<=', $date)
            ->where('fs_transmains.company_id', '=', $companyId)
            //->where('fs_transmains.month','=',$month)
            ->get();
        return $customer;
    }

    // public function printVoucher($trmId)
    // {
    //     $transmains = DB::table('fs_transmains')
    //         ->leftJoin('users', 'users.id', '=', 'fs_transmains.created_by')
    //         ->select('fs_transmains.*', 'users.name as uname', DB::raw("concat(fs_transmains.month,'-',LPAD(fs_transmains.number,4,0)) as doc_num"))->where('fs_transmains.id', $trmId)->first();


    //     if ($transmains) {
    //         if ($transmains->voucher_type == '1'  || $transmains->voucher_type == '3')   // if Voucher is reciept Voucher
    //         {

    //             $Title = "Receipt";
    //             // Get value of Bank/Cash
    //             $transCashBank = DB::table('fs_transdetails')
    //                 ->select('fs_coas.name')
    //                 ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
    //                 ->where('trm_id', $trmId)->where('credit', '=', 0)->first();

    //             $transDetails = DB::table('fs_transdetails')
    //                 ->select('fs_coas.name', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'sys_currencies.name as currency_name')
    //                 ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
    //                 ->join('sys_currencies', 'sys_currencies.id', '=', 'fs_transdetails.cur_id')
    //                 ->where('trm_id', $trmId)->where('debit', '=', 0)->get();
    //         } elseif ($transmains->voucher_type == '2'  || $transmains->voucher_type == '4') // if voucher is payment voucher.
    //         {
    //             $Title = "Payments";
    //             // Get value of Bank/Cash
    //             $transCashBank = DB::table('fs_transdetails')
    //                 ->select('fs_coas.name')
    //                 ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
    //                 ->where('trm_id', $trmId)->where('debit', '=', 0)->first();

    //             // Get Value of Accounts
    //             $transDetails = DB::table('fs_transdetails')
    //                 ->select('fs_coas.name', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transdetails.cur_id')
    //                 ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
    //                 ->where('trm_id', $trmId)->where('credit', '=', 0)->get();
    //         }


    //         $X = 78;
    //         $Y = 20;
    //         $Ln = 5;
    //         $B = 0;
    //         $LH = 5;
    //         $W = 20;
    //         $pdf = new swPDF();
    //         $pdf->SetAutoPageBreak(true, 30);
    //         $pdf->SetHeaderMargin(50);
    //         $pdf->setH3();
    //         $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
    //         $X = 11;
    //         $Y = 30;
    //         $pdf->SetTitle('Bank Receipt');
    //         $Y = $pdf->GetY() + 30;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setTitleFont2();
    //         $pdf->Cell($pdf->getPageWidth() - ($X * 2), 10, $Title, $B, 0, 'C', 0, '', 0);
    //         $Y = $pdf->GetY() + 10;
    //         $X = 11;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($W, $LH, 'Account:', $B, 0, 'L', 0, '', 0);
    //         $Y = 50;
    //         $X = 31;
    //         $VW = 50;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($VW, $LH, $transCashBank->name ?? '', $B, 0, 'L', 0, '', 0);
    //         $Y = 50;
    //         $X = $pdf->GetX() + 78;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($W, $LH, 'BRV#:', $B, 0, 'R', 0, '', 0);
    //         $Y = $pdf->GetY() + 5;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($W, $LH, 'Date:', $B, 0, 'R', 0, '', 0);
    //         $X = $pdf->GetX();
    //         $Y = 50;
    //         $VW = 30;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($VW, $LH, $transmains->doc_num, $B, 0, 'L', 0, '', 0);
    //         $Y = $pdf->GetY() + 5;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1(10);
    //         $pdf->Cell($VW, $LH, date(appLib::showDateFormat(), strtotime($transmains->date)), $B, 0, 'L', 0, '', 0);
    //         $Y = $pdf->GetY();
    //         $X = $pdf->GetX();
    //         $pdf->Ln($Ln + 4);

    //         $VoucherData = "";
    //         $i = 1;
    //         $totalAmount = 0;
    //         foreach ($transDetails as $transdetail) {
    //             $debit = $transdetail->debit;
    //             $credit = $transdetail->credit;
    //             $amount = ($debit > 0) ? $debit : $credit;
    //             $VoucherData .= '<tr>
    //                 <td style="font-size:10px; text-align:center">' . $i . '</td>
    //                 <td style="font-size:10px; text-align:left;">&nbsp;&nbsp;' . $transdetail->name ?? '' . '</td>
    //                 <td style="font-size:10px; text-align:left;">&nbsp;&nbsp;' . $transdetail->description ?? '' . '</td>
    //                 <td style="font-size:10px; text-align:right;">' . $transdetail->currency_name ?? '' . '</td>
    //             </tr>';
    //             $i++;
    //             $totalAmount += $amount;
    //         }

    //         $VoucherData .= '
    //         <tr style="background-color:white;">
    //             <td> </td>
    //             <td> </td>
    //             <td style="text-align:center; font-size: 11px;">***Total***</td>
    //             <td style="font-size:10px; text-align:right;">&nbsp;&nbsp;' . appLib::setNumberFormat($totalAmount, 2) . '</td>
    //             <td></td>
    //         </tr>';

    //         $html = '
    //         <table cellspacing="0" cellpadding="1" border="0.1">
    //             <tr>
    //             <th style="width: 90px;text-align:center;font-size:11px;">Code</th>
    //             <th style="width: 150px; text-align:center;font-size:11px;">Account Name</th>
    //             <th style="width: 210px; text-align:center;font-size:11px;">Description</th>
    //             <th style="text-align:center;width: 80px;font-size:11px;">Currency Name/Amount</th>
    //             </tr>' . $VoucherData .
    //             '</table>';

    //         // Print text using writeHTMLCell()
    //         $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    //         $X = 10;
    //         $Y = $pdf->GetY() + 2;
    //         $Ln = 5;
    //         $B = 0;
    //         $LH = 5;
    //         $W = 70;
    //         $pdf->ln(2);
    //         $pdf->SetXY($X, $Y);

    //         // Calculate the width for each line of the amount in words
    //         $line1 = substr(appLib::amountInWords($totalAmount), 0, 40);
    //         $line2 = substr(appLib::amountInWords($totalAmount), 40);

    //         $pdf->SetFont($pdf->setSWFont, 'U');

    //         // Print the first line of the amount in words
    //         $pdf->Cell($W, $LH, 'Amount In Word: ' . $line1, $B, 0, 'L', 0, '', 0);

    //         // Move to the next line and print the second line
    //         $pdf->SetXY($X, $pdf->GetY() + $LH);
    //         $pdf->Cell($W, $LH, $line2, $B, 0, 'L', 0, '', 0);


    //         $complex_cell_border = array(
    //             'T' => array('width' => 1, 'color' => array(0, 255, 0), 'dash' => 4, 'cap' => 'butt'),
    //             'R' => array('width' => 2, 'color' => array(255, 0, 255), 'dash' => '1,3', 'cap' => 'round'),
    //             'B' => array('width' => 3, 'color' => array(0, 0, 255), 'dash' => 0, 'cap' => 'square'),
    //             'L' => array('width' => 4, 'color' => array(255, 0, 255), 'dash' => '3,1,0.5,2', 'cap' => 'butt'),
    //         );
    //         $Y = ($Y < 100) ? 120 : $Y;
    //         $B = 0;
    //         $X = 10;
    //         $XW = 45;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setT1();
    //         $pdf->Cell($XW, $LH, $transmains->uname, $B, 0, 'C', 0, '', 0);
    //         $Y = $pdf->GetY() + 3;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->setH3();
    //         $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
    //         $pdf->ln(3);
    //         $pdf->Cell($XW, $LH, 'Prepared By', $B, 0, 'C', 0, '', 0);
    //         $X = $pdf->GetX();
    //         $pdf->SetXY($X, $Y);
    //         $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
    //         $X = $pdf->GetX();
    //         $pdf->SetXY($X, $Y);
    //         $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
    //         $X = $pdf->GetX();
    //         $pdf->SetXY($X, $Y);
    //         $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
    //         $pdf->SetX($pdf->GetX());
    //         $Y = $pdf->GetY() + 3;
    //         $X = 55;
    //         $pdf->SetXY($X, $Y);
    //         $pdf->Cell($XW, $LH, 'Verified By', $B, 0, 'C', 0, '', 0);
    //         $pdf->SetX($pdf->GetX());
    //         $pdf->Cell($XW, $LH, 'Approved By', $B, 0, 'C', 0, '', 0);
    //         $pdf->SetX($pdf->GetX());
    //         $pdf->Cell($XW, $LH, 'Received By', $B, 0, 'C', 0, '', 0);
    //         $pdf->Output('Bank Receipt.pdf', 'I');
    //     } else
    //         return redirect()->back();
    // }


    public function printVoucher($trmId)
    {
        $transmains = DB::table('fs_transmains')
            ->leftJoin('users', 'users.id', '=', 'fs_transmains.created_by')
            ->select('fs_transmains.*', 'users.name as uname', DB::raw("concat(fs_transmains.month,'-',LPAD(fs_transmains.number,4,0)) as doc_num"))->where('fs_transmains.id', $trmId)->first();


        if ($transmains) {
            if ($transmains->voucher_type == '1'  || $transmains->voucher_type == '3')   // if Voucher is reciept Voucher
            {

                $Title = "Receipt";
                // Get value of Bank/Cash
                $transCashBank = DB::table('fs_transdetails')
                    ->select('fs_coas.name')
                    ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->where('trm_id', $trmId)->where('credit', '=', 0)->first();

                $transDetails = DB::table('fs_transdetails')
                    ->select('fs_coas.name', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'sys_currencies.name as currency_name')
                    ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->join('sys_currencies', 'sys_currencies.id', '=', 'fs_transdetails.cur_id')
                    ->where('trm_id', $trmId)->where('debit', '=', 0)->get();
            } elseif ($transmains->voucher_type == '2'  || $transmains->voucher_type == '4') // if voucher is payment voucher.
            {
                $Title = "Payments";
                // Get value of Bank/Cash
                $transCashBank = DB::table('fs_transdetails')
                    ->select('fs_coas.name')
                    ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->where('trm_id', $trmId)->where('debit', '=', 0)->first();

                // Get Value of Accounts
                $transDetails = DB::table('fs_transdetails')
                    ->select('fs_coas.name', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit', 'fs_transdetails.cur_id')
                    ->join('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
                    ->where('trm_id', $trmId)->where('credit', '=', 0)->get();
            }


            $X = 78;
            $Y = 20;
            $Ln = 5;
            $B = 0;
            $LH = 5;
            $W = 20;
            $pdf = new swPDF();
            $pdf->SetAutoPageBreak(true, 30);
            $pdf->SetHeaderMargin(50);
            $pdf->setH3();
            $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
            $X = 11;
            $Y = 30;
            $pdf->SetTitle('Bank Receipt');
            $Y = $pdf->GetY() + 30;
            $pdf->SetXY($X, $Y);
            $pdf->setTitleFont2();
            $pdf->Cell($pdf->getPageWidth() - ($X * 2), 10, $Title, $B, 0, 'C', 0, '', 0);
            $Y = $pdf->GetY() + 10;
            $X = 11;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($W, $LH, 'Account:', $B, 0, 'L', 0, '', 0);
            $Y = 50;
            $X = 31;
            $VW = 50;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($VW, $LH, $transCashBank->name ?? '', $B, 0, 'L', 0, '', 0);
            $Y = 50;
            $X = $pdf->GetX() + 78;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($W, $LH, 'BRV#:', $B, 0, 'R', 0, '', 0);
            $Y = $pdf->GetY() + 5;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($W, $LH, 'Date:', $B, 0, 'R', 0, '', 0);
            $X = $pdf->GetX();
            $Y = 50;
            $VW = 30;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($VW, $LH, $transmains->doc_num, $B, 0, 'L', 0, '', 0);
            $Y = $pdf->GetY() + 5;
            $pdf->SetXY($X, $Y);
            $pdf->setT1(10);
            $pdf->Cell($VW, $LH, date(appLib::showDateFormat(), strtotime($transmains->date)), $B, 0, 'L', 0, '', 0);
            $Y = $pdf->GetY();
            $X = $pdf->GetX();
            $pdf->Ln($Ln + 4);

            $VoucherData = "";
            $i = 1;
            $totalAmount = 0;
            foreach ($transDetails as $transdetail) {
                $debit = $transdetail->debit;
                $credit = $transdetail->credit;
                $amount = ($debit > 0) ? $debit : $credit;
                $VoucherData .= '<tr>
                <td style="font-size:10px; text-align:center">' . $i . '</td>
                <td style="font-size:10px; text-align:left;">&nbsp;&nbsp;' . ($transdetail->name ?? '') . '</td>
                <td style="font-size:10px; text-align:left;">&nbsp;&nbsp;' . ($transdetail->description ?? '') . '</td>
                <td style="font-size:10px; text-align:right;">' . ($transdetail->currency_name ?? '') . '</td>
            </tr>';
            $i++;
            $totalAmount += $amount;

            }

            $VoucherData .= '
            <tr style="background-color:white;">
                <td> </td>
                <td> </td>
                <td style="text-align:center; font-size: 11px;">***Total***</td>
                <td style="font-size:10px; text-align:right;">&nbsp;&nbsp;' . appLib::setNumberFormat($totalAmount, 2) . '</td>
                <td></td>
            </tr>';

            $html = '
            <table cellspacing="0" cellpadding="1" border="0.1">
                <tr>
                <th style="width: 90px;text-align:center;font-size:11px;">Code</th>
                <th style="width: 150px; text-align:center;font-size:11px;">Account Name</th>
                <th style="width: 210px; text-align:center;font-size:11px;">Description</th>
                <th style="text-align:center;width: 80px;font-size:11px;">Currency Name/Amount</th>
                </tr>' . $VoucherData .
                '</table>';

                // dd($html);
            // Print text using writeHTMLCell()
            $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
            $X = 10;
            $Y = $pdf->GetY() + 2;
            $Ln = 5;
            $B = 0;
            $LH = 5;
            $W = 70;
            $pdf->ln(2);
            $pdf->SetXY($X, $Y);

            // Calculate the width for each line of the amount in words
            $line1 = substr(appLib::amountInWords($totalAmount), 0, 40);
            $line2 = substr(appLib::amountInWords($totalAmount), 40);

            $pdf->SetFont($pdf->setSWFont, 'U');

            // Print the first line of the amount in words
            $pdf->Cell($W, $LH, 'Amount In Word: ' . $line1, $B, 0, 'L', 0, '', 0);

            // Move to the next line and print the second line
            $pdf->SetXY($X, $pdf->GetY() + $LH);
            $pdf->Cell($W, $LH, $line2, $B, 0, 'L', 0, '', 0);


            $complex_cell_border = array(
                'T' => array('width' => 1, 'color' => array(0, 255, 0), 'dash' => 4, 'cap' => 'butt'),
                'R' => array('width' => 2, 'color' => array(255, 0, 255), 'dash' => '1,3', 'cap' => 'round'),
                'B' => array('width' => 3, 'color' => array(0, 0, 255), 'dash' => 0, 'cap' => 'square'),
                'L' => array('width' => 4, 'color' => array(255, 0, 255), 'dash' => '3,1,0.5,2', 'cap' => 'butt'),
            );
            $Y = ($Y < 100) ? 120 : $Y;
            $B = 0;
            $X = 10;
            $XW = 45;
            $pdf->SetXY($X, $Y);
            $pdf->setT1();
            $pdf->Cell($XW, $LH, $transmains->uname, $B, 0, 'C', 0, '', 0);
            $Y = $pdf->GetY() + 3;
            $pdf->SetXY($X, $Y);
            $pdf->setH3();
            $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
            $pdf->ln(3);
            $pdf->Cell($XW, $LH, 'Prepared By', $B, 0, 'C', 0, '', 0);
            $X = $pdf->GetX();
            $pdf->SetXY($X, $Y);
            $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
            $X = $pdf->GetX();
            $pdf->SetXY($X, $Y);
            $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
            $X = $pdf->GetX();
            $pdf->SetXY($X, $Y);
            $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, '', 0);
            $pdf->SetX($pdf->GetX());
            $Y = $pdf->GetY() + 3;
            $X = 55;
            $pdf->SetXY($X, $Y);
            $pdf->Cell($XW, $LH, 'Verified By', $B, 0, 'C', 0, '', 0);
            $pdf->SetX($pdf->GetX());
            $pdf->Cell($XW, $LH, 'Approved By', $B, 0, 'C', 0, '', 0);
            $pdf->SetX($pdf->GetX());
            $pdf->Cell($XW, $LH, 'Received By', $B, 0, 'C', 0, '', 0);
            $pdf->Output('Bank Receipt.pdf', 'I');
        } else
            return redirect()->back();
    }

    public function printJVoucher($trmId)
    {

        $transmains = DB::table('fs_transmains')
            ->join('users', 'users.id', '=', 'fs_transmains.created_by')
            ->select('fs_transmains.*', 'users.name as username', DB::raw("concat(fs_transmains.month,'-',LPAD(fs_transmains.number,4,0)) as doc_num"))->where('fs_transmains.id', $trmId)->first();
        $transDetails = DB::table('fs_transdetails')
            ->select('fs_coas.name', 'fs_transdetails.description', 'fs_transdetails.debit', 'fs_transdetails.credit')
            ->leftjoin('fs_coas', 'fs_coas.id', '=', 'fs_transdetails.coa_id')
            ->where('trm_id', $trmId)->get();


        //dd($symbol);

        $X = 78;
        $Y = 20;
        $Ln = 5;
        $B = 0;
        $LH = 5;
        $W = 20;
        $pdf = new swPDF();
        $pdf->SetAutoPageBreak(true, 30);
        $pdf->SetHeaderMargin(50);
        $pdf->setH3();
        $pdf->AddPage('P', 'pt', ['format' => [1000, 1000], 'Rotate' => 270]);
        $X = 11;
        $Y = 30;
        $pdf->SetTitle('Bank Receipt');
        $Y = $pdf->GetY() + 30;
        $pdf->SetXY($X, $Y);
        $pdf->setTitleFont2();
        $pdf->Cell($pdf->getPageWidth() - ($X * 2), 10, 'Journal Voucher', $B, 0, 'C', 0, 'B', 0);
        $Y = $pdf->GetY() + 10;
        $X = 11;
        $X = 92;
        $VW = 50;
        $pdf->SetXY($X, $Y);
        //$Y=$pdf->GetY()+5;
        $Y = 50;
        $X = $pdf->GetX() + 68;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell($W, $LH, 'JV#:', $B, 0, 'R', 0, 'B', 0);
        $Y = $pdf->GetY() + 5;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell($W, $LH, 'Date:', $B, 0, 'R', 0, 'B', 0);
        $X = $pdf->GetX();
        $Y = 50;
        $VW = 30;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell($VW, $LH, ($transmains->doc_num??''), $B, 0, 'L', 0, 'B', 0);
        $Y = $pdf->GetY() + 5;
        $pdf->SetXY($X, $Y);
        $pdf->setT1(10);
        $pdf->Cell($VW, $LH, appLib::setDateFormat($transmains->date ?? ''), $B, 0, 'L', 0, 'B', 0);
        $Y = $pdf->GetY();
        $X = $pdf->GetX();
        $pdf->Ln($Ln + 5);


        $VoucherData = "";
        $i = 1;
        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($transDetails as $transdetail) {
            $amount = ($transdetail->debit > 0) ? $transdetail->debit : $transdetail->credit;
            $VoucherData .= '<tr>
                <td style="font-size:10px; text-align:center;">' . $i . '</td>
                <td style="font-size:9px;"> ' . $transdetail->name . '</td>
                <td style="font-size:9px;"> ' . $transdetail->description . '</td>
                <td style="font-size:9px; text-align:right;">' . appLib::setNumberFormat($transdetail->debit, 2) . '</td>
                <td style="font-size:9px; text-align:right;">' . appLib::setNumberFormat($transdetail->credit, 2) . '</td>
                </tr>';
            $i++;
            $totalDebit += $transdetail->debit;
            $totalCredit += $transdetail->credit;
        }
        $VoucherData .= '
            <tr style="background-color:white;">
             <td> </td>
             <td> </td>
             <td style="text-align:center; font-size: 11px;">***Total***</td>
             <td style="font-size:10px; text-align:right;">&nbsp;&nbsp;' . appLib::setNumberFormat($totalDebit, 2) . '</td>
             <td style="font-size:10px; text-align:right;">&nbsp;&nbsp;' . appLib::setNumberFormat($totalCredit, 2) . '</td>
        </tr>
            ';
        $html = '
            <table cellspacing="0" cellpadding="1" border="1">
            <tr style=" border-bottom: 0;">
            <th style="width: 60px; text-align:center; font-size: 11px;">Code</th>
            <th style="width: 130px; text-align:center;font-size: 11px;">Account Name</th>
            <th style="width: 203px; text-align:center;font-size: 11px;">Description</th>
            <th style="width: 70px; text-align:center;font-size: 11px;">Debit</th>
            <th style="width: 70px; text-align:center;font-size: 11px;">Credit</th>
            </tr>' . $VoucherData .
            '</table>';
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        //$pdf->ln();

        $X = 10;
        $Y = $pdf->GetY() + 2;
        $Ln = 5;
        $B = 0;
        $LH = 5;
        $W = 70;
        $pdf->ln(2);
        $pdf->SetXY($X, $Y);
        $pdf->SetFont($pdf->setSWFont, 'U');

        $pdf->Cell($W, $LH, 'Amount In Word: ' . appLib::amountInWords($totalDebit), $B, 0, 'L', 0, 'B', 0);
        // $pdf->Cell($W,$LH,NumConvert::word(), $B, 0, 'L', 0, 'B', 0);
        $Y = ($Y < 100) ? 120 : $Y;
        $B = 0;
        $X = 10;
        $XW = 45;
        $pdf->SetXY($X, $Y);
        $pdf->setT1();
        $pdf->Cell($XW, $LH, ($transmains->username ??''), $B, 0, 'C', 0, 'B', 0);
        $Y = $pdf->GetY() + 3;
        $pdf->SetXY($X, $Y);
        $pdf->setH3();
        $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, 'B', 0);
        $pdf->ln(3);
        $pdf->Cell($XW, $LH, 'Prepared By', $B, 0, 'C', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, 'B', 0);
        $X = $pdf->GetX();
        $pdf->SetXY($X, $Y);
        $pdf->Cell($XW, $LH, '---------------------', $B, 0, 'C', 0, 'B', 0);
        $pdf->SetX($pdf->GetX());
        $Y = $pdf->GetY() + 3;
        $X = 55;
        $pdf->SetXY($X, $Y);
        $pdf->Cell($XW, $LH, 'Verified By', $B, 0, 'C', 0, 'B', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($XW, $LH, 'Approved By', $B, 0, 'C', 0, 'B', 0);
        $pdf->SetX($pdf->GetX());
        $pdf->Cell($XW, $LH, 'Received By', $B, 0, 'C', 0, 'B', 0);
        $pdf->Output('Bank Receipt.pdf', 'I');
    }
}
