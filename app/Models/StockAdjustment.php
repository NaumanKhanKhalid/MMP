<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'adjustment_number',
        'adjustment_type',
        'product_id',
        'stock_count_id',
        'adjustment_date',
        'quantity_before',
        'adjustment_qty',
        'quantity_after',
        'reason',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'adjustment_date' => 'date',
        'quantity_before' => 'decimal:4',
        'adjustment_qty' => 'decimal:4',
        'quantity_after' => 'decimal:4',
    ];

    // Auto-generate adjustment number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adjustment) {
            if (empty($adjustment->adjustment_number)) {
                $adjustment->adjustment_number = self::generateAdjustmentNumber();
            }
        });
    }

    public static function generateAdjustmentNumber()
    {
        $lastAdjustment = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastAdjustment ? (int) filter_var($lastAdjustment->adjustment_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 10000;
        return 'ADJ' . $nextNumber; // ADJ10000, ADJ10001...
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockCount()
    {
        return $this->belongsTo(StockCount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isIncrease()
    {
        return $this->adjustment_qty > 0;
    }

    public function isDecrease()
    {
        return $this->adjustment_qty < 0;
    }

    public function getAbsoluteAdjustmentAttribute()
    {
        return abs($this->adjustment_qty);
    }

    public function getAdjustmentTypeLabel()
    {
        return ucfirst(str_replace('_', ' ', $this->adjustment_type));
    }
}

