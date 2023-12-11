<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStudents extends Model
{
    use HasFactory;
    protected $table = 'class_students';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->first();
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }
}
