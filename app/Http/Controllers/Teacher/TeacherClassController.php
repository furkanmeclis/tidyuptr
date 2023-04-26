<?php

namespace App\Http\Controllers\Teacher;

use App\Models\ClassContent;

class TeacherClassController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        return view('teacher.class.all', [
            'classes' => ClassContent::where('teacher_id', auth()->guard('teacher')->user()->id)->orderBy('updated_at', 'desc')->get(),
        ]);
    }
}
