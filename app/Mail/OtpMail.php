<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->from('noreply@absen_in.com', 'Absen-In')
                    ->subject('Your OTP Code')
                    ->html("<h1>Your OTP Code</h1><p>Your OTP is: <strong>{$this->otp}</strong>. It is valid for 10 minutes.</p>");
    }
}
