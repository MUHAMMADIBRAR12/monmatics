<?php


namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Mail\Mailcheck;
use App\Mail\TicketCreated;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Facades\Client;
// use Webklex\PHPIMAP\Client;


class EmailController extends Controller
{

    public function fetchEmails()
    {

        $credentials = DB::table('smtp_pop_setting')
            ->where('status', 'activate')
            ->where('company_id', session('companyId'))
            ->get();



        try {
            foreach ($credentials as $credential) {
                $accountInfo = [
                    'host' => env('IMAP_HOST', $credential->imap_host),
                    'port' => env('IMAP_PORT', $credential->imap_port),
                    'protocol' => env('IMAP_PROTOCOL', $credential->imap_protocol),
                    'encryption' => env('IMAP_ENCRYPTION', $credential->imap_encryption),
                    'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                    'username' => env('IMAP_USERNAME', $credential->imap_username),
                    'password' => env('IMAP_PASSWORD', $credential->imap_password),
                    'authentication' => env('IMAP_AUTHENTICATION', null),
                    'proxy' => [
                        'socket' => null,
                        'request_fulluri' => false,
                        'username' => null,
                        'password' => null,
                    ],
                    'timeout' => 30,
                    'extensions' => [],
                ];
                $account = Client::make($accountInfo);
                $account->connect();
                $folders = $account->getFolders();

                foreach ($folders as $folder) {
                    $messages = $folder->messages()->unseen()->all()->get();
                    $messageCount = count($messages);

                    if ($messageCount == 0) {
                        return redirect()->back()->with('error', 'Unreed mails not found');
                    }

                    $tickets = [];

                    foreach ($messages as $message) {
                        $from = $message->getFrom();
                        $subject = $message->getSubject();

                        $rawContent = $subject;
                        $nextWord = $rawContent[0];
                        $paragraph = preg_match_all('!\d+!', $nextWord, $matches);

                        if ($paragraph == 1) {
                            $integerValue = (int)$matches[0][0];
                            $id = Str::uuid()->toString();
                            $senderemail = $from[0]->mail;
                            $record = DB::table('tkt_tickets')->where('number', $integerValue)->first();
                            $MailBody = preg_replace('/<!--\[if[^>]>.?<\/xml>\]-->/s', '', $message->getHTMLBody());
                            if ($record) {
                                $ids = str::uuid()->toString();
                                $data = [
                                    'id' => $ids,
                                    'tkt_id' => $record->id,
                                    'body' => $MailBody,
                                    'created_at' => carbon::now(),
                                ];


                                DB::table('tkt_tickets_history')->insert($data);
                                $data['subject'] = $record->subject ?? '';
                                $data['email'] = $record->email ?? '';
                            } else {
                                $data = [
                                    'id' => $id,
                                    'email' => $senderemail,
                                    'subject' => $subject[0],
                                    'body' => $MailBody,
                                    'source' => 'Email',
                                    'status' => 'New',
                                    'company_id' => session('companyId'),
                                    'created_at' => Carbon::now(),
                                ];
                                $oldnewTicket= DB::table('tkt_tickets')->insertGetId($data);
                            }




                            if ($credential->message == 'delete') {
                                $message->delete();
                            } else {
                                $message->setFlag(['Seen']);
                            }
                        } else {
                            $id = Str::uuid()->toString();
                            $senderemail = $from[0]->mail;
                            $MailBody = preg_replace('/<!--\[if[^>]>.?<\/xml>\]-->/s', '', $message->getHTMLBody());
                            $data = [
                                'id' => $id,
                                'email' => $senderemail,
                                'subject' => $subject[0],
                                'body' => $MailBody,
                                'source' => 'Email',
                                'status' => 'New',
                                'company_id' => session('companyId'),
                                'created_at' => Carbon::now(),
                            ];



                            $ticketnewId = DB::table('tkt_tickets')->insertGetId($data);
                        }
                        $data['number'] = $record->number ?? $ticketnewId ?? $oldnewTicket;

                        $tickets[] = $data;
                        if ($credential->message == 'delete') {
                            $message->delete();
                        } else {
                            $message->setFlag(['Seen']);
                        }
                    }

                    foreach ($tickets as $ticket) {
                        // dd($ticket);
                        $Mails = DB::table('mail_configration_setting')->where('company_id', session('companyId'))->where('status', 'activate')->where('department', $credential->department)->get();
                        foreach ($Mails as $Mail) :
                            $mailerConfig = [

                                'transport'     => $Mail->mail_transport,
                                'host'       => $Mail->mail_host,
                                'port'       => $Mail->mail_port,
                                'from'       => ['address' => $Mail->mail_username, 'name' => $Mail->from_name ?? 'Monmatics'],
                                'encryption' => $Mail->mail_encryption,
                                'username'   => $Mail->mail_username,
                                'password'   => $Mail->mail_password,

                            ];

                            config(['mail.mailers.smtp' => $mailerConfig]);
                            $assignedEmail = $ticket['email'] ;
                            Mail::to($assignedEmail)->send(new TicketCreated($ticket));
                            return redirect()->back()->with('message', 'Ticket Fetched Successfully');
                        endforeach;
                    }
                }

                $account->disconnect();
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', $e->getMessage(), $e->getLine());
        }
    }


    public function imapTest($id)
    {

        $credential = DB::table('smtp_pop_setting')
            ->where('id', $id)
            ->first();

        try {
            $accountInfo = [
                'host' => env('IMAP_HOST', $credential->imap_host),
                'port' => env('IMAP_PORT', $credential->imap_port),
                'protocol' => env('IMAP_PROTOCOL', $credential->imap_protocol),
                'encryption' => env('IMAP_ENCRYPTION', $credential->imap_encryption),
                'validate_cert' => env('IMAP_VALIDATE_CERT', true),
                'username' => env('IMAP_USERNAME', $credential->imap_username),
                'password' => env('IMAP_PASSWORD', $credential->imap_password),
                'authentication' => env('IMAP_AUTHENTICATION', null),
                'proxy' => [
                    'socket' => null,
                    'request_fulluri' => false,
                    'username' => null,
                    'password' => null,
                ],
                'timeout' => 30,
                'extensions' => [],
            ];

            $account = Client::make($accountInfo);
            $account->connect();



            $account->disconnect();

            return redirect()->back()->with('message', 'IMAP connection is successful.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function mailTest($id)
    {
        try {
            $Mail = DB::table('mail_configration_setting')->where('id', $id)->first();

            $mailerConfig = [
                'transport'     => $Mail->mail_transport,
                'host'       => $Mail->mail_host,
                'port'       => $Mail->mail_port,
                'from'       => ['address' => $Mail->mail_username, 'name' => 'Mail Test'],
                'encryption' => $Mail->mail_encryption,
                'username'   => $Mail->mail_username,
                'password'   => $Mail->mail_password,
            ];

            config(['mail.mailers.smtp' => $mailerConfig]);
            $assignedEmail = $Mail->mail_username;
            Mail::to($assignedEmail)->send(new Mailcheck());
            return redirect()->back()->with('message', 'Connection Successfull. Please Check Your Mail Application.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
