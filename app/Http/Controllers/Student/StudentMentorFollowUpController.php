<?php

namespace App\Http\Controllers\Student;
use App\Mail\RemindAgenta;
use App\Models\MentorFollowUp;
use App\Models\RemindMentorFollowUp;
use App\Models\Student;
use App\Models\StudentTeacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StudentMentorFollowUpController extends \Illuminate\Routing\Controller
{
    public function index()
    {

        $student = auth('student')->user();

        $mentorFollowUp = MentorFollowUp::where('student_id', auth('student')->user()->id)->get();
        $calendarData = [];
        for ($i = 0; $i < 30; $i++) {
            $calendarData[Carbon::today()->subDays($i)->format("Y-m-d")] = [
                "id" => $student['id'],
                "name" => $student['name'],
                "url" => "#",
                "send" => false,
            ];
        }
        foreach ($mentorFollowUp as $item) {
            $calendarData[$item->getDate('-')] = [
                "id" => $item->student_id,
                "name" => $student->name,
                "url" => $item->getFileUrl(),
                "send" => true
            ];
        }
        $printData = [];
        $i=0;
        foreach ($calendarData as $key => $value) {
                $printData[] = [
                    "id" => $i,
                    "send"=> $value['send'],
                    "student_id" => $value['id'],
                    "title" => $value['send'] ? "Gönderildi" : "Gönderilmedi",
                    "start" => $key,
                    "end" => $key,
                    "url" => $value['send'] ? $value['url'] : "#",
                    "color" => $value['send'] ? "#28a745" : "#dc3545",
                    "className" => $value['send'] ? "" : "unsended-agenta",
                ];
                $i++;
        }
        return view('student.mentor-follow-up.calendar')->with('data', $printData);
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
    public function store(Request $request){
        if ($request->hasFile('file')) {
            try{
                if($teacher = StudentTeacher::where('student_id',auth('student')->user()->id)->first()){
                    if(MentorFollowUp::where('student_id',auth('student')->user()->id)->whereDate(
                        'created_at', Carbon::today()
                    )->first()){
                        return response()->json(['status' => false, 'message' => 'Bugün Zaten Gönderildi']);
                    }
                    $file = $request->file('file');
                    $path = $file->store('uploads');
                    $teacher_id = $teacher->teacher_id;
                    $mentorFollowUp = new MentorFollowUp();
                    $mentorFollowUp->teacher_id = $teacher_id;
                    $mentorFollowUp->student_id = auth('student')->user()->id;
                    $mentorFollowUp->file = $path;
                    if($mentorFollowUp->save()){
                        return response()->json(['status' => true, 'message' => 'Dosya Yüklendi',"url" => route('student.mentor.index')]);
                    }else{
                        return response()->json(['status' => false, 'message' => 'Dosya Yüklenemedi']);
                    }
                }else{
                    return response()->json(['status' => false, 'message' => 'Öğretmen Bulunamadı']);
                }

            }catch(\Exception $e){
                return response()->json(['status' => false, 'message' => $e->getMessage()]);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Dosya Seçilmeli']);
        }
    }
}
