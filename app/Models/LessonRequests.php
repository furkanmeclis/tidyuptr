<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonRequests extends Model
{
    use HasFactory;
    protected $table = 'lesson_requests';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->first();
    }
}
