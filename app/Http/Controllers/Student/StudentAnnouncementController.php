<?php

namespace App\Http\Controllers\Student;

use App\Models\Announcements;
use App\Models\StudentTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentAnnouncementController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $teacher_id = StudentTeacher::where('student_id',auth('student')->user()->id)->first()->teacher_id;
        return view('student.announcement.all', [
            'contents' => Announcements::where('teacher_id', $teacher_id)->orderBy('updated_at', 'asc')->get(),
        ]);
    }
}
