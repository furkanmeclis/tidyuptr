<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;

class RemindAgenta extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($student,$teacher)
    {
        $this->student_name = $student->name;
        $this->student_email = $student->email;
        $this->teacher_name = $teacher->name;
        $this->teacher_email = $teacher->email;
    }
    public function build()
    {
        return $this->subject('Ajanda Hatırlatma Talebi')
            ->view('teacher.mentor-follow-up.mail')->with([
                'student_name' => $this->student_name,
                'student_email' => $this->student_email,
                'teacher_name' => $this->teacher_name,
                'teacher_email' => $this->teacher_email,
            ]);
    }
}
