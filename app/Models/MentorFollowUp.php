<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MentorFollowUp extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'teacher_id',
        'file',
        'note',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->first();
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->first();
    }
    public function getFileUrl()
    {
        if(!$this->file){
            return "#";
        }
        return Storage::url($this->file);
    }
    public function getDate($splitter = "."){
        return Carbon::parse($this->created_at)->format("Y" . $splitter . "m" . $splitter . "d");
    }
}
