<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayTable extends Model
{
    use HasFactory;
    protected $table = 'day_tables';
    protected $fillable = [
        'time_table_id',
        'day',
    ];
    public function timeTable()
    {
        return $this->belongsTo(TimeTable::class);
    }
    public function hours()
    {
        return $this->hasMany(HourTable::class)->orderBy('index','asc');
    }

}
