<?php

namespace App\Http\Controllers\Student;

use Illuminate\Routing\Controller;

class StudentPreferenceRobotController extends Controller
{
    public function index()
    {
        return view('student.prefenceRobot');
    }
}
