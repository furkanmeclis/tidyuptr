<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Organization;
use App\Models\OrganizationTeacher;
use App\Models\Student;
use App\Models\StudentTeacher;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeacherTeacherController extends Controller
{
    public function view($name,$r = []){
        return view('organizationAdmin.'.$name ,$r);
    }
    public $getTeachersHelper = false;
    public function index()
    {
        $getTeacherIds = OrganizationTeacher::where('organization_id',Auth::guard('organization')->user()->id)->pluck('teacher_id')->toArray();
        return $this->view('teacher.all')->with('teachers', Teacher::whereIn('id',$getTeacherIds)->get());
    }
    private function getTeachersHelper($item, $key) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'selected' => $this->getTeachersHelper == $item->id ? true : false,
        ];
    }
    public function getTeachers($id = false)
    {

        if ($id) {
            $t = StudentTeacher::where('student_id', $id)->first();
            if ($t) {
                $this->getTeachersHelper = $t->teacher_id;
            }
        }
        $responseArr = array();
        $getTeacherIds = OrganizationTeacher::where('organization_id',Auth::guard('organization')->user()->id)->pluck('teacher_id')->toArray();
        $teachers = Teacher::whereIn('id',$getTeacherIds)->get()->map(function ($item,$key){
            return $this->getTeachersHelper($item,$key);
        })->toArray();
        if($this->getTeachersHelper == false){
            $teachers[0]['selected'] = true;
        }
        return response()->json([
            "data" => $teachers,
        ]);
    }
    public function show(Teacher $teacher)
    {
        return $this->view('teacher.show')->with('teacher', $teacher);
    }
    public function showStudent($id)
    {
        return $this->view('teacher.showStudent')
            ->with('teacher', Teacher::find($id))
            ->with('students', StudentTeacher::where('teacher_id', $id)->get());
    }
    public function endRegistration(Request $request,$id)
    {
       $teacher = Teacher::find($id);
       if($teacher){
           $teacher_control = OrganizationTeacher::where('teacher_id',$id)->where('organization_id',Auth::guard('organization')->user()->id)->first();
              if($teacher_control) {
                 if( $teacher_control->delete()){
                     $student_ids = Student::where('organization_id',Auth::guard('organization')->user()->id)->pluck('id')->toArray();
                        if(StudentTeacher::whereIn('student_id',$student_ids)->where('teacher_id',$id)->delete()){
                            return response()->json([
                                "status" => true,
                                "message" => "Öğretmen Silindi",
                            ]);
                        }else{
                            return response()->json([
                                "status" => false,
                                "message" => "Öğretmen Silindi Fakat Öğrencilerinizle İlişkisi Silinemedi",
                            ]);
                        }
                 }else{
                        return response()->json([
                            "status" => false,
                            "message" => "Öğretmen Silinemedi",
                        ]);
                 }
              }else{
                  return response()->json([
                      "status" => false,
                      "message" => "Öğretmenle Kurum İlişkisi Bulunamadı",
                  ]);
              }
       }else{
           return response()->json([
               "status" => false,
                "message" => "Öğretmen Bulunamadı",
           ]);
       }
    }

}
