<?php

namespace App\Http\Controllers\Student;

use App\Models\Answers;
use App\Models\OrganizationTeacher;
use App\Models\Questions;
use App\Models\StudentTeacher;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentQuestionAnswerController extends \Illuminate\Routing\Controller
{
    public function index($id=false){
        $questions = Questions::where('student_id',auth('student')->user()->id)->orderBy('created_at','desc')->paginate(10);
        if(!$activeQuestion = Questions::find($id)){
            $activeQuestion = $questions[0] ?? false;
        }
        return view('student.questionAnswer.all',compact('questions','activeQuestion'));
    }
    public function answer(Request $request,$id){
        if($question = Questions::find($id)){
            if($question->student_id == auth('student')->user()->id) {
                try{
                    $answer = new Answers();
                    $answer->question_id = $id;
                    $answer->student_id = auth('student')->user()->id;
                    $answer->teacher_id = $question->teacher_id;
                    $answer->answer = $request->input('message');
                    $answer->is_teacher = 0;
                    if($answer->save()){
                        if ($request->hasFile('file')) {
                            $file = $request->file('file');
                            $path = $file->store('uploads');
                            $filedAnswer = new Answers();
                            $filedAnswer->question_id = $id;
                            $filedAnswer->student_id = auth('student')->user()->id;
                            $filedAnswer->teacher_id = $question->teacher_id;
                            $filedAnswer->answer = $request->input('message');
                            $filedAnswer->is_teacher = 0;
                            $filedAnswer->file = $path;
                        }
                        if(isset($filedAnswer)){
                            if($filedAnswer->save()){
                                return response()->json(['status' => true, 'message' => 'Cevap Eklendi']);
                            }else{
                                return response()->json(['status' => false, 'message' => 'Cevap Eklendi Dosya Eklenemedi']);
                            }
                        }
                        return response()->json(['status' => true, 'message' => 'Cevap Eklendi']);
                    }else{
                        return response()->json(['status' => false, 'message' => 'Cevap Eklenemedi']);
                    }
                }catch(\Exception $e){
                    return response()->json(['status' => false, 'message' => $e->getMessage()]);
                }
            }else{
                return response()->json(['status' => false, 'message' => 'Yalnızca Tarafınıza Sorulan Soruları Cevaplayabilirsiniz']);
            }
        }else{
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
    }
    public function download($id){
        if($question = Questions::find($id)){
            $answers = $question->answers();
            $content = "Soru: " . $question->question . "\n";
            $content .= "Öğretmen Adı: ".$question->teacher()->name."\n";
            $content .= "Öğrenci Adı: ".$question->student()->name."\n";
            $content .= "Soru Sorma Tarihi: ".$question->created_at->format('d.m.Y H:i')."\n\n";
            $content .= "Cevaplar: \n";
            $content .= "------------------\n";
            foreach ($answers as $answer){
                if($answer->is_teacher){
                    $content .= "Cevaplayan: Öğretmen\n";
                    $content .= "Cevap Tarihi: ".$answer->created_at->format('d.m.Y H:i')."\n";
                    if($answer->file){
                        $content .= "Dosya Adı: ".$answer->getFileName()."\n";
                    }else{
                        $content .= "Cevap: ".$answer->answer."\n";
                    }
                    $content .= "------------------\n";
                }else{
                    $content .= "Cevaplayan: Öğrenci\n";
                    $content .= "Cevap Tarihi: ".$answer->created_at->format('d.m.Y H:i')."\n";
                    if($answer->file){
                        $content .= "Dosya Adı: ".$answer->getFileName()."\n";
                    }else{
                        $content .= "Cevap: ".$answer->answer."\n";
                    }
                    $content .= "------------------\n";
                }
            }
            $zipFileName = date('d.m.Y H-i').' Tarihli Sohbet Kaydı.zip';
            $zip = new \ZipArchive();
            $zip->open(public_path($zipFileName), \ZipArchive::CREATE);
            $zip->addFromString("sohbet.txt",$content);
            if($answers->count() > 0){
                $zip->addEmptyDir('Dosyalar');
            }
            foreach ($answers as $answer){
                if($answer->file){
                    $zip->addFile(storage_path('app/public/'.$answer->file),"Dosyalar/".$answer->getFileName());
                }
            }
            $zip->setArchiveComment('Bu Sohbet Kaydı Öğrenci Tarafından İstenilmiş Olup '.date('d.m.Y H:i').' Tarihinde Oluşturulmuştur.
'.env('APP_NAME')." Tarafından Oluşturulmuştur".'
Sohbet Id: '.$question->id.'
Soru: '.$question->question.'
Öğretmen Adı: '.$question->teacher()->name.'
Öğrenci Adı: '.$question->student()->name.'
Soru Sorma Tarihi: '.$question->created_at->format('d.m.Y H:i'));
            $zip->close();
            return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
        }else{
            return response()->json(['status' => false, 'message' => 'Kayıt Bulunamadı']);
        }
    }

    public function ask()
    {
        $organization_control = auth('student')->user()->organization_id;
        if($organization_control != 0){
            $teacher_ids = OrganizationTeacher::where('organization_id',$organization_control)->pluck('teacher_id');
            $student_teacher = StudentTeacher::where('student_id',auth('student')->user()->id)->first();
            if($student_teacher){
                $teacher_ids = $teacher_ids->push($student_teacher->teacher_id);
            }
            $teachers = Teacher::whereIn('id',$teacher_ids)->get();
        }else{
            $teacher_id = StudentTeacher::where('student_id')->first()->teacher_id;
            $teachers = Teacher::where('id',$teacher_id)->get();
        }
        return view('student.questionAnswer.ask')->with('teachers',$teachers);
    }

    public function downloadAll()
    {
        $qu = Questions::where('student_id',auth('student')->user()->id)->get();
        $pdf = PDF::loadView('student.questionAnswer.pdf', [
            'quest' => $qu,
        ])->setOptions(['isPhpEnabled' => true,'isRemoteEnabled' => true]);
        return $pdf->download('yapamadigim-sorular.pdf');
    }

    public function store(Request $request)
    {
        try{
            if($teacher = Teacher::find($request->input('teacher_id'))){
                $teacher_id = $teacher->id;
                $student = auth('student')->user();
                $question = new Questions();
                $question->question = $request->input('question');
                $question->student_id = $student->id;
                $question->teacher_id = $teacher_id;
               if($question->save()){
                   $answer = new Answers();
                   $answer->question_id = $question->id;
                   $answer->student_id = $student->id;
                   $answer->teacher_id = $teacher_id;
                   $answer->answer = $request->input('question');
                   $answer->is_teacher = 0;
                   if ($request->hasFile('file')) {
                          $file = $request->file('file');
                          $path = $file->store('uploads');
                          $answer->file = $path;
                   }
                   if($answer->save()) {
                       if ($request->hasFile('file')) {
                           $file = $request->file('file');
                           $path = $file->store('uploads');
                           $filedAnswer = new Answers();
                           $filedAnswer->question_id = $question->id;
                           $filedAnswer->student_id = $student->id;
                           $filedAnswer->teacher_id = $teacher_id;
                           $filedAnswer->answer = $request->input('question');
                           $filedAnswer->is_teacher = 0;
                           $filedAnswer->file = $path;
                            if ($filedAnswer->save()) {
                                 return response()->json(['status' => true, 'message' => 'Soru Eklendi']);
                            } else {
                                 return response()->json(['status' => false, 'message' => 'Soru Eklendi Fakat Dosya Eklenemedi']);
                            }
                       }
                       return response()->json(['status' => true, 'message' => 'Soru Eklendi']);
                   }
               }else{
                     return response()->json(['status' => false, 'message' => 'Soru Eklenemedi']);
               }
            }else{
                return response()->json(['status' => false, 'message' => 'Öğretmen Bulunamadı']);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function complete(Request $request, $id)
    {
        try{
            $question = Questions::find($id);
            if($question){
                $question->is_answered = 1;
                if($question->save()){
                    return response()->json(['status' => true, 'message' => 'Soru Tamamlandı']);
                }else{
                    return response()->json(['status' => false, 'message' => 'Soru Tamamlanamadı']);
                }
            }else{
                return response()->json(['status' => false, 'message' => 'Soru Bulunamadı']);
            }
        }catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
