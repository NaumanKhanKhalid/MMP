<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFitment extends Model
{
    protected $fillable = ['product_id', 'vehicle_make', 'vehicle_model', 'year_range'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
