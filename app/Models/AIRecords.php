<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Parsedown;
class AIRecords extends Model
{
    use HasFactory;
    protected $table = 'ai_records';
    protected $parsedown = null;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->parsedown = new Parsedown();
    }


    public function answer(){

        return str_replace('<table>', '<table class="table table-striped table-hover table-bordered">',  $this->parsedown->text($this->answer));
    }
    public function question(){

        return $this->parsedown->text($this->question);
    }
}
