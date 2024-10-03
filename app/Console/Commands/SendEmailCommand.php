<?php

namespace App\Console\Commands;

use App\Mail\TicketCreated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Webklex\IMAP\Facades\Client;

class SendEmailCommand extends Command
{
    protected $signature = 'send:email';
    protected $description = 'mail fetched successfully';

    public function handle()
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
                // dd($account->connect());

                $folders = $account->getFolders();

                foreach ($folders as $folder) {
                    $messages = $folder->messages()->unseen()->all()->get();
                    $tickets = [];

                    foreach ($messages as $message) {
                        $from = $message->getFrom();
                        $subject = $message->getSubject();

                        $rawContent = $subject;
                        $nextWord = $rawContent[0];
                        preg_match_all('!\d+!', $nextWord, $matches);

                        $integerValue = (int)$matches[0][0];
                        $id = Str::uuid()->toString();
                        $senderemail = $from[0]->mail;
                        if ($integerValue) {
                            $data = [
                                'id' => $id,
                                'email' => $senderemail,
                                'subject' => $subject[0],
                                'body' => $message->getHTMLBody(),
                                'source' => 'Email',
                                'status' => 'New',
                                'company_id' => session('companyId'),
                                'updated_at' => now(),
                            ];

                            $record = DB::table('tkt_tickets')->where('number', $integerValue)->first();


                            if ($record) {
                                DB::table('tkt_tickets')->where('number', $integerValue)->update($data);
                                $ticketId = $integerValue;
                            } else {
                                $ticketId = DB::table('tkt_tickets')->insertGetId($data);
                            }
                        }
                        $data['number'] = $ticketId;
                        $tickets[] = $data;
                        if ($credential->message == 'delete') {
                            $message->delete();
                        } else {
                            $message->setFlag(['Seen']);
                        }
                    }

                    foreach ($tickets as $ticket) {
                        $Mails = DB::table('mail_configration_setting')->where('company_id', session('companyId'))->where('status', 'activate')->where('department', $credential->department)->get();
                        foreach ($Mails as $Mail) :
                            $mailerConfig = [

                                'transport'     => $Mail->mail_transport,
                                'host'       => $Mail->mail_host,
                                'port'       => $Mail->mail_port,
                                'from'       => ['address' => $Mail->mail_username, 'name' => $Mail->department],
                                'encryption' => $Mail->mail_encryption,
                                'username'   => $Mail->mail_username,
                                'password'   => $Mail->mail_password,

                            ];

                            config(['mail.mailers.smtp' => $mailerConfig]);
                            $assignedEmail = $ticket['email'];
                            Mail::to($assignedEmail)->send(new TicketCreated($ticket));
                            return redirect()->back()->with('message', 'Ticket Fetched Successfully');
                        endforeach;
                    }
                }

                $account->disconnect();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
