<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayHours extends Model
{
    use HasFactory;
    protected $table = 'day_hours';

    public function teacher()
    {
        if($this->teacher_id){
            return Teacher::find($this->teacher_id);
        }else{
            $std = new \stdClass();
            $std->name = "Tanımsız Öğretmen";
            return $std;
        }
    }
    public function lesson()
    {
        if($this->is_live){
            $std = new \stdClass();
            $std->name = "Canlı Ders";
        }else{
            $std = Lesson::find($this->lesson_id);
        }
        return $std;
    }

    public function attendanceUrl($timetable_id)
    {
        return route('organizationAdmin.class.showAttendance', ['class' => $timetable_id , 'hour' => $this->id]);
    }
}
