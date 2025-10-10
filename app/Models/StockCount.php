<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'count_number',
        'count_name',
        'count_date',
        'status',
        'filters',
        'category_id',
        'brand_id',
        'bin_location',
        'total_products',
        'counted_products',
        'products_with_variance',
        'total_variance_value',
        'user_id',
        'posted_by',
        'posted_at',
        'notes',
    ];

    protected $casts = [
        'count_date' => 'date',
        'filters' => 'array',
        'total_variance_value' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    // Auto-generate count number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($count) {
            if (empty($count->count_number)) {
                $count->count_number = self::generateCountNumber();
            }
        });
    }

    public static function generateCountNumber()
    {
        $lastCount = self::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastCount ? (int) filter_var($lastCount->count_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 10000;
        return 'SC' . $nextNumber; // SC10000, SC10001...
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function items()
    {
        return $this->hasMany(StockCountItem::class);
    }

    public function adjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    // Helper methods
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPosted()
    {
        return $this->status === 'posted';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canEdit()
    {
        return in_array($this->status, ['draft', 'in_progress']);
    }

    public function canPost()
    {
        return $this->status === 'completed';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_products == 0) return 0;
        return round(($this->counted_products / $this->total_products) * 100, 2);
    }

    public function hasVariances()
    {
        return $this->products_with_variance > 0;
    }
}

