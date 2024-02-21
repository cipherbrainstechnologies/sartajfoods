<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\User;

class ResetPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $token;
    public $AdminMail;
    public $businessName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token,$AdminMail,$businessName)
    {
        //
        $this->user = $user;
        $this->token = $token;
        $this->businessName = $businessName;
        $this->AdminMail = $AdminMail;
    }

    public function build()
    {
        return $this->from($this->AdminMail, $this->businessName)
                ->subject('Password Reset Sartaj Foods')
                ->view('email-templates.opening-reset-mail');
    }
}
