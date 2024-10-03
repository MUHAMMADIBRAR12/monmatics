<?php

/*
 * The app core rights are with Solutions Wave.
 * For further help you can contact with info@solutionswave.com
 * All content of project are copyright with Solutions Wave.
 *
 * This class contains DB related functions of entire app.
 * However, logic of each module is located in their repsective models.
 */

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Auth;

class appLib
{
    public static function padingZero($value, $Zero = 4)
    {
        if (is_null($value) || $value == '')
            return '';

        return str_pad($value, $Zero, '0', STR_PAD_LEFT);
    }


    public static function showVal($val)
    {
        echo "<pre>";
        print_r($val);
        die();
    }

    public static function setDateFormat($date, $showTime = false)
    {
        $createDate = new DateTime($date);
        $date = $createDate->format('Y-m-d');
        // dd($date);
        return $date;
    }

    public static function showDateFormat()
    {
        $format = DB::table('sys_companies')->where('id',session('companyId'))->first();
        return $format->date_format ?? 'M-d-Y';
    }

    public static function setNumberFormat($value, $decimal = 2, $decimal_separator = ".",  $thousands_separator = "")
    {
        return  number_format($value, 2 , $decimal_separator ,  $thousands_separator);
    }

    public static function setCurrencyFormat($amount, $decimal = 2, $decimal_separator = ".", $thousands_separator = "")
    {

        return  number_format($amount, 2, $decimal_separator,  $thousands_separator);
    }


    public static function getHour($date)
    {
        $createDate = new DateTime($date);
        $hour = $createDate->format('h');
        return $hour;
    }
    public static function getMinutes($date)
    {
        $createDate = new DateTime($date);
        $minutes = $createDate->format('i');
        return $minutes;
    }

    public static function getOptions($type)
    {
        $options = DB::table('sys_options')->select('description')->where('type', $type)->where('status', 1)->orderBy('description')->get();
        return $options;
    }

    public static $types = ['Mr.', 'Mrs.', 'Dr.', 'Eng.'];
    public static $related_to = array ('Customer'=>'customer', 'lead'=>'lead', 'project'=>'project','contacts'=>'contacts');
    public static $contact_related_to = ['lead', 'customer', 'vendor'];
    public static $options_array = array(
        "Status Of Task" => "task_status",
        "Priority Of Task" => "task_priority",
        "Type Of Opportunities" => "opportunities_type",
        "Sale Stage Of Opportunities" => "opportunities_sale_stage",
        "Lead Source Of Opportunities" => "opportunities_lead_source",
        "Communication Type Of Calls" => "calls_communication_type",
        "Packaging Detail Of Product" => "product_packaging_detail",
        "Type Of Customer" => "customer_type",
        "Credit Limit Of Customer" => "customer_credit_limit",
        "Type Of Vendor" => "vendor_type",
        "Category Of Project" => "project_category",
        "Designation Of Employee" => "employee_designation",
        "Ticket Categories" => "ticket_category",
        "Ticket Status" => "ticket_status",
        "Ticket Priority" => "ticket_priority",

    );
    public static $hours = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
    public static function minutes()
    {
        $minutes = [];
        for ($x = 0; $x < 60; $x++) {
            $minutes[] = $x;
        }
        return $minutes;
    }
    //array of table according to related_to_type
    public static $related_table = array(
        "all" => array("pa_vendors", "crm_contacts", "crm_customers", "prj_projects"),
        "customer" => "crm_customers",
        "lead" => "crm_customers",
        "project" => "prj_projects",
        "contacts" => "crm_contacts",
        "vendor" => "pa_vendors",
    );






    //this function return array of common where clause like company_id and month
    public static function whereData($tableName = null)
    {
        $tableName = ($tableName) ? $tableName . "." : '';
        $whereData = array(
            $tableName . "company_id" => session('companyId'),
            $tableName . "month" => session('month'),
        );

        return $whereData;
    }


