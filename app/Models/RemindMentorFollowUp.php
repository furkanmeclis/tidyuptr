<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemindMentorFollowUp extends Model
{
    protected $table = "remind_mentor_follow_ups";
    use HasFactory;
    protected $fillable = [
        'student_id',
        'teacher_id',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->first();
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->first();
    }

}
