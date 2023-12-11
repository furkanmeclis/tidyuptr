<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchExams extends Model
{
    use HasFactory;
    protected $table = 'batch_exams';
    public function date($splitter = ".")
    {
        return Carbon::parse($this->created_date)->format("d" . $splitter . "m" . $splitter . "Y");
    }

    public function average()
    {
        $exams = Exams::where('batch_exam_id', $this->id)->get();
        if(count($exams) == 0){
            $std = new \stdClass();
            $std->name = $this->name;
            $std->count = 0;
            $std->correct_answers = 0;
            $std->wrong_answers = 0;
            $std->total = 0;
            $std->empty = true;
            return $std;
        }else{
            $average = 0;
            $correct = 0;
            $wrong = 0;
            $count = 0;
            $total = 0;
            foreach ($exams as $exam){
                $results = ExamResults::where('exam_id', $exam->id)->get();
                $c = 0;
                $w = 0;
                foreach ($results as $result) {
                    $c += $result->correct_answers;
                    $w += $result->wrong_answers;
                }
                $correct += $c;
                $wrong += $w;
                $count++;
            }
            $total = $correct - ($wrong / 4);

            $std = new \stdClass();
            $std->name = $this->name;
            $std->count = count($exams);
            $std->correct_answers = round($correct / $count,2);
            $std->wrong_answers = round($wrong / $count,2);
            $std->total = round($total / $count,2);
            return $std;
        }
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'batch_exam_lessons', 'batch_exam_id', 'lesson_id');
    }

    public function answerKey()
    {
        return $this->hasOne(AnswerKey::class, 'batch_exam_id', 'id');
    }

    public function topics(): array
    {
        $lessons = $this->lessons()->get()->pluck('id')->toArray();
        $topics = Topic::whereIn('lesson_id', $lessons)->get();
        $returnTopics = [];
        foreach ($topics as $topic){
            if(!isset($returnTopics[$topic->lesson_id]))
                $returnTopics[$topic->lesson_id] = [];
            $returnTopics[$topic->lesson_id][] = [
                'id' => $topic->id,
                'name' => $topic->name
            ];
        }
        return $returnTopics;
    }
}
