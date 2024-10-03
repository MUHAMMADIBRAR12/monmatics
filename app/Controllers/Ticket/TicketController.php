<?php




namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Libraries\appLib;
use App\Libraries\dbLib;
use App\Mail\TicketAssigned;
use App\Mail\TicketCreated;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Null_;

class TicketController extends Controller
{


    public function list(Request $request, $type = null)
    {

        session()->forget(['priority', 'department', 'status', 'related_to', 'category', 'related_to_id']);

        $userId = Auth::id();
        //dd($userId );

        if ($type == 'New') {
            $where = array(['status', '=', 'New']);
        } elseif ($type == 'open') {
            $where = array(['is_closed', '=', NULL]);
        } elseif ($type == 'me') {
            $where = array(['is_closed', '=', NULL], ['assign_to', '=', $userId]);
        } elseif ($type == 'closed') {
            $where = array(['status', '=', 'Closed'], ['is_closed', '=', 'on']);
        } else {
            $where = array(['is_closed', '=', NULL], ['assign_to', '=', $userId]);
        }
        $myTickets = DB::table('tkt_tickets')->where([['is_closed', '=', NULL], ['assign_to', '=', $userId], ['company_id', session('companyId')]])->count();
        $openTickets = DB::table('tkt_tickets')->where([['is_closed', '=', NULL], ['company_id', session('companyId')]])->count();
        $newTickets = DB::table('tkt_tickets')->where([['status', '=', 'New'], ['company_id', session('companyId')]])->count();
        $closedTickets = DB::table('tkt_tickets')->where([['is_closed', '=', 'on'], ['company_id', session('companyId')], ['status', 'Closed']])->count();
        $statuses = DB::table('sys_options')->where('type', '=', 'ticket_status')->get();
        $categories = DB::table('sys_options')->where('type', '=', 'ticket_category')->get();
        $priorities = DB::table('sys_options')->where('type', '=', 'ticket_priority')->get();
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();

        $tickets = DB::table('tkt_tickets')->where($where)->where('company_id', session('companyId'))->orderBy('number', 'asc')->get();

        // dd($tickets);


        // dd($type);

        return view('tickets.list', compact('tickets', 'myTickets', 'openTickets', 'newTickets', 'closedTickets', 'statuses', 'categories', 'priorities', 'departments', 'type'));
    }

    public function ticket($id = null)
    {

        $previousUrl = url()->previous();
        if ($previousUrl != url('Tmg/Ticket')) {
            session()->forget(['priority', 'department', 'status', 'related_to', 'category', 'related_to_id']);
        }
        $backURL = redirect()->back()->getTargetUrl();
        $statuses = DB::table('sys_options')->where('type', '=', 'ticket_status')->get();
        $categories = DB::table('sys_options')->where('type', '=', 'ticket_category')->get();
        $priorities = DB::table('sys_options')->where('type', '=', 'ticket_priority')->get();
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        $ticket = null;
        $sessionpriority =  session('priority');
        $sessiondepartment =  session('department');
        $sessionstatus =  session('status');
        $sessionrelated_to =  session('related_to');
        $sessioncategory =  session('category');
        $sessionrelated_to_id =  session('related_to_id');

        $customer = null;
        $projects = null;
        $contact = null;
        $customer2 = null;
        if ($sessionrelated_to_id) {
            $customer = DB::table('crm_customers')->where('id', $sessionrelated_to_id)->first();
            $projects = DB::table('prj_projects')->where('id', $sessionrelated_to_id)->first();
            $contact = DB::table('crm_contacts')->where('id', $sessionrelated_to_id)->first();
            $customer2 = DB::table('crm_customers')->where('coa_id', $sessionrelated_to_id)->first();
        }

        // dd($contact);
        if ($id) {

            $previousUrl = url()->previous();
            if ($previousUrl != url('Tmg/Ticket')) {
                session()->forget(['priority', 'department', 'status', 'related_to', 'category', 'related_to_id']);
            }
            $project = DB::table('prj_projects')->where('id', '=', $id)->first();
            if ($project) {


                $attachmentRecord = dbLib::getAttachment($id);
                return view('tickets.ticket', compact('id', 'statuses', 'categories', 'priorities', 'departments', 'attachmentRecord', 'backURL', 'project'));
            }
            $ticket = DB::table('tkt_tickets')
                ->leftjoin('crm_customers', 'crm_customers.coa_id', 'tkt_tickets.related_to_id')
                ->leftjoin('crm_customers as customer', 'customer.id', 'tkt_tickets.related_to_id')
                ->leftjoin('prj_projects', 'prj_projects.id', 'tkt_tickets.related_to_id')
                ->leftjoin('crm_contacts', 'crm_contacts.id', 'tkt_tickets.related_to_id')
                ->select('tkt_tickets.*', 'crm_customers.category as customer_name', 'prj_projects.name as project_name', 'crm_contacts.first_name as contact_firstname', 'crm_contacts.last_name as contact_lastname', 'customer.name as customer_name')
                ->where('tkt_tickets.id', '=', $id)->first();



            // dd($ticket);
            $assignedToName = DB::table('users')->select(DB::raw('concat(firstName, " ", lastName) as name'))->where('id', '=', $ticket->assign_to)->first();

            $attachmentRecord = dbLib::getAttachment($id);
            return view('tickets.ticket', compact('id', 'ticket', 'statuses', 'categories', 'priorities', 'departments', 'assignedToName', 'attachmentRecord', 'backURL'));
        }
        return view('tickets.ticket', compact('id', 'statuses', 'categories', 'priorities', 'departments', 'backURL', 'ticket', 'sessionpriority', 'sessiondepartment', 'sessionstatus', 'sessionrelated_to', 'sessioncategory', 'sessionrelated_to_id', 'customer', 'projects', 'contact', 'customer2'));
    }




