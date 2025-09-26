<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrossReference extends Model
{
    protected $fillable = ['product_id', 'reference_number', 'reference_brand'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
