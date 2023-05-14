<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Answers extends Model
{
    protected $table = 'answers';
    use HasFactory;
    public function getFileUrl(){
        return Storage::url($this->file);
    }
    public function getFileName(){
        return pathinfo($this->file,PATHINFO_BASENAME);
    }
}
