<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;
    protected $table = 'students';

    protected $fillable = [
        'student_number',
        'organization_id',
        'email',
        'name',
        'phone_number',
        'address',
        'password'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
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
}
