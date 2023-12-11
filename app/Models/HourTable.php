<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourTable extends Model
{
    use HasFactory;
    protected $table = 'hour_tables';

    public function lesson()
    {
        $lesson_id = DayHours::where('hour_table_id', $this->id)->first()->lesson_id;
        return Lesson::find($lesson_id);
    }

    public function hour()
    {
        return DayHours::where('hour_table_id', $this->id)->first();
    }
}
