<?php

namespace App\Http\Controllers\Teacher;
use App\Mail\RemindAgenta;
use App\Models\MentorFollowUp;
use App\Models\RemindMentorFollowUp;
use App\Models\Student;
use App\Models\StudentTeacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TeacherMentorFollowUpController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $getStudentIds = StudentTeacher::where('teacher_id', auth('teacher')->user()->id)->get()->map(function ($record) {
            return $record->student_id;
        });
        $students = Student::whereIn('id', $getStudentIds)->get()->map(function ($record) {
            return [
                'id' => $record->id,
                'name' => $record->name,
            ];
        })->toArray();

        $mentorFollowUp = MentorFollowUp::where('teacher_id', auth('teacher')->user()->id)->whereIn('student_id',$getStudentIds)->get();
        $calendarData = [];
        for ($i = 0; $i < 30; $i++) {
            $calendarData[Carbon::today()->subDays($i)->format("Y-m-d")] = [];
        }
        foreach ($mentorFollowUp as $item) {
            $studentName = $this->getStudentName($item->student_id, $students);
            $calendarData[$item->getDate('-')][] = [
                "id" => $item->student_id,
                "name" => $studentName,
                "url" => $item->getFileUrl(),
                "send" => true
            ];
        }
        foreach($calendarData as $key => $dateOnly){
            foreach($students as $student){
                if(!in_array($student['id'],array_column($dateOnly,'id'))){
                    $calendarData[$key][] = [
                        "id" => $student['id'],
                        "name" => $student['name'],
                        "url" => "#",
                        "send" => false,
                    ];
                }
            }
        }
        $printData = [];
        $i=0;
        foreach ($calendarData as $key => $item) {
            foreach ($item as $index => $value) {
                $printData[] = [
                    "id" => $i,
                    "send"=> $value['send'],
                    "student_id" => $value['id'],
                    "title" => $value['name'],
                    "start" => $key,
                    "end" => $key,
                    "url" => $value['send'] ? $value['url'] : route('teacher.mentor.remindStudent',$value['id']),
                    "color" => $value['send'] ? "#28a745" : "#dc3545",
                    "className" => $value['send'] ? "" : "unsended-agenta",
                ];
                $i++;
            }
        }
        return view('teacher.mentor-follow-up.calendar')->with('data', $printData);
    }
    protected function getStudentName($id, $data)
    {
        foreach ($data as $index => $item) {
            if ($item['id'] == $id) {
                return $item['name'];
            }
        }
        return null;
    }
    public function remindStudent(Request $request,$id)
    {
        $student = Student::find($id);
        if(!$student){
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
        if(StudentTeacher::where('student_id',$id)->where('teacher_id',auth('teacher')->user()->id)->count() == 0){
            return response()->json(['status' => false, 'message' => 'Yalnızca Kendi Öğrencinize Hatırlatmada Bulunabilirsiniz.']);
        }
        $today = Carbon::today();
        $record = RemindMentorFollowUp::where('student_id',$id)
            ->where('teacher_id',auth('teacher')->user()->id)
            ->whereRaw('DATE(created_at) = ?', [$today])->count();
        if($record > 0){
            return response()->json(['status' => false, 'message' => 'Bugün Zaten Hatırlatma Yaptınız.']);
        }else{
            try{
                Mail::to(['furkanmeclis@icloud.com'])->send(new RemindAgenta($student,auth('teacher')->user()));
                RemindMentorFollowUp::create([
                    'student_id' => $id,
                    'teacher_id' => auth('teacher')->user()->id,
                ]);
                return response()->json(['status' => true, 'message' => 'Hatırlatma Maili Gönderildi.']);
            }catch(\Exception $e){
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }
    }
}
