<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $guarded = [];

    protected $appends = ['url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get full URL for the image
     */
    public function getUrlAttribute()
    {
        if ($this->path) {
            return asset('storage/app/public/' . $this->path);
        }
        return asset('public/assets/images/pos-system/1.jpg'); // Default placeholder
    }

    /**
     * Check if image file exists
     */
    public function exists()
    {
        return $this->path && Storage::disk('public')->exists($this->path);
    }
}
