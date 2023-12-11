<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    use HasFactory;
    protected $table = 'class_attendances';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->first();
    }

    public function hour()
    {
        return $this->belongsTo(DayHours::class, 'day_hour_id', 'id')->first();
    }

    public function lesson()
    {
        return $this->belongsTo(DayHours::class, 'day_hour_id', 'id')->first()->lesson();
    }
    public function teacher()
    {
        return $this->belongsTo(DayHours::class, 'day_hour_id', 'id')->first()->teacher();
    }
}
