<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCountItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_count_id',
        'product_id',
        'system_qty',
        'counted_qty',
        'variance_qty',
        'unit_cost',
        'variance_value',
        'is_counted',
        'notes',
    ];

    protected $casts = [
        'system_qty' => 'decimal:4',
        'counted_qty' => 'decimal:4',
        'variance_qty' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'variance_value' => 'decimal:4',
        'is_counted' => 'boolean',
    ];

    // Relationships
    public function stockCount()
    {
        return $this->belongsTo(StockCount::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function hasVariance()
    {
        return $this->variance_qty != 0;
    }

    public function isOverage()
    {
        return $this->variance_qty > 0;
    }

    public function isShortage()
    {
        return $this->variance_qty < 0;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->system_qty == 0) return 0;
        return round(($this->variance_qty / $this->system_qty) * 100, 2);
    }

    // Calculate variance when counted_qty is set
    public function calculateVariance()
    {
        if ($this->counted_qty !== null) {
            $this->variance_qty = $this->counted_qty - $this->system_qty;
            $this->variance_value = $this->variance_qty * $this->unit_cost;
            $this->is_counted = true;
        }
    }
}

