<?php

namespace App\Http\Controllers\Student;

use App\Models\Exams;
use App\Models\Organization;
use App\Models\OrganizationTeacher;
use App\Models\Student;
use App\Models\StudentTeacher;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentStudentController extends Controller
{

    public function index()
    {
        $studentIds = StudentTeacher::where('teacher_id', auth()->guard('teacher')->user()->id)->get()->map(function ($student) {
            return $student->student_id;
        });
        return view('teacher.student.all')->with('students', Student::whereIn('id', $studentIds)->get());
    }
    public function getOrganization(Request $request)
    {
        return response()->json(User::where('email', $request->input('email'))->first());
    }

    public function create()
    {
        return view('organizationAdmin.student.create');
    }
    public function getStudents(){
        $students = Student::where('organization_id',Auth::guard('organization')->user()->id)->get()->map(function ($item,$key){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'selected' => false,
            ];
        })->toArray();
        $students[0]['selected'] = true;
        return response()->json([
            "data" => $students,
        ]);
    }
    public function exams($id)
    {
        return view('teacher.student.exam')
            ->with('exams', Exams::where('student_id', $id)->get())
            ->with('student', Student::where('id',$id)->first());
    }

    public function show($id)
    {
        return view('teacher.student.show')->with('student', Student::find($id));
    }

    public function destroy($id)
    {
        $record = StudentTeacher::where('student_id',$id)->where('teacher_id',Auth::guard('teacher')->user()->id)->first();
        if (!$record) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $record->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