    public function ticketView($id)
    {

        $totalTimeInSeconds = DB::table('tkt_tickets_history')
            ->where('tkt_id', '=', $id)
            ->select(DB::raw('SUM(TIME_TO_SEC(total_time)) as total_seconds'))
            ->value('total_seconds');

            if($totalTimeInSeconds === null){
                $totalTime = '00:00:00';
            }else{
                $totalTime = gmdate('H:i:s', $totalTimeInSeconds);
            }


        // dd($totalTime);

        // dd($totalTime);



        $statuses = DB::table('sys_options')->where('type', '=', 'ticket_status')->get();
        $ticket = DB::table('tkt_tickets')->where('id', '=', $id)->first();
        $selectedValue = DB::table('tkt_tickets')
            ->where('id', $id)
            ->value('status');
        $ticketsHistory = DB::table('tkt_tickets_history')->where('tkt_id', '=', $id)->orderBy('created_at', 'desc')->get();

        $attachmentRecords = [];
        foreach ($ticketsHistory as $historyEntry) {
            $sourceId = $historyEntry->id;
            $attachments = DB::table('sys_attachments')->where('source_id', '=', $sourceId)->get();
            $attachmentRecords[$sourceId] = $attachments;
        }

        // dd($attachmentRecords);
        return view('tickets.ticketView', compact('ticket', 'statuses', 'ticketsHistory', 'selectedValue', 'attachmentRecords','totalTime'));
    }

