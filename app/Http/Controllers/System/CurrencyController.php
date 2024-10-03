<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function currencyList($id = null)
    {

        if ($id) {
            $currencies = DB::table('sys_currencies')->get();
            $currency = DB::table('sys_currencies')->where('id', $id)->first();

            return view('system.currency', compact('currencies', 'currency'));
        }
        $currencies = DB::table('sys_currencies')->get();
        return view('system.currency', compact('currencies'));
    }

    public function currencyStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'shortform' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'Message' => 'Validation Error',
                'Error' => $validator->getMessageBag()->toArray(),
            ]);
        }


        $data = [
            'name' => $request->name,
            'symbol' => $request->shortform,
            'code' => $request->shortform,
            'rate' => $request->price,
            'sub_unit' => $request->sub_unit,

        ];

        if ($request->recordId) {
            DB::table('sys_currencies')->where('id', $request->recordId)->update($data);
            return response()->json([
                'id' => $request->recordId,
                'name' => $data['name'],
                'rate' => $data['rate'],
                'sub_unit' => $data['sub_unit'],
                'symbol' => $data['symbol'],

            ]);
            // return redirect()->back()->with('message','Currency Updated successfully');
        }

       $currency =  DB::table('sys_currencies')->insertGetId($data);
        return response()->json([
            'id' => $currency,
            'name' => $data['name'],
            'rate' => $data['rate'],
            'sub_unit' => $data['sub_unit'],
            'symbol' => $data['symbol'],

        ]);
        // return redirect()->back()->with('message','Currency Added successfully');
    }


    public function currencyDelete($id)
    {

        DB::table('sys_currencies')->where('id', $id)->delete();
        return redirect()->back()->with('error', 'Currency Deleted successfully');
    }
}
