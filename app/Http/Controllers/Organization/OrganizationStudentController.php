<?php

namespace App\Http\Controllers\Organization;

use App\Exports\StudentExport;
use App\Imports\StudentImport;
use App\Models\BatchExamLessons;
use App\Models\BatchExams;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Organization;
use App\Models\OrganizationTeacher;
use App\Models\Parents;
use App\Models\Student;
use App\Models\StudentTeacher;
use App\Models\Teacher;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class OrganizationStudentController extends Controller
{

    public function index()
    {
        return view('organizationAdmin.student.all')->with('students', Student::where('organization_id',Auth::guard('organization')->user()->id)->get());
    }
    public function getOrganization(Request $request)
    {
        return response()->json(User::where('email', $request->input('email'))->first());
    }

    public function download($type = 'html')
    {
        $writerType = \Maatwebsite\Excel\Excel::XLSX;
        if ($type == 'csv') {
            $writerType = \Maatwebsite\Excel\Excel::CSV;
        }
        if($type == 'html'){
            $writerType = \Maatwebsite\Excel\Excel::HTML;
        }
        return Excel::download(new StudentExport, 'students.'.$type,$writerType);
    }

    public function importStudents(Request $request)
    {
        try{
            $import = new StudentImport;
            Excel::import($import,$request->file('file'));

            return response()->json([
                'status' => true,
                'message' => 'Öğrenciler başarıyla eklendi',
                'data' =>$import->getResult()
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
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
        return view('organizationAdmin.student.exam')->with('exams', Exams::where('student_id', $id)->get())->with('student', Student::find($id));
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
            $teacher = Teacher::find($request->input('teacher_id'));
            if (!$teacher) {
                return response()->json([
                    "status" => false,
                    "message" => "Öğretmen Bulunamadı"
                ]);
            }
            $quantity = StudentTeacher::where('teacher_id', $teacher->id)->count();
            if ($quantity >= $teacher->max_students) {
                return response()->json([
                    "status" => false,
                    "message" => "Öğretmenin Öğrenci Kontenjanı Dolmuştur"
                ]);
            }
            $student = new Student;
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->identity_number = $request->input('identity_number');
            $student->grade = $request->input('grade');
            $student->password = Hash::make($request->input('password'));
            if ($request->input('phone')) {
                $student->phone = $request->input('phone');
            }
            if ($request->input('address')) {
                $student->address = $request->input('address');
            }
            $student->organization_id = Auth::guard('organization')->user()->id;

            if ($student->saveOrFail()) {

                StudentTeacher::create([
                    'student_id' => $student->id,
                    'teacher_id' => $request->input('teacher_id')
                ]);
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('organizationAdmin.student.show', ["student" => $student->id])
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
        return view('organizationAdmin.student.show')->with('student', Student::find($id));
    }
    public function showTeacher($id)
    {
        $student = Student::find($id);
        return view('organizationAdmin.student.showOrganization')
            ->with('student', $student)
            ->with('organization', Organization::where('id', $student->id)->get());
    }

    public function edit($id)
    {
        return view('organizationAdmin.student.edit')->with('student', Student::find($id));
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
                $student->identity_number = $request->input('identity_number');
                $student->email = $request->input('email');
                $student->grade = $request->input('grade');
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
                        "url" => route('organizationAdmin.student.show', ["student" => $student->id])
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
        if(OrganizationTeacher::where('teacher_id',$teacher_id)->where('organization_id',Auth::guard('organization')->user()->id)->first() == null){
            return response()->json([
                "status" => false,
                "message" => "Yalnızca Sizinle Çalışan Öğretmenleri Seçebilirsiniz."
            ]);
        }
        if(Student::find($id)){
            if($student){
                $teacher = Teacher::find($request->input('teacher_id'));
                $quantity = StudentTeacher::where('teacher_id', $teacher->id)->count();

                if ($quantity > $teacher->max_students || $quantity == $teacher->max_students) {
                    return response()->json([
                        "status" => false,
                        "message" => "Öğretmenin Öğrenci Kontenjanı Dolmuştur"
                    ]);
                }
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
                $teacher = Teacher::find($request->input('teacher_id'));
                $quantity = StudentTeacher::where('teacher_id', $teacher->id)->count();

                if ($quantity > $teacher->max_students || $quantity == $teacher->max_students) {
                    return response()->json([
                        "status" => false,
                        "message" => "Öğretmenin Öğrenci Kontenjanı Dolmuştur"
                    ]);
                }
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
                        "url" => route('organizationAdmin.student.show', ["student" => $student->id])
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
        $record = Student::where('id',$id)->where('organization_id',Auth::guard('organization')->user()->id)->first();
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

    public function getStudentExams($id)
    {
        $student = Student::find($id);
        $exams = Exams::where('student_id', $id)->get();
        return view('organizationAdmin.student.exam.all')->with('exams', $exams)->with('student', $student);
    }

    public function showStudentExam($studentId, $examId)
    {
        return response()->json([
            "html" => view('organizationAdmin.exam.showExam', [
                'exam' => Exams::where('id', $examId)->where('student_id',$studentId)->first(),
            ])->render(),
        ]);
    }
    public function downloadPdf($student, $id)
    {
        $exam = Exams::where('id', $id)->first();
        $pdf = PDF::loadView('organizationAdmin.reports.examScore', [
            'exam' => $exam,
        ])->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream($exam->student()->name . '-deneme-sonuc.pdf');
    }


    public function editExam($student,$examId)
    {
        $exam = Exams::where('id', $examId)->first();
        return view('organizationAdmin.student.exam.editExam', [
            'exam' => $exam,
        ]);
    }

    public function updateExam(Request $request,$student,$exam)
    {
        $exam = Exams::find($exam);
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        $lessons = $request->input('lessons');
        foreach ($lessons as $key => $lesson) {
            ExamResults::where('exam_id', $exam->id)->where('lesson_id', $key)->update([
                'correct_answers' => (int)$lesson['correct_answers'],
                'wrong_answers' => (int)$lesson['wrong_answers'],
            ]);
        }
        try {
            $exam->save();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Güncellendi',"url"=>route('organizationAdmin.student.exam.index',[
                "student" => $student,
            ])]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroyExam(Request $request,$student,$exam)
    {
        $exam = Exams::where('id',$exam)->where('student_id',$student)->first();
        if (!$exam) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $exam->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function saveParents(Request $request, Student $student)
    {
        if($student){
            try {
                $parents = $request->input('parents');
                $import = [];
                foreach ($parents as $key => $parent){
                    $name = $parent['name'];
                    $email = $parent['email'];
                    $phone = $parent['phone'];
                    $parentForDb = new Parents();
                    $parentForDb->name = $name;
                    $parentForDb->email = $email;
                    $parentForDb->phone = $phone;
                    $parentForDb->student_id = $student->id;
                    $import[] = $parentForDb;
                }
                if(count($import) == 0){
                    return response()->json([
                        "status" => false,
                        "message" => "Güncellenecek Yada Kaydedilecek Veri Bulunamadı"
                    ]);
                }else{
                    Parents::where('student_id',$student->id)->delete();
                    foreach ($import as $key => $value){
                        $value->save();
                    }
                    return response()->json([
                        "status" => true,
                        "message" => "İşlem Başarılı"
                    ]);
                }
            }catch (\Exception $e){
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage()
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "message" => "Kayıt Bulunamadı"
            ]);
        }
    }
}
