<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_note_id',
        'invoice_item_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_description',
        'qty_sold',
        'unit_price',
        'discount',
        'discount_percentage',
        'line_total',
        'qty_returned',
        'return_unit_price',
        'return_discount',
        'return_line_total',
        'stock_handling',
        'batch_id',
        'restocked',
        'restocked_at',
        'vat_amount',
        'vat_rate',
    ];

    protected $casts = [
        'qty_sold' => 'integer',
        'qty_returned' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'line_total' => 'decimal:2',
        'return_unit_price' => 'decimal:2',
        'return_discount' => 'decimal:2',
        'return_line_total' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'restocked' => 'boolean',
        'restocked_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class);
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StockBatch::class);
    }

    /**
     * Stock handling helpers
     */
    public function isRestock(): bool
    {
        return $this->stock_handling === 'restock';
    }

    public function isWriteOff(): bool
    {
        return $this->stock_handling === 'write_off';
    }

    public function isNoStock(): bool
    {
        return $this->stock_handling === 'no_stock';
    }

    public function getStockHandlingTextAttribute(): string
    {
        return match($this->stock_handling) {
            'restock' => 'Restock into original batches',
            'write_off' => 'Write off (damaged)',
            'no_stock' => 'No stock (credit only)',
            default => 'Unknown'
        };
    }

    /**
     * Calculate return line total
     */
    public function calculateReturnTotal(): void
    {
        $this->return_line_total = ($this->return_unit_price * $this->qty_returned) - $this->return_discount;
    }
}
