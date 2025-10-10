<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_sku',
        'product_name',
        'product_barcode',
        'unit_price',
        'quantity',
        'discount_amount',
        'discount_percentage',
        'line_total',
        'unit_cost',
        'line_cost',
        'line_profit',
        'stock_batch_id',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'decimal:3',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'line_total' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'line_cost' => 'decimal:2',
        'line_profit' => 'decimal:2',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockBatch()
    {
        return $this->belongsTo(StockBatch::class);
    }

    // Helper methods
    public function getProfitPercentageAttribute()
    {
        if ($this->line_total == 0) return 0;
        return ($this->line_profit / $this->line_total) * 100;
    }

    // Calculate line total before VAT
    public function calculateLineTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $discount = $this->discount_amount > 0 ? $this->discount_amount : ($subtotal * $this->discount_percentage / 100);
        return $subtotal - $discount;
    }

    // Calculate profit using FIFO cost
    public function calculateProfit($fifoCost)
    {
        $this->unit_cost = (float) $fifoCost;
        $this->line_cost = $this->quantity * (float) $fifoCost;
        $this->line_profit = $this->line_total - $this->line_cost;
    }
}