<?php

namespace App\Http\Controllers\Student;

use App\Models\Answers;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentQuestionAnswerController extends \Illuminate\Routing\Controller
{
    public function index($id=false){
        $questions = Questions::where('teacher_id',auth('teacher')->user()->id)->orderBy('created_at','desc')->paginate(10);
        if(!$activeQuestion = Questions::find($id)){
            $activeQuestion = $questions[0] ?? false;
        }
        return view('teacher.questionAnswer.all',compact('questions','activeQuestion'));
    }
    public function answer(Request $request,$id){
        if($question = Questions::find($id)){
            if($question->teacher_id == auth('teacher')->user()->id) {
                try{
                    $answer = new Answers();
                    $answer->question_id = $id;
                    $answer->teacher_id = auth('teacher')->user()->id;
                    $answer->student_id = $question->student_id;
                    $answer->answer = $request->input('message');
                    $answer->is_teacher = 1;
                    if($answer->save()){
                        if ($request->hasFile('file')) {
                            $file = $request->file('file');
                            $path = $file->store('uploads');
                            $filedAnswer = new Answers();
                            $filedAnswer->question_id = $id;
                            $filedAnswer->teacher_id = auth('teacher')->user()->id;
                            $filedAnswer->student_id = $question->student_id;
                            $filedAnswer->answer = $request->input('message');
                            $filedAnswer->is_teacher = 1;
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
            $zip->setArchiveComment('Bu Sohbet Kaydı Öğretmen Tarafından İstenilmiş Olup '.date('d.m.Y H:i').' Tarihinde Oluşturulmuştur.
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
}
