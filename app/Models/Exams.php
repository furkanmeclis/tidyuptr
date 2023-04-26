<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
