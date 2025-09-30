<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    protected $casts = [
        'balance' => 'decimal:2',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier');
    }

    public function grns()
    {
        return $this->hasMany(GoodsReceipt::class);
    }
}
