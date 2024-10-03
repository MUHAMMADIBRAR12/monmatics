<?php

namespace App\Jobs;

use App\Mail\TicketCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client;

class ProcessIncomingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $credentials = DB::table('smtp_pop_setting')
                ->where('status', 'activate')
                ->where('company_id', session('companyId'))
                ->get();

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
                    $tickets = [];

                    foreach ($messages as $message) {
                        $from = $message->getFrom();
                        $subject = $message->getSubject();

                        $id = Str::uuid()->toString();
                        $senderEmail = $from[0]->mail;

                        $data = [
                            'id' => $id,
                            'email' => $senderEmail,
                            'subject' => $subject[0],
                            'body' => $message->getHTMLBody(),
                            'source' => 'Email',
                            'status' => 'New',
                            'company_id' => session('companyId'),
                            'created_at' => now(),
                        ];

                        $ticketId = DB::table('tkt_tickets')->insertGetId($data);
                        $data['number'] = $ticketId;
                        $tickets[] = $data;

                        if ($credential->message == 'delete') {
                            $message->delete();
                        } else {
                            $message->setFlag(['Seen']);
                        }
                    }

                    foreach ($tickets as $ticket) {
                        $mailConfigurations = DB::table('mail_configration_setting')
                            ->where('company_id', session('companyId'))
                            ->where('status', 'activate')
                            ->where('department', $credential->department)
                            ->get();

                        foreach ($mailConfigurations as $mailConfig) {
                            $mailerConfig = [
                                'transport' => $mailConfig->mail_transport,
                                'host' => $mailConfig->mail_host,
                                'port' => $mailConfig->mail_port,
                                'from' => [
                                    'address' => $mailConfig->mail_username,
                                    'name' => $mailConfig->department,
                                ],
                                'encryption' => $mailConfig->mail_encryption,
                                'username' => $mailConfig->mail_username,
                                'password' => $mailConfig->mail_password,
                            ];

                            config(['mail.mailers.smtp' => $mailerConfig]);
                            $assignedEmail = $ticket['email'];
                            Mail::to($assignedEmail)->send(new TicketCreated($ticket));
                        }
                    }
                }

                $account->disconnect();
            }
            Log::info("Emails sent successfully!");
        } catch (\Exception $e) {
            // Log or handle the exception
            Log::error("Error fetching or sending emails: " . $e->getMessage());
        }
    }
}