    public static function header()
    {
        $img = asset('public/assets/images/logo.jpg');
        $hiol = '<h2>Company Name</h2><img src=".$img."></img>';
        return $hiol;
    }


    public static function listHeader()
    {

        $comapnyName = DB::table('sys_companies')->select('name')->where('id', session('companyId'))->first()->name;
        $listHeader = "
                { extend: 'pageLength', className:'btn cl mr-2 px-3 rounded'},
                { extend: 'copy', className: 'btn bg-dark mr-2 px-3 rounded', title:'Products'},
                { extend: 'csv', className: 'btn btn-info mr-2 px-3 rounded', title:'Products'},
                { extend: 'pdf', className: 'btn btn-danger mr-2 px-3 rounded',title:title+\"" . $comapnyName . "\",
                    customize: function(doc) {
                        doc.styles.title = {
                        color: 'black',
                        fontSize: '10',
                        alignment: 'left'
                        }
                    } ,
                    header : true,
                    footer : true,
                },
                { extend: 'excel', className: 'btn btn-warning mr-2 px-3 rounded',title:'Products'},
                { extend: 'print', className: 'btn btn-success mr-2 px-3 rounded',title:title+\"<br>" . $comapnyName . "\",
                    customize: function(doc) {
                        doc.styles.title = {
                        color: 'black',
                        fontSize: '10',
                        alignment: 'left'
                        }
                    } ,

                },
                { extend: 'colvis', className:'visible btn rounded'},


        ";

        return $listHeader;
    }
    public static function setCheckBoxValue($array, $index, $type = 'yn')
    {
        if ($type == 'yn') {
            $yes = 'yes';
            $no = 'no';
        } else {
            $yes = 1;
            $no = 0;
        }
        if (is_array($array))
            return    array_key_exists($index, $array) ? $yes : $no;
        else
            return $no;
    }
    public static function amountInWords($amount)
    {
        // dd($amount);

        $symbol = DB::table('sys_currencies')
            ->where('code', session('code'))
            ->first();


        $decones = array(
            '01' => "One",
            '02' => "Two",
            '03' => "Three",
            '04' => "Four",
            '05' => "Five",
            '06' => "Six",
            '07' => "Seven",
            '08' => "Eight",
            '09' => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $ones = array(
            0 => "",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quadrillion"
        ); //limit t quadrillion
        // echo "<br>$amount<br>";
        $amount = number_format($amount, 2, ".", ",");
        $num_arr = explode(".", $amount);
        $wholenum = $num_arr[0];
        //echo "<br>$wholenum<br>";
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        //   dd($whole_arr);
        krsort($whole_arr);
        $rettxt = "";
        $t = 1;
        foreach ($whole_arr as $key => $i) {
            //            echo "<br>count$t     =";
            //            $t++;
            $i = (int)$i;
            if ($i < 20) {
                $rettxt .= $ones[$i];
            } elseif ($i < 100) {
                $rettxt .= $tens[substr($i, 0, 1)];
                $rettxt .= " " . $ones[substr($i, 1, 1)];
            } else {
                $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                if (substr($i, 1, 1) == 1) {
                    $rettxt .= " " . $ones[substr($i, 1, 1) . substr($i, 2, 1)];
                } else {
                    if ($i > 0) {
                        $rettxt .= " " . $tens[substr($i, 1, 1)];
                        $rettxt .= " " . $ones[substr($i, 2, 1)];
                    }
                }
            }
            if ($key > 0) {
                $rettxt .= " " . $hundreds[$key] . " ";
            }
        }
        //$rettxt = $rettxt."Only";

        if ($decnum > 0) {
            $rettxt .= " and ";
            if ($decnum < 20) {
                $rettxt .= $decones[$decnum];
            } elseif ($decnum < 100) {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= " " . $ones[substr($decnum, 1, 1)];
            }

            return $rettxt = $symbol->name . ' ' . $rettxt . ' ' . $symbol->sub_unit . ' only';
        }
        $rettxt = $symbol->name . ' ' . $rettxt . ' only';
        return $rettxt;
    }
}
