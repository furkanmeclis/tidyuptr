<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchemes extends Model
{
    use HasFactory;
    protected $table = 'exam_schemes';

    public function lessons()
    {
        $ids = json_decode($this->lesson_ids,true);
        return Lesson::whereIn('id',$ids)->get();
    }
}
