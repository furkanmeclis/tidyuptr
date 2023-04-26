<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Models\Exams;
use App\Models\Organization;
use App\Models\Student;
use App\Models\StudentTeacher;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SystemAdminStudentController extends Controller
{

    public function index()
    {
        return view('systemAdmin.student.all')->with('students', Student::get());
    }
    public function getOrganization(Request $request)
    {
        return response()->json(User::where('email', $request->input('email'))->first());
    }

    public function create()
    {
        return view('systemAdmin.student.create');
    }

    public function exams($id)
    {
        return view('systemAdmin.student.exam')->with('exams', Exams::where('student_id', $id)->get())->with('student', Student::find($id));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:students'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $student = new Student;
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->password = Hash::make($request->input('password'));
            if ($request->input('phone')) {
                $student->phone = $request->input('phone');
            }
            if ($request->input('address')) {
                $student->address = $request->input('address');
            }
            if ($request->input('organization_id') != 0) {
                $student->organization_id = $request->input('organization_id');
            }
            if ($student->saveOrFail()) {
                StudentTeacher::create([
                    'student_id' => $student->id,
                    'teacher_id' => $request->input('teacher_id')
                ]);
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('systemAdmin.student.show', ["student" => $student->id])
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

    public function show($id)
    {
        return view('systemAdmin.student.show')->with('student', Student::find($id));
    }
    public function showTeacher($id)
    {
        $student = Student::find($id);
        return view('systemAdmin.student.showOrganization')
            ->with('student', $student)
            ->with('organization', Organization::where('id', $student->id)->get());
    }

    public function edit($id)
    {
        return view('systemAdmin.student.edit')->with('student', Student::find($id));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::unique('students')->ignore($id)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $student = Student::find($id);
            if ($student) {
                $student->name = $request->input('name');
                $student->email = $request->input('email');
                if ($request->input('phone')) {
                    $student->phone = $request->input('phone');
                }
                if ($request->input('address')) {
                    $student->address = $request->input('address');
                }
                if ($student->save()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.student.show', ["student" => $student->id])
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Güncelleme İşlemi Başarısız"
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Kayıt Bulunamadı"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
    public function updateTeacher(Request $request,$id){
        $teacher_id = $request->input('teacher_id');
        $student = StudentTeacher::where('student_id',$id)->first();
        if(Student::find($id)){
        if($student){
            $student->teacher_id = $teacher_id;
            if($student->save()){
                return response()->json([
                    "status" => true,
                    "message" => "Öğretmen Güncelleme İşlemi Başarılı",
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Öğretmen Güncelleme İşlemi Başarısız"
                ]);
            }
        }else{
            $new = new StudentTeacher;
            $new->student_id = $id;
            $new->teacher_id = $teacher_id;
            if($new->save()){
                return response()->json([
                    "status" => true,
                    "message" => "Öğretmen Güncelleme İşlemi Başarılı",
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Öğretmen Güncelleme İşlemi Başarısız"
                ]);
            }
        }
        }else{
            return response()->json([
                "status" => false,
                "message" => "Kayıt Bulunamadı"
            ]);
        }
    }
    public function updateOrganization(Request $request,$id){
    $organization_id = $request->input('organization_id');
    $student = Student::find($id);
    if($student){
        $student->organization_id = ($organization_id == 0) ? null : $organization_id;
        if($student->save()){
            return response()->json([
                "status" => true,
                "message" => "Kurum Güncelleme İşlemi Başarılı",
            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => "Kurum Güncelleme İşlemi Başarısız"
            ]);
        }
    }else{
        return response()->json([
            "status" => false,
            "message" => "Kayıt Bulunamadı"
        ]);
    }
}
    public function updatePassword(Request $request, $id)
    {
        try {
            $student = Student::find($id);
            if ($student) {
                $password = $request->input('password');
                $student->password = Hash::make($password);
                if ($student->saveOrFail()) {
                    return response()->json([
                        "status" => true,
                        "message" => "Güncelleme İşlemi Başarılı",
                        "url" => route('systemAdmin.student.show', ["student" => $student->id])
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Güncelleme İşlemi Başarısız"
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Kayıt Bulunamadı"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        $record = Student::find($id);
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