    public function updateTicket(request $request)
    {


        $data = array(
            'related_to' => $request['related_to_type'],
            'related_to_id' => $request['related_ID'],
            'email' => $request['email'],
            'category' => $request['category'],
            'subject' => $request['subject'],
            'body' => $request['body'],
            'department' => $request['department'],
            'status' => $request['status'],
            'priority' => $request['priority'],
            'assign_to' => $request['assign_ID'],
            'source' => 'webapp',
            'is_closed' => $request['closed'],
            'is_global' => $request['is_global'],
            'company_id' => session('companyId'),
            'created_at' => Carbon::now(),
            'updated_at' => $request[''],
            'created_by' => Auth::user()->id,
            'updated_by' => $request[''],
            'is_billable' => $request->is_billable,

        );

        // dd($request->closed);
        if ($request->status == 'Closed') {
            $data['is_closed'] = 'on';
            $data['status'] = $request['status'];
        } elseif ($request->closed == 'on') {
            $data['status'] = 'Closed';
            $data['is_closed'] = $request['closed'];
        }

        // If the status is not 'Closed', set is_closed to null
        // if ($request->status !== 'Closed') {
        //     $data['is_closed'] = null;
        //     $data['status'] = $request['status'];
        // } elseif ($request->closed == null) {
        //     $data['is_closed'] = null;
        //     $data['status'] = 'New';
        // }



        // dd($data['status']);
        $MoreTickets = $request->more_ticket;
        if ($MoreTickets) {
            session(['priority' => $request->priority]);
            session(['department' => $request->department]);
            session(['status' => $request->status]);
            session(['category' => $request->category]);
            session(['related_to' => $request->related_to_type]);
            session(['related_to_id' => $request->related_ID]);
        }
        $id = $request['id'];
        if ($request['id']) {
            DB::table('tkt_tickets')->where('id', '=', $id)->update($data);
        } else {
            $id = str::uuid()->toString();
            $data['id'] = $id;
            DB::table('tkt_tickets')->insert($data);
        }

        if ($request->hasFile('file')) {
            dbLib::uploadDocument($id, $request->file('file'));
        }
        //   dd($request->file);


        $sendcustomer = $request->send_customer;
        $user = DB::table('users')->where('id', $data['assign_to'])->first();
        // dd($user);
        $ticket = DB::table('tkt_tickets')->where('id', '=', $id)->first();
        // dd($ticket);
        $assignedEmail = $request['email'];
        if ($user && $sendcustomer == 1) {
            Mail::to($user->email)->send(new TicketAssigned($ticket));
            Mail::to($assignedEmail)->send(new TicketCreated($ticket));
        } elseif ($user) {
            Mail::to($user->email)->send(new TicketAssigned($ticket));
        } elseif ($sendcustomer == 1) {
            Mail::to($assignedEmail)->send(new TicketCreated($ticket));
        }
        if ($MoreTickets) {
            return redirect()->back()->with('message', 'Created Sucessfully');
        } else {
            session()->forget(['priority', 'department', 'status', 'related_to', 'category', 'related_to_id']);
            if ($request->backURL == url('authentication/authenticate')) {
                return redirect('Tmg/Listing')->with('message', 'Created Sucessfully');
            } else {
                return redirect($request->backURL)->with('message', 'Created Sucessfully');
            }
        }
    }



    public function updateTicketHistory(request $request)
    {

        $IDS = $request->id;
        $id = str::uuid()->toString();

        $ticket = [
            'assign_to' => $request['assign_ID'],
            'is_closed' => $request['closed'],
            'updated_by' => $request[''],
            'status' => $request['status'],



        ];


        DB::table('tkt_tickets')->where('id', '=', $IDS)->update($ticket);
        $ticket =  DB::table('tkt_tickets')->where('id', '=', $IDS)->first();
        // dd($ticketCreater);

        // Add update in ticket History
        $ticketUpdate = [
            'id' => $id,
            'tkt_id' => $request->id,
            'body' => $request['body'],
            'send_to_customer' => $request['send_mail'],
            'created_at' => Carbon::now(),
            'created_by' => Auth::user()->id,
            'from_time' => $request->input('from_time'),
            'to_time' => $request->input('to_time'),
            'total_time' => $request->input('total_time'),
        ];



        $sendEmail = $request->assign_ID;
        if ($sendEmail) {
            $userForEmail = DB::table('users')->where('id', $sendEmail)->first();
            $userEmail = $userForEmail->email;
            if ($ticketUpdate['send_to_customer'] == 1) {
                Mail::to($userEmail)->send(new TicketAssigned($ticket));
            }
        }

        if ($request->hasFile('file')) {
            dbLib::uploadDocument($id, $request->file('file'));
        }
        DB::table('tkt_tickets_history')->insert($ticketUpdate);


        return redirect()->back()->with('insert_message', 'Updated successfully');
    }


    public function deleteTicket($id)
    {

        DB::table('tkt_tickets')->where('id', $id)->delete();
        DB::table('tkt_tickets_history')->where('tkt_id', $id)->delete();
        return redirect()->back()->with('error', 'Deleted successfully');
    }


    public function BulkTicket(Request $request)
    {
        $ids = $request->ids;

        if (!empty($ids)) {
            if ($request->status == 'Delete') {
                DB::table('tkt_tickets')->whereIn('id', $ids)->delete();
                return redirect()->back()->with('error', 'Tickets deleted successfully');
            } elseif ($request->status == 'MarkAsSpam') {
                $data = DB::table('tkt_tickets')->whereIn('id', $ids)->get();
                $uniqueEmail = $data->pluck('email')->unique();
                $uniqueEmails = $uniqueEmail->toArray();
                foreach ($uniqueEmails as $mail) {
                    DB::table('tkt_mail_spam')->insert(['email' => $mail]);
                }
                return redirect()->back()->with('message', 'Emails Spam successfully');
            }
            DB::table('tkt_tickets')->whereIn('id', $ids)->update(['status' => $request->status]);
            return redirect()->back()->with('message', 'Tickets updated successfully');
        } else {
            return redirect()->back();
        }
    }


