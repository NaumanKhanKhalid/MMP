<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCrossRef extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'reference_number',
        'note',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
