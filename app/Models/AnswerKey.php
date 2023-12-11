<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerKey extends Model
{
    use HasFactory;
    protected $table = 'answer_keys';

    public function getAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AnswerValues::class, 'answer_key_id', 'id');
    }

    public function getAnswerArray(): array
    {
        $answers = $this->getAnswers()->orderBy('question_number','asc')->get();
        $answerArray = [];
        foreach ($answers as $answer) {
            $answerArray["A"][$answer->lesson_id][$answer->question_number] = [
                "answer" =>$answer->answer_value,
                "topic" => $answer->topic,
            ];
            $answerArray["B"][$answer->lesson_id][$answer->b_number] = [
                "answer" =>$answer->answer_value,
                "topic" => $answer->topic,
            ];
        }
        return $answerArray;
    }
}
