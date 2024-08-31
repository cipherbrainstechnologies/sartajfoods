<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendRewards extends Mailable
{
    use Queueable, SerializesModels;
    protected $credit;
    protected $userId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId ,$credit)
    {
        $this->userId = $userId;
        $this->credit = $credit; 
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   $userid= $this->userid;
        $credit  = $this->credit;
        $subject = 'Rewards Point: ' .  $this->credit;

        return $this->view('email-templates.send-rewards',compact('credit','userid'))
            ->subject($subject);
    }
}
