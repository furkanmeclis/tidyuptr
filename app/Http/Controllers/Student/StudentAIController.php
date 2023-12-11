<?php

namespace App\Http\Controllers\Student;

use App\Models\AIRecords;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class StudentAIController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        $messages = AIRecords::where('student_id',auth('student')->user()->id)->get();
        return view('student.ai.index')->with('messages',$messages);
    }

    public function store(Request $request)
    {
        $ai = new OpenAIService();
        $message = $request->input('message');
        return response()->json($ai->send($message));
    }
    public function download(){
        $messages = AIRecords::where('student_id',auth('student')->user()->id)->get();
        $txt = "Yol Arkadaşım Modülü Sohbet Kaydı\n";
        $txt .= "Oluşturulma Tarihi: ".now()->format('d.m.Y H:i:s')."\n";
        $txt .= "Soru Sayısı: ".count($messages)."\n";
        $txt .= "-------------------------------------\n";
        foreach ($messages as $message){
            $txt .= "Soru: ".$message->question."\n\n";
            $txt .= "Cevap: ".$message->answer."\n";
            $txt .= "Tarih: ".$message->created_at->format('d.m.Y H:i:s')."\n";
            $txt .= "-------------------------------------\n";
        }
        $fileName = 'travel-mate-chat-backup '.now()->format('d-m-Y_H-i-s').'.txt';
        Storage::disk('local')->put($fileName, $txt);
        $path = Storage::disk('local')->path($fileName);
        return response()->download($path)->deleteFileAfterSend();
    }
}
