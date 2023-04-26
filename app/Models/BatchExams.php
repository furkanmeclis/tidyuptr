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
}