    public function TicketSearch(Request $request)
    {

        $userId = Auth::user()->id;
        if ($request->type == 'New') {
            $where = array(['tkt_tickets.status', '=', 'New']);
        } elseif ($request->type == 'open') {
            $where = array(['tkt_tickets.is_closed', '=', NULL]);
        } elseif ($request->type == 'me') {
            $where = array(['tkt_tickets.is_closed', '=', NULL], ['tkt_tickets.assign_to', '=', $userId]);
        } elseif ($request->type == 'closed') {
            $where = array(['tkt_tickets.status', '=', 'Closed'], ['tkt_tickets.is_closed', '=', 'on']);
        } else {
            $where = array(['tkt_tickets.is_closed', '=', NULL], ['tkt_tickets.assign_to', '=', $userId]);
        }
        $ticketsQuery = DB::table('tkt_tickets')->where('tkt_tickets.company_id', session('companyId'));
        // return $ticketsQuery;

        if ($request->anyFilled(['ticket_id', 'email', 'priority', 'status', 'category', 'department', 'from_date', 'to_date', 'assign_ID', 'related_ID', 'is_billable'])) {


            if ($request->ticket_id) {
                $ticketsQuery->where('tkt_tickets.number', 'LIKE',  $request->ticket_id);
            }

            if ($request->email) {
                $ticketsQuery->where('tkt_tickets.email', 'LIKE',  $request->email);
            }

            if ($request->priority) {
                $ticketsQuery->where('tkt_tickets.priority', 'LIKE',  $request->priority);
            }

            if ($request->status) {
                $ticketsQuery->where('tkt_tickets.status', 'LIKE',  $request->status);
            }

            if ($request->category) {
                $ticketsQuery->where('tkt_tickets.category', 'LIKE',  $request->category);
            }

            if ($request->department) {
                $ticketsQuery->where('tkt_tickets.department', 'LIKE',  $request->department);
            }

            if ($request->from_date && $request->to_date) {
                $fromDate = $request->from_date;
                $toDate = $request->to_date;
                $ticketsQuery->whereBetween('tkt_tickets.created_at', [$fromDate, $toDate]);
            }

            if ($request->assign_ID) {
                $ticketsQuery->where('tkt_tickets.assign_to', 'LIKE',  $request->assign_ID);
            }


            if ($request->related_ID) {
                $ticketsQuery->where('tkt_tickets.related_to_id', 'LIKE',  $request->related_ID);
            }

            if ($request->is_billable) {
                $ticketsQuery->where('tkt_tickets.is_billable', 'LIKE',  $request->is_billable);
            }

            $tickets = $ticketsQuery->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->where($where)->orderBy('number', 'asc')->get();
        } else {

            $tickets = DB::table('tkt_tickets')->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->where('company_id', session('companyId'))->where($where)->orderBy('number', 'asc')->get();
        }

        return $tickets;
    }


