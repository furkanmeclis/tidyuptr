<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;



    public function build()
    {
        return $this->subject('Şifre Sıfırlama İsteği')
            ->markdown('systemAdmin.auth.emails.password');
    }
}
