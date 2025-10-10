<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'invoice_item_id',
        'product_id',
        'product_sku',
        'product_name',
        'product_barcode',
        'quantity_returned',
        'unit_price',
        'line_total',
        'return_reason',
        'condition',
        'restock',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'restock' => 'boolean',
    ];

    public function productReturn(): BelongsTo
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getConditionBadgeAttribute(): string
    {
        return match($this->condition) {
            'new' => 'success',
            'used' => 'info',
            'damaged' => 'warning',
            'defective' => 'danger',
            default => 'secondary'
        };
    }

    public function getConditionTextAttribute(): string
    {
        return match($this->condition) {
            'new' => 'New',
            'used' => 'Used',
            'damaged' => 'Damaged',
            'defective' => 'Defective',
            default => 'Unknown'
        };
    }
}