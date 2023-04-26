<?php

namespace App\Services;

class OpenAIService
{
    protected $baseUrl = 'https://api.openai.com/v1';

    public function generateChatCompletion($messages)
    {
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

        return json_decode($response, true);
    }
}
