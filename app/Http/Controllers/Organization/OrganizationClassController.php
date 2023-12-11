<?php

namespace App\Http\Controllers\Organization;

use App\Models\ClassAnnouncements;
use App\Models\ClassAttendance;
use App\Models\Classes;
use App\Models\ClassStudents;
use App\Models\DayHours;
use App\Models\DayTable;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\HourTable;
use App\Models\Lesson;
use App\Models\OrganizationTeacher;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TimeTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationClassController extends Controller
{
    public function index()
    {
        $classes = Classes::where('organization_id', auth('organization')->user()->id)->get();
        return view('organizationAdmin.class.all')->with('classes', $classes);
    }

    public function create()
    {
        $org_teachers = OrganizationTeacher::where('organization_id', auth('organization')->user()->id)->pluck('teacher_id');
        $class_teachers = Classes::whereIn('teacher_id', $org_teachers)->pluck('teacher_id');
        $teachers = Teacher::whereIn('id', $org_teachers)->whereNotIn('id',$class_teachers)->get();
        return view('organizationAdmin.class.create')->with('teachers', $teachers);
    }

    public function download($id)
    {
        $class = Classes::find($id);
        $class_students = ClassStudents::where('class_id', $id)->pluck('student_id')->toArray();
        $students = Student::whereIn('id', $class_students)->get();
        //exams
        $exam_ids = Exams::whereIn('student_id',$class_students)->pluck('id')->toArray();
        $exams = ExamResults::whereIn('exam_id',$exam_ids)->get();
        $lessons = [];
        foreach ($exams as $exam){
            $lessonId = $exam->lesson_id;
            $data = isset($lessons[$lessonId]) ? $lessons[$lessonId] : [];
            if (empty($data)) {
                $data = [
                    'total' => 0,
                    'correct_answers' => 0,
                    'wrong_answers' => 0,
                ];
            }

            $data['correct_answers'] += $exam->correct_answers;
            $data['wrong_answers'] += $exam->wrong_answers;
            $lessons[$lessonId] = $data;
        }
        $lastData = [];
        foreach($lessons as $lesson_id => $scores) {
            $std = new \stdClass();
            $std->lesson = \App\Models\Lesson::find($lesson_id);
            $std->correct_answers = $scores['correct_answers'] / count($exam_ids);
            $std->wrong_answers = round($scores['wrong_answers'] / count($exam_ids));
            $std->total = round($scores['correct_answers'] / count($exam_ids)) - (round($scores['wrong_answers'] / count($exam_ids)) / 4);
            $lastData[] = $std;
        }
        $timeTable = TimeTable::where('class_id',$id)->first();
        $classAttendances = ClassAttendance::whereBetween('created_at', [Carbon::now()->subWeek(),Carbon::now()])->where('class_id',$id)->get();
        $pdf = PDF::loadView('organizationAdmin.class.downloadPdf', [
            'class' => $class,
            'students' => $students,
            'timetable' => $timeTable,
            'lessons' => $lastData,
            'classAttendances' => $classAttendances
        ]);
        $filename = $class->name.'-class'.date('d-m-Y_H-i').'.pdf';
        return $pdf->download($filename);

    }
    public function store(Request $request)
    {
        try{
            $class = new Classes();
            $class->name = $request->input('name');
            $class->teacher_id = $request->input('teacher_id');
            $class->organization_id = auth('organization')->user()->id;

            if($class->save()){
                $students = $request->input('student_id');
                foreach ($students as $student){
                    $class_student = new ClassStudents();
                    $class_student->class_id = $class->id;
                    $class_student->student_id = $student;
                    $class_student->save();
                }
                return response()->json([
                    "status" => true,
                    "message" => "Ekleme İşlemi Başarılı",
                    "url" => route('organizationAdmin.class.createTimeTable', $class->id)
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Ekleme İşlemi Başarısız",
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => "Ekleme İşlemi Başarısız",
            ]);
        }
    }

    public function createTimeTable($id)
    {
        $lessons = Lesson::all();
        $teachers = Teacher::whereIn('id', OrganizationTeacher::where('organization_id',auth('organization')->user()->id)->pluck('teacher_id')->toArray())->get();
        $class = Classes::find($id);
        return view('organizationAdmin.class.createTimeTable')
            ->with('teachers', $teachers)
            ->with('lessons', $lessons)
            ->with('class', $class);
    }

    public function createTimeTableStore(Request $request,$id)
    {
        try{
            if(Classes::find($id)){
                if(!TimeTable::where('class_id',$id)->first()){
                    $lesson_ids = Lesson::all()->pluck('id')->toArray();
                    $teacher_ids = Teacher::whereIn('id', OrganizationTeacher::where('organization_id',auth('organization')->user()->id)->pluck('teacher_id')->toArray())->pluck('id')->toArray();
                    //TimeTable Create
                    $timeTable = new TimeTable();
                    $timeTable->class_id = $id;
                    if($timeTable->save()){
                        //DayTable Insert
                        foreach ($request->input('days') as $indexDay => $day){
                            $dayTable = new DayTable();
                            $dayTable->time_table_id = $timeTable->id;
                            $dayTable->day = $indexDay;
                            $dayTable->start_time = Carbon::createFromFormat('H:i',$day['start_time']);
                            $duration = (int) $day['duration'];
                            $recess = (int) $day['recess'];
                            if($dayTable->save()){
                                //HourTable Insert
                                foreach($day["hours"] as $indexHour => $hour){
                                    $hourTable = new HourTable();
                                    $hourTable->day_table_id = $dayTable->id;
                                    $hourTable->index = $indexHour;
                                    if($hour['lesson_id'] == "recess"){
                                        $hourTable->duration = 0;
                                        $hourTable->recess = $hour['recess'];
                                        $hourTable->is_recess = true;
                                    }else{
                                        $hourTable->duration = $duration;
                                        $hourTable->recess = $recess;
                                    }

                                    if($hourTable->save()){
                                        if(!$hourTable->is_recess){
                                            //dayHours ınsert
                                            //control lesson_id
                                            if (!in_array($hour['lesson_id'], $lesson_ids)) {
                                                $timeTable->delete();
                                                return response()->json([
                                                    "status" => false,
                                                    "message" => "Ders Programı Oluşturulamadı - Ders Bulunamadı",
                                                ]);
                                            }
                                            //control teacher_id
                                            if (!in_array($hour['teacher_id'], $teacher_ids)) {
                                                $timeTable->delete();
                                                return response()->json([
                                                    "status" => false,
                                                    "message" => "Ders Programı Oluşturulamadı - Öğretmen Bulunamadı",
                                                ]);
                                            }
                                            $dayHour = new DayHours();
                                            $dayHour->hour_table_id = $hourTable->id;
                                            $dayHour->lesson_id = $hour['lesson_id'];
                                            $dayHour->teacher_id = $hour['teacher_id'];

                                            if ($dayHour->save()) {

                                            }else{
                                                $timeTable->delete();
                                                return response()->json([
                                                    "status" => false,
                                                    "message" => "Ders Programı Oluşturulamadı",
                                                ]);
                                            }
                                        }
                                    }else{
                                        $timeTable->delete();
                                        return response()->json([
                                            "status" => false,
                                            "message" => "Ders Programı Oluşturulamadı",
                                        ]);
                                    }
                                }
                            }else{
                                $timeTable->delete();
                                return response()->json([
                                    "status" => false,
                                    "message" => "Ders Programı Oluşturulamadı",
                                ]);
                            }
                        }
                        return response()->json([
                            "status" => true,
                            "message" => "Ders Programı Oluşturuldu",
                            "url" => route('organizationAdmin.class.show', $id)
                        ]);
                    }else{
                        return response()->json([
                            "status" => false,
                            "message" => "Ders Programı Oluşturulamadı",
                        ]);
                    }
                }else{
                    return response()->json([
                        "status" => false,
                        "message" => "Bu Sınıf İçin Zaten Ders Programı Oluşturulmuş",
                    ]);
                }
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Sınıf Bulunamadı",
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }
    public function show($id)
    {
        $class = Classes::find($id);
        $timeTable = TimeTable::where('class_id',$id)->first();
        if(!$timeTable){
            return redirect()->route('organizationAdmin.class.createTimeTable', $id);
        }
        return view('organizationAdmin.class.show')->with('class', $class);
    }
    public function showAttendance($classId,$hourId)
    {
        $attendances_0 = ClassAttendance::where('class_id',$classId)->where('day_hour_id',$hourId)->where('attendance_status',0)->get();
        $attendances_1 = ClassAttendance::where('class_id',$classId)->where('day_hour_id',$hourId)->where('attendance_status',1)->get();
        return response()->json([
            "title" => "Devamsızlık Listesi - Ders: ".DayHours::find($hourId)->lesson()->name." - Öğretmen: ".DayHours::find($hourId)->teacher()->name,
            "html" => view('organizationAdmin.class.showAttendance', compact('attendances_0','attendances_1'))->render(),
        ]);
    }
    public function destroy(Request $request,$id)
    {
        try{
            $class = Classes::find($id);
            if($class->delete()){
                return response()->json([
                    "status" => true,
                    "message" => "Silme İşlemi Başarılı",
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Silme İşlemi Başarısız",
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function attendanceDownloadAll($classId, $dayId)
    {
        $day = DayTable::find($dayId);
        $HourIds = HourTable::where('day_table_id',$dayId)->pluck('id')->toArray();
        $dayHourIds = DayHours::where('hour_table_id',$HourIds)->pluck('id')->toArray();
        $classAttendances = ClassAttendance::whereIn('day_hour_id',$dayHourIds)->where('class_id',$classId)->get();
        $pdf = PDF::loadView('organizationAdmin.attendanceDownloadAll', compact('classAttendances','day'))->setOptions(['isPhpEnabled' => true]);
        return $pdf->download('devamsızlık_listesi.pdf');
    }

    public function attendanceDownload_1($classId, $dayId)
    {
        $day = DayTable::find($dayId);
        $HourIds = HourTable::where('day_table_id',$dayId)->pluck('id')->toArray();
        $dayHourIds = DayHours::where('hour_table_id',$HourIds)->pluck('id')->toArray();
        $classAttendances = ClassAttendance::whereIn('day_hour_id',$dayHourIds)->where('attendance_status',1)->where('class_id',$classId)->get();
        $pdf = PDF::loadView('organizationAdmin.class.attendanceDownloadAll', compact('classAttendances','day'))->setOptions(['isPhpEnabled' => true]);
        return $pdf->download('devamsızlık_listesi.pdf');
    }
    public function attendanceDownload_0($classId, $dayId)
    {
        $day = DayTable::find($dayId);
        $HourIds = HourTable::where('day_table_id',$dayId)->pluck('id')->toArray();
        $dayHourIds = DayHours::where('hour_table_id',$HourIds)->pluck('id')->toArray();
        $classAttendances = ClassAttendance::whereIn('day_hour_id',$dayHourIds)->where('attendance_status',0)->where('class_id',$classId)->get();
        $pdf = PDF::loadView('organizationAdmin.class.attendanceDownloadAll', compact('classAttendances','day'))->setOptions(['isPhpEnabled' => true]);
        return $pdf->download('devamsızlık_listesi.pdf');
    }

    public function getStudentsClass($id)
    {
        $ids = ClassStudents::where('class_id',$id)->pluck('student_id')->toArray();

        $students = Student::whereIn('id',$ids)->where('organization_id',auth('organization')->user()->id)->get()->map(function ($item,$key){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'selected' => true,
            ];
        })->toArray();
        $students_2 = Student::whereNotIn('id',$ids)->where('organization_id',auth('organization')->user()->id)->get()->map(function ($item,$key){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'selected' => false,
            ];
        })->toArray();
        $students = array_merge($students,$students_2);
        return response()->json([
            "data" => $students,
        ]);

    }

    public function edit($id)
    {
        $org_teachers = OrganizationTeacher::where('organization_id', auth('organization')->user()->id)->pluck('teacher_id');
        $teachers = Teacher::whereIn('id', $org_teachers)->get();
        $class = Classes::find($id);
        return view('organizationAdmin.class.edit')->with('class', $class)->with('teachers', $teachers);
    }

    public function update(Request $request,$id)
    {
        try{
            $class = Classes::find($id);
            if(!$class){
                return response()->json([
                    "status" => false,
                    "message" => "Sınıf Bulunamadı",
                ]);
            }
            $class->name = $request->input('name');
            $class->teacher_id = $request->input('teacher_id');
            if($class->save()){
                $students = $request->input('student_id');
                $class->students()->delete();
                foreach ($students as $student){
                    $class_student = new ClassStudents();
                    $class_student->class_id = $class->id;
                    $class_student->student_id = $student;
                    $class_student->save();
                }
                return response()->json([
                    "status" => true,
                    "message" => "Sınıf Güncellendi",
                    "url" => route('organizationAdmin.class.show', $class->id)
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Sınıf Güncellenemedi",
                ]);
            }
        }catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function announcementIndex($id)
    {
        return view('organizationAdmin.class.announcements', [
            'contents' => ClassAnnouncements::where('class_id', $id)->orderBy('updated_at', 'asc')->get(),
            'class_id' => $id
        ]);
    }
    public function announcementStore(Request $request,$classId){
        try {
            $content = $request->input('content');
            $class = new ClassAnnouncements();
            $class->class_id = $classId;
            $class->content = $content;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads');
                $class->file = $path;
            }
            if($class->save()){
                return response()->json([
                    "status" => true,
                    "message" => "Duyuru Eklendi",
                    "url" => route('organizationAdmin.class.announcement.index', $classId)
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Duyuru Eklenemedi",
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ]);
        }

    }

    public function announcementDestroy($classId,$id)
    {
        $content = ClassAnnouncements::where('class_id', $classId)->where('id', $id)->first();
        if (!$content) {
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        try {
            $file_path = $content->file;
            if ($file_path) {
                Storage::delete($file_path);
            }
            $content->delete();
            return response()->json(['status' => true, 'message' => 'Kayıt Başarıyla Silindi']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
