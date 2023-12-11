<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    public static $allStudents = null;
    protected $table = 'students';

    protected $fillable = [
        'student_number',
        'organization_id',
        'email',
        'name',
        'phone',
        'address',
        'password',
        'identity_number',
        'grade'
    ];

    public function organization()
    {
        $org = $this->belongsTo(Organization::class)->first();
        if($org){
            return $org;
        }else{
            $r = new \stdClass();
            $r->name = 'Bireysel Öğrenci';
            return $r;
        }
    }

    public function getOrganizationNameAttribute()
    {
        return $this->organization ? $this->organization->name : 'Bireysel Öğrenci';
    }
    public function getTeacher(){
        if(StudentTeacher::where('student_id',$this->id)->first()){
            return StudentTeacher::where('student_id',$this->id)->first()->teacher()->first();
        }
        $r = new \stdClass();
        $r->name = 'Bireysel Öğrenci';
        return $r;
    }

    public function lastExam()
    {
        return $this->hasOne(Exams::class, 'student_id', 'id')
            ->whereNull('batch_exam_id')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function lastBatchExam()
    {
        return $this->hasOne(Exams::class, 'student_id', 'id')
            ->whereNotNull('batch_exam_id')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function assignments()
    {
        $assignment_ids = AssignmentStudents::where('student_id', $this->id)->pluck('assignment_id');
        $assignments = Assignments::whereIn('id', $assignment_ids)->orderBy('created_at','asc')->get();
        return $assignments;
    }
    public function assignmentResponse($assignment_id)
    {
        return AssignmentResponses::where('assignment_id', $assignment_id)->where('student_id', $this->id)->first();
    }

    public function questions()
    {
        $questions = Questions::where('student_id', $this->id)->orderBy('created_at','asc')->get();
        return $questions;
    }

    public function className()
    {
        if($class = ClassStudents::where('student_id', $this->id)->first()){
            return $class->class()->first()->name;
        }else{
            return '';
        }
    }
    public static function findMostSimilarUser($text)
    {
        if(self::$allStudents == null){
            self::$allStudents = Student::all();
        }
        $mostSimilar = false;
        $mostSimilarScore = 0;
        foreach (self::$allStudents as $student){
            $score = similar_text($text, $student->name);
            if($score > $mostSimilarScore){
                $mostSimilarScore = $score;
                $mostSimilar = $student;
            }
        }
        return $mostSimilar;

    }

    public function getClassName()
    {
        if($class = ClassStudents::where('student_id', $this->id)->first()){
            return $class->class()->first()->name;
        }else{
            return '';
        }
    }

    public function lastExams($count = 7)
    {
        return Exams::where('student_id', $this->id)->orderBy('id', 'desc')->limit($count)->get();
    }

    public function getParentDetails()
    {
        return Parents::where('student_id', $this->id)->get();
    }
}
