<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Avatar;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'teachers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'max_students'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getQuota(){
         $students = StudentTeacher::where('teacher_id',$this->id)->count();
         return $this->max_students - $students;
    }
    public function getStudentsCount(){
        $students = StudentTeacher::where('teacher_id',$this->id)->count();
        return $students;
    }
    public function getAvaible(){
        $students = StudentTeacher::where('teacher_id',$this->id)->count();
        return $students < $this->max_students;
    }
}
