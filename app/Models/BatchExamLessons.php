<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchExamLessons extends Model
{
    use HasFactory;
    protected $table = 'batch_exam_lessons';
    protected $fillable = [
        'batch_exam_id',
        'lesson_id',
    ];
    public function batchExam()
    {
        return BatchExams::find($this->batch_exam_id);
    }
    public function lesson()
    {
        return Lesson::find($this->lesson_id);
    }
}
