<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnalysis extends Model
{
    use HasFactory;
    protected $table = 'exam_analysis';

    public function lessonName()
    {
        return Lesson::where('id', $this->lesson_id)->first()->name;
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }
}
