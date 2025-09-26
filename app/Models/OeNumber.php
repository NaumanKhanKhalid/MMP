<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OeNumber extends Model
{
    protected $fillable = ['product_id', 'oe_number'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
