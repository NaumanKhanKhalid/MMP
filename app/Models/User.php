<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected $casts = [
        'two_factor_expires_at' => 'datetime',
    ];
}
