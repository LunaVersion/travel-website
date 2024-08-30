<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $resetLink;

    // public function __construct($resetLink)
    // {
    //     $this->resetLink = $resetLink;
    // }

    // public function build()
    // {
    //     return $this->subject('Reset Password')
    //         ->view('emails.reset_password')
    //         ->with(['resetLink' => $this->resetLink]);
    // }
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.forgot-password')
                    ->with([
                        'user' => $this->user,
                    ]);
    }
}