    public function TicketSearchProject(Request $request)
    {

        $ticketsQuery = DB::table('tkt_tickets')->where('tkt_tickets.company_id', session('companyId'))->where('tkt_tickets.related_to_id', $request->prj_id);
        // return $ticketsQuery;

        if ($request->anyFilled(['ticket_id', 'email', 'priority', 'status', 'category', 'department', 'from_date', 'to_date', 'assign_ID', 'related_ID', 'is_billable'])) {


            // dd('inner');
            if ($request->ticket_id) {
                $ticketsQuery->where('tkt_tickets.number', 'LIKE',  $request->ticket_id);
            }

            if ($request->email) {
                $ticketsQuery->where('tkt_tickets.email', 'LIKE',  $request->email);
            }

            if ($request->priority) {
                $ticketsQuery->where('tkt_tickets.priority', 'LIKE',  $request->priority);
            }

            if ($request->status) {
                $ticketsQuery->where('tkt_tickets.status', 'LIKE',  $request->status);
            }

            if ($request->category) {
                $ticketsQuery->where('tkt_tickets.category', 'LIKE',  $request->category);
            }

            if ($request->department) {
                $ticketsQuery->where('tkt_tickets.department', 'LIKE',  $request->department);
            }

            if ($request->from_date && $request->to_date) {
                $fromDate = $request->from_date;
                $toDate = $request->to_date;
                $ticketsQuery->whereBetween('tkt_tickets.created_at', [$fromDate, $toDate]);
            }

            if ($request->assign_ID) {
                $ticketsQuery->where('tkt_tickets.assign_to', 'LIKE',  $request->assign_ID);
            }


            if ($request->related_ID) {
                $ticketsQuery->where('tkt_tickets.related_to_id', 'LIKE',  $request->related_ID);
            }

            if ($request->is_billable) {
                $ticketsQuery->where('tkt_tickets.is_billable', 'LIKE',  $request->is_billable);
            }

            $tickets = $ticketsQuery->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->orderBy('number', 'asc')->get();
        } else {
            // dd('outer');

            $tickets = DB::table('tkt_tickets')
                ->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->where('company_id', session('companyId'))->where('related_to_id', $request->prj_id)->orderBy('number', 'asc')->get();
        }

        return $tickets;
    }


    public function reports(){

        $statuses = DB::table('sys_options')->where('type', '=', 'ticket_status')->get();
        $categories = DB::table('sys_options')->where('type', '=', 'ticket_category')->get();
        $priorities = DB::table('sys_options')->where('type', '=', 'ticket_priority')->get();
        $departments = DB::table('sys_departments')->where('company_id', session('companyId'))->get();
        $tickets = DB::table('tkt_tickets')->where('company_id', session('companyId'))->orderBy('number', 'asc')->get();



        return view('tickets.reports',compact('statuses','categories','priorities','departments','tickets'));
    }


    public function TicketSearchReports(Request $request){


        $ticketsQuery = DB::table('tkt_tickets')->where('tkt_tickets.company_id', session('companyId'))->where('tkt_tickets.related_to_id', $request->prj_id);
        // return $ticketsQuery;

        if ($request->anyFilled(['ticket_id', 'email', 'priority', 'status', 'category', 'department', 'from_date', 'to_date', 'assign_ID', 'related_ID', 'is_billable'])) {


            // dd('inner');
            if ($request->ticket_id) {
                $ticketsQuery->where('tkt_tickets.number', 'LIKE',  $request->ticket_id);
            }

            if ($request->email) {
                $ticketsQuery->where('tkt_tickets.email', 'LIKE',  $request->email);
            }

            if ($request->priority) {
                $ticketsQuery->where('tkt_tickets.priority', 'LIKE',  $request->priority);
            }

            if ($request->status) {
                $ticketsQuery->where('tkt_tickets.status', 'LIKE',  $request->status);
            }

            if ($request->category) {
                $ticketsQuery->where('tkt_tickets.category', 'LIKE',  $request->category);
            }

            if ($request->department) {
                $ticketsQuery->where('tkt_tickets.department', 'LIKE',  $request->department);
            }

            if ($request->from_date && $request->to_date) {
                $fromDate = $request->from_date;
                $toDate = $request->to_date;
                $ticketsQuery->whereBetween('tkt_tickets.created_at', [$fromDate, $toDate]);
            }

            if ($request->assign_ID) {
                $ticketsQuery->where('tkt_tickets.assign_to', 'LIKE',  $request->assign_ID);
            }


            if ($request->related_ID) {
                $ticketsQuery->where('tkt_tickets.related_to_id', 'LIKE',  $request->related_ID);
            }

            if ($request->is_billable) {
                $ticketsQuery->where('tkt_tickets.is_billable', 'LIKE',  $request->is_billable);
            }

            $tickets = $ticketsQuery->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->orderBy('number', 'asc')->get();
        } else {
            // dd('outer');

            $tickets = DB::table('tkt_tickets')
                ->leftJoin('users', 'users.id', 'tkt_tickets.assign_to')
                ->select('tkt_tickets.*', 'users.firstName as assigned_user_first_name', 'users.lastName as assigned_user_last_name')->where('company_id', session('companyId'))->where('related_to_id', $request->prj_id)->orderBy('number', 'asc')->get();
        }

        return $tickets;

    }
}
