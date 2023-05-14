<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Assignments extends Model
{
    protected $table = 'assignments';
    use HasFactory;
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->first();
    }
    public function responses()
    {
        return $this->hasMany(AssignmentResponses::class, 'assignment_id', 'id')->get();
    }
    public function students(){
        return $this->hasMany(AssignmentStudents::class, 'assignment_id', 'id')->get();
    }
    public function getFileUrl(){
        return Storage::url($this->file);
    }
    public function getFileName(){
        return pathinfo($this->file,PATHINFO_BASENAME);
    }
    public function getFileExtension(){
        return pathinfo($this->file,PATHINFO_EXTENSION);
    }
    public function dueDate($splitter = ".")
    {
        return Carbon::parse($this->due_date)->format("d" . $splitter . "m" . $splitter . "Y");
    }
}
