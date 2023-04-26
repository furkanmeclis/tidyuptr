<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTeacher extends Model
{
    use HasFactory;
    protected $table = 'student_teacher';
    protected $fillable = [
        'student_id',
        'teacher_id',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class)->first();
    }
    public static function getTableName()
    {
        return (new static())->table;
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
