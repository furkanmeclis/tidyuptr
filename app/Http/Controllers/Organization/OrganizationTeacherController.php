<?php

namespace App\Http\Controllers\Organization;

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

class OrganizationTeacherController extends Controller
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
    public function create()
    {
        return view('organizationAdmin.teacher.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:teachers'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $teacher = new Teacher;
            $teacher->name =  $request->input('name');
            $teacher->email =  $request->input('email');
            $teacher->max_students =  $request->input('max_students');
            $teacher->is_mentor = $request->input('is_mentor') ? 1 : 0;
            $teacher->password = Hash::make($request->input('password'));
            if ($request->input('phone')) {
                $teacher->phone = $request->input('phone');
            }
            if ($teacher->saveOrFail()) {
                $orgTeacher = new OrganizationTeacher;
                $orgTeacher->organization_id = auth('organization')->user()->id;
                $orgTeacher->teacher_id = $teacher->id;
                $orgTeacher->save();
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('organizationAdmin.teacher.show', ["teacher" => $teacher->id])
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ekleme İşlemi Başarısız"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
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
        $teachers = Teacher::whereIn('id',$getTeacherIds)->where('is_mentor',1)->get()->map(function ($item,$key){
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
            ->with('students', StudentTeacher::where('teacher_id', $id)->whereIn('student_id',Student::where('organization_id',auth('organization')->user()->id)->pluck('id')->toArray())->get());
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
