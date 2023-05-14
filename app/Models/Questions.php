<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = "questions";
    use HasFactory;
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->first();
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->first();
    }
    public function answers()
    {
        return $this->hasMany(Answers::class, 'question_id', 'id')->get();
    }
}
