<?php

namespace App\Http\Controllers\Teacher;

use App\Models\BatchExamLessons;
use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherExamController extends \Illuminate\Routing\Controller
{

    public function index()
    {
        return view('organizationAdmin.exam.all', [
            'exams' => BatchExams::where('organization_id', auth()->guard('organization')->user()->id)->get(),
        ]);
    }


    public function show($id)
    {
        return response()->json([
            "html" => view('organizationAdmin.exam.showExam', [
                'exam' => Exams::where('id', $id)->first(),
            ])->render(),
        ]);
    }
}
