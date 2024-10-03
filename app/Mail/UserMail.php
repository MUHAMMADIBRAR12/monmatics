<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
  
    public $froms;
    public $user_subject;
    public $description;
    public function __construct($from,$user_subject,$description)
    {
      
        $this->froms=$from;
       
        $this->user_subject=$user_subject;
        $this->description =$description;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      
        $message=$this->from($this->froms)
                    ->subject($this->user_subject)
                    ->view('mail.user_mail',['description'=>$this->description]);                
                    return $message;
       
    }
}