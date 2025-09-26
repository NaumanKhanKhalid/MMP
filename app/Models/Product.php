<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'barcode', 'name', 'brand_id', 'category_id',
        'unit', 'reorder_level', 'is_special_order',
        'default_purchase_price', 'default_sale_price'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class);
    }

    public function oeNumbers()
    {
        return $this->hasMany(OeNumber::class);
    }

    public function crossReferences()
    {
        return $this->hasMany(CrossReference::class);
    }

    public function stockBatches()
    {
        return $this->hasMany(StockBatch::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
