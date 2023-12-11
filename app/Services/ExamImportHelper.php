<?php

namespace App\Services;
use App\Models\BatchExams;
use App\Models\Lesson;

class ExamImportHelper
{
    public $data = [];
    public $batchExamId = null;
    public array $lessonNames = [];
    public array $batchLessons = [];
    public array $lessons = [];
    public array $error = [];
    public array $examResultArray = [];
    public function __construct($data = [],$batchExamId = null)
    {

        $this->data = $data[0];
        if($batchExamId != null){
            $this->batchExamId = $batchExamId;
            BatchExams::find($batchExamId)->lessons()->get()->each(function($lesson){
                $this->batchLessons[] = [
                    "id" => $lesson->id,
                    "name" => $lesson->name
                ];
            });
        }
    }

    public function getLessonNames()
    {
        $r1 = $this->data[0];
        foreach ($r1 as $value){
            if($value != null){
                $this->lessonNames[] = $value;
            }
        }
        return $this->lessonNames;
    }

    public function controlLessonNames(): bool
    {
        if($this->batchExamId != null){
            $error = [];
            $lessons = [];
            foreach ($this->lessonNames as $lessonName){
                $control = false;
                foreach ($this->batchLessons as $batchLesson){
                    if($batchLesson["name"] == $lessonName){
                        $lessons[] = [
                            "id" => $batchLesson["id"],
                            "name" => $batchLesson["name"]
                        ];
                        $control = true;
                    }
                }
                if(!$control){
                    $error["lesson"][] = $lessonName;
                }
            }
            if(count($error) > 0){
                $this->error = $error;
                return false;
            }else{
                $this->lessons = $lessons;
                return true;
            }
        }else{
            $lessons = [];
            $error = [];
            foreach ($this->lessonNames as $lessonName){
                $lesson = Lesson::where('name', 'LIKE','%'.$lessonName.'%')->first();
                if($lesson){
                    $lessons[] = [
                        "id" => $lesson->id,
                        "name" => $lesson->name
                    ];
                }else{
                    $error["lesson"][] = $lessonName;
                }
            }
            if(count($error) > 0){
                $this->error = $error;
                return false;
            }else{
                $this->lessons = $lessons;
                return true;
            }
        }
    }

    public function compileData()
    {
        $this->data = array_slice($this->data, 2);
        $examResultArray = [];
        foreach ($this->data as $row){
            $ar = [
                "student_name" => $row[0],
                "lessons" => []
            ];
            $row = array_chunk(array_slice($row, 1), 2);
            foreach($row as $key => $val){
                $ar["lessons"][] = [
                    "name" => $this->lessons[$key]["name"],
                    "id" => $this->lessons[$key]["id"],
                    "correct_answers" => $val[0],
                    "wrong_answers" => $val[1]
                ];
            }
            $examResultArray[] = $ar;
        }
        $this->examResultArray = $examResultArray;
    }

    public function getArray()
    {
        $this->getLessonNames();
        if($this->controlLessonNames()){
            $this->compileData();
            if($this->controlStudents()){
                return [
                    "data" => $this->examResultArray,
                    "lessons" => $this->lessons,
                ];
            }else{
                return [
                    "unidentified" => $this->error["student"],
                    "data" => $this->examResultArray,
                    "lessons" => $this->lessons,
                ];
            }
        }else{
            return $this->error;
        }
    }

    public function controlStudents()
    {
        $students = [];
        $error = [];
        $examResultArray = [];
        foreach ($this->examResultArray as $examResult){
            $student = \App\Models\Student::where('name', 'LIKE','%'.$examResult['student_name'].'%')->first();
            if($student){
                $students[] = [
                    "id" => $student->id,
                    "name" => $student->name
                ];
                $examResultArray[] = array_merge($examResult, ["student_id" => $student->id]);
            }else{
                $error["student"][] = $examResult;
            }
        }
        if(count($error) > 0){
            $this->error = $error;
            $this->examResultArray = $examResultArray;
            return false;
        }else{
            $this->examResultArray = $examResultArray;
            return true;
        }
    }

}
