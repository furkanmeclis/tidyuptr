<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organization extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'organizations';
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'active',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];
    public function getAuthIdentifierName()
    {
        return 'email';
    }
}
