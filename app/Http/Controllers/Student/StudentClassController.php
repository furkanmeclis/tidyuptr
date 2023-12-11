<?php

namespace App\Http\Controllers\Student;

use App\Models\Announcements;
use App\Models\ClassAnnouncements;
use App\Models\Classes;
use App\Models\ClassStudents;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentClassController extends \Illuminate\Routing\Controller
{
    public function schedule()
    {
        $class = Classes::find(ClassStudents::where('student_id',auth('student')->user()->id)->first()->class_id);
        $timeTable = TimeTable::where('class_id',$class->id)->first();
        return view('student.class.schedule')->with('timeTable',$timeTable)->with('class',$class);
    }

    public function announcements()
    {
        $class = Classes::find(ClassStudents::where('student_id',auth('student')->user()->id)->first()->class_id);
        $announcements = ClassAnnouncements::where('class_id',$class->id)->orderBy('created_at','desc')->get();
        return view('student.class.announcements')->with('announcements',$announcements)->with('class',$class);
    }

}
