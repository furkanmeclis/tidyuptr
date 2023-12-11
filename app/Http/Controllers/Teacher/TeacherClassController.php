<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Announcements;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherClassController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $class = Classes::where('teacher_id', auth('teacher')->user()->id)->first();

        return view('teacher.class.show')->with('class', $class);
    }

    public function all()
    {
        $classes = Classes::whereIn('organization_id', OrganizationTeacher::where('teacher_id',auth('teacher')->user()->id)->pluck('organization_id')->toArray())->get();
        return view('teacher.class.all')->with('classes', $classes);
    }
    public function download($id = null)
    {
        if($id == null){
           $id = Classes::where('teacher_id', auth('teacher')->user()->id)->first()->id;
        }
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
    public function show($id)
    {
        $class = Classes::find($id);
        return view('teacher.class.show')->with('class', $class);
    }
    public function showAttendance($classId,$hourId)
    {
        $hour = DayHours::find($hourId);
        $students = Student::whereIn('id',ClassStudents::where('class_id',$classId)->pluck('student_id')->toArray())->get();
        $attendances_0 = ClassAttendance::where('class_id',$classId)->where('day_hour_id',$hourId)->where('attendance_status',0)->get();
        $attendances_1 = ClassAttendance::where('class_id',$classId)->where('day_hour_id',$hourId)->where('attendance_status',1)->get();
        return response()->json([

            "title" => "Devamsızlık Listesi - Ders: ".DayHours::find($hourId)->lesson()->name." - Öğretmen: ".DayHours::find($hourId)->teacher()->name,
            "html" => view('teacher.class.showAttendance', compact('attendances_0','students','attendances_1','hour',"classId"))->render(),
        ]);
    }

    public function attendanceDownloadAll($classId, $dayId)
    {
        $day = DayTable::find($dayId);
        $HourIds = HourTable::where('day_table_id',$dayId)->pluck('id')->toArray();
        $dayHourIds = DayHours::where('hour_table_id',$HourIds)->pluck('id')->toArray();
        $classAttendances = ClassAttendance::whereIn('day_hour_id',$dayHourIds)->where('class_id',$classId)->get();
        $pdf = PDF::loadView('organizationAdmin.class.attendanceDownloadAll', compact('classAttendances','day'))->setOptions(['isPhpEnabled' => true]);
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
    public function announcementIndex($id = null)
    {
        if($id==null){
            $id = Classes::where('teacher_id', auth('teacher')->user()->id)->first()->id;
        }


        return view('teacher.class.announcements', [
            'contents' => ClassAnnouncements::where('class_id', $id)->orderBy('updated_at', 'asc')->get(),
            'class_id' => $id
        ]);
    }
    public function announcementStore(Request $request,$id = null){
        try {
            $classId = $id == null ? Classes::where('teacher_id', auth('teacher')->user()->id)->first()->id : $id;
            $content = $request->input('content');
            $class = new ClassAnnouncements();
            $class->class_id = $classId;
            $class->teacher_id = auth('teacher')->user()->id;
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
                    "url" => $id == null ? route('teacher.class.announcement.index') : route('teacher.class.announcement.index',[$id])
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

    public function initAttendance(Request $request, $classId, $hourId)
    {
        try {
            if($class = Classes::find($classId)){
                if($hour = DayHours::find($hourId)){
                    $studentAccepted = $request->input('student_id');
                    foreach ($class->students()->whereNotIn('student_id',$studentAccepted)->get() as $student){
                        $newAttendance = new ClassAttendance();
                        $newAttendance->class_id = $classId;
                        $newAttendance->day_hour_id = $hourId;
                        $newAttendance->student_id = $student->student_id;
                        $newAttendance->attendance_status = 0;
                        $newAttendance->save();
                    }
                    foreach ($studentAccepted as $student_id){
                        $newAttendance = new ClassAttendance();
                        $newAttendance->class_id = $classId;
                        $newAttendance->day_hour_id = $hourId;
                        $newAttendance->student_id = $student_id;
                        $newAttendance->attendance_status = 1;
                        $newAttendance->save();
                    }
                    return response()->json(['status' => true, 'message' => 'Devamsızlık Bilgi Giriş İşlemi Başarıyla Gerçekleştirildi']);
                }else{
                    return response()->json(['status' => false, 'message' => 'Ders Saati Bulunamadı']);
                }
            }else{
                return response()->json(['status' => false, 'message' => 'Sınıf Bulunamadı']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
