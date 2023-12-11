<?php

namespace App\Services;
use \App\Models\AIRecords;
use Carbon\Carbon;

class OpenAIService
{
    protected string $baseUrl = 'https://api.openai.com/v1';
    public $response = null;
    public $error = false;
    public function generateChatCompletion($messages): OpenAIService
    {
        try {
            $url = $this->baseUrl . '/chat/completions';
            $data = [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages
            ];
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('OPENAI_API_KEY')
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $this->response = json_decode($response, true);
            return $this;
        }catch(\Exception $exception){
            $this->error = $exception->getMessage();
            return $this;
        }
    }

    public function send($question){
        $student = auth('student')->user();
        $today = Carbon::today();
        $count = AIRecords::where('student_id',$student->id)
            ->whereRaw('DATE(created_at) = ?', [$today])->count();
        if($count <= env('AI_DAILY_MAX_QUESTION_COUNT')){
            $message = [
                [
                    'content' => "Ek Olarak Cevabının en başında bir başlık tarzı bir şey ekle h3 etiketinde bu başlık verilen cevapla ilgili olsun.Soruları yalnızca markdown formatında cevapla etkili bir şekilde kullan tablo oluştur liste oluştur
                    .Sorulan soruların eğitimle ilgili olmasına dikkat et.
                    Öğrenci Adı: $student->name. MATHJAXSİ mutlaka kullan!!!.
                    ÖNEMLİ BİLGİLERİ KALIN YAZ MARKDOWN FORMATINDA.Matematiksel ifadeleri katex kütüpahenesine uygun bir şekilde yaz",
                    'role' => 'system',
                ],
                [
                    'content' => "Soru:".$question,
                    'role' => 'user',
                ]
            ];
            $this->generateChatCompletion($message);
            if($this->error == false){
                $response = $this->response["choices"][0]["message"]["content"];
                $aiRecord = new AIRecords();
                $aiRecord->student_id = $student->id;
                $aiRecord->question = $question;
                $aiRecord->answer = $response;
                try {
                    if($aiRecord->save()){
                        return [
                            'status' => true,
                            'message' => $response
                        ];
                    }else{
                        return [
                            'status' => false,
                            'message' => 'Bir hata oluştu lütfen tekrar deneyin.'
                        ];
                    }
                }catch(\Exception $exception){
                    return [
                        'status' => false,
                        'message' => $exception->getMessage()
                    ];
                }
            }else{
                return [
                    'status' => false,
                    'message' => $this->error
                ];
            }
        }else{
            return [
                'status' => false,
                'message' => 'Günlük maksimum soru sayısına ulaştınız.'
            ];
        }
    }
}
