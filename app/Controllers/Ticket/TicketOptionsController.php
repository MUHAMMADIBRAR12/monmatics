<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Libraries\dbLib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketOptionsController extends Controller
{
    var $type = array ('UTS'=>'ticket_status', 'KAT'=> 'ticket_category', 'PTY'=>'ticket_priority');
    var $typeTitle = array ('UTS'=>'Status', 'KAT'=> 'Category', 'PTY'=>'Priority');
    public function list($optionKey)
    {

        $optionType = $this->type[$optionKey];
        $title = $this->typeTitle[$optionKey];
        $options = DB::table('sys_options')->where('type', '=', $optionType)->get();
       // dd($options);
        return view('tickets.ticketOptionsList', compact('optionKey', 'options', 'title'));

    }

    public function option($key, $id = null)
    {

        $title = $this->typeTitle[$key];
        if ($id) {
            $optionData = DB::table('sys_options')->where('id', $id)->first();
            return view('tickets.ticketOptions', compact('key', 'optionData', 'title'));
        } else {
             return view('tickets.ticketOptions', compact('key', 'title'));
        }
    }

    public function optionAdd(Request $request)
    {
        $optionData = array(
            "type" => $this->type[$request->key],
            "description" => $request->option,
            "status" => 1,
        );

        if ($request->id)
            DB::table('sys_options')->where('id', $request->id)->update($optionData);
        else
            DB::table('sys_options')->insert($optionData);

        session()->flash('message', 'Transaction saved successfully');
        return redirect('Tmg/Status/'.$request->key )->with('message', 'Data saved successfully');

    }

    public function taskDelete($id){

      $tickethistory =  DB::table('tkt_tickets_history')->where('id' , '=' , $id)->first();
       DB::table('tkt_tickets_history')->where('id' , '=' , $id)->delete();
       DB::table('sys_attachments')->where('source_id' , '=' , $tickethistory->id)->delete();
        return redirect()->back()->with('delete_message','Deleted successfully');

    }
}
