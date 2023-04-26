<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationTeacher extends Model
{
    use HasFactory;
    protected $table = 'organization_teacher';

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public static function getTableName()
    {
        return (new static())->table;
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
