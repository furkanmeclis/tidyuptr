<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Exams extends Model
{
    use HasFactory;
    protected $table = 'exams';

    public function results()
    {
        return ExamResults::where('exam_id', $this->id)->get();
    }

    public function student()
    {
        return Student::where('id', $this->student_id)->first();
    }
    public function date($splitter = ".")
    {
        return Carbon::parse($this->created_date)->format("d" . $splitter . "m" . $splitter . "Y");
    }
    public function score($custom = false)
    {
        $correct_answers = 0;
        $wrong_answers = 0;
        $empty_answers = 0;
        $lessons = [];
        foreach ($this->results() as $result) {
            $correct_answers += $result->correct_answers;
            $wrong_answers += $result->wrong_answers;
            $empty_answers += $result->empty_answers;
            $lessons[$result->lesson_id] = $result->correct_answers - ($result->wrong_answers / 4);
        }
        $questionCount = ($correct_answers + $wrong_answers + $empty_answers == 0) ? 1 : ($correct_answers + $wrong_answers + $empty_answers);
        $name = $this->batchName();
        $total = $correct_answers - ($wrong_answers / 4);
        $returnData = new stdClass();
        $returnData->correct_answers = $correct_answers;
        $returnData->wrong_answers = $wrong_answers;
        $returnData->total = $total;
        $returnData->lessons = $lessons;
        $returnData->name = $name ? $name : "Bireysel Sınav";
        $returnData->date = $this->created_at->format("d.m.Y");
        $returnData->height = str_replace(",", ".", round($total * 100 / $questionCount,2));
        return $returnData;
    }

    public function getWrongQuestions()
    {
        if($this->batch_exam_id !== null) {
            return ExamAnalysis::where('exam_id', $this->id)->where('status',"wrong")->get();
        }else{
            return false;
        }
    }

    public function batchName()
    {
        if($this->batch_exam_id !== null) {
            return BatchExams::where('id', $this->batch_exam_id)->first()->name ?? false;
        }else{
            return false;
        }
    }

    public function topicAnalysis()
    {
        $analysis = ExamAnalysis::where('exam_id', $this->id)->get();
        $data = [];

        foreach ($analysis as $item) {
            $lessonId = $item->lesson_id;
            $topicId = $item->topic_id;
            $status = $item->status;

            if (!isset($data[$lessonId])) {
                $lessonName = Lesson::where('id', $lessonId)->value('name');
                $data[$lessonId] = [
                    "name" => $lessonName,
                    "topics" => [],
                ];
            }

            if (!isset($data[$lessonId]["topics"][$topicId])) {
                $topicName = $item->topic->name ?? "Konu Bulunamadı";
                $data[$lessonId]["topics"][$topicId] = [
                    "name" => $topicName,
                    "correct" => 0,
                    "wrong" => 0,
                    "empty" => 0,
                ];
            }
            if ($status === "correct") {
                $data[$lessonId]["topics"][$topicId]["correct"]++;
            } elseif ($status === "wrong") {
                $data[$lessonId]["topics"][$topicId]["wrong"]++;
            } elseif ($status === "empty") {
                $data[$lessonId]["topics"][$topicId]["empty"]++;
            }
        }
        foreach ($data as $lessonId => $lesson) {
            $totalCorrect = 0;
            $totalWrong = 0;
            $totalEmpty = 0;
            foreach ($lesson["topics"] as $topicId => $topic) {
                $totalCorrect += $topic["correct"];
                $totalWrong += $topic["wrong"];
                $totalEmpty += $topic["empty"];
            }
            $data[$lessonId]["total"] = [
                "correct" => $totalCorrect,
                "wrong" => $totalWrong,
                "empty" => $totalEmpty,
            ];
        }

        return count($data) > 0 ? json_decode(json_encode($data), false) : false;
    }

}
