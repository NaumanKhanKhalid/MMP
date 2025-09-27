<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

       protected $casts = [
        'balance' => 'decimal:2',
    ];
}
