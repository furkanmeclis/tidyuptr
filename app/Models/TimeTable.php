<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;
    protected $table = 'time_tables';
    protected $fillable = [
        'student_id',
    ];
    public function days()
    {
        return $this->hasMany(DayTable::class);
    }
}
