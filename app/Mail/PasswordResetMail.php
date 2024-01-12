<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $token;
    public $username;
    public function __construct($token,$username)
    {
        $this->token = $token;
        $this->username = $username;
        // $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = $this->token;
        // $customer = $this->customer;
        // return $this->view('email-templates.customer-password-reset', ['token' => $token]);
        return $this->view('email-templates.forgot-password', ['token' => $token,'username' => $this->username]);
    }
}
