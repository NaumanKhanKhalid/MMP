<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'grn_number',
        'received_date',
        'invoice_number',
        'total_amount',
        'status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Generate unique GRN number
     */
    public static function generateGRNNumber(): string
    {
        $lastGRN = self::whereNotNull('grn_number')
            ->orderByRaw('CAST(SUBSTRING(grn_number, 5) AS UNSIGNED) DESC')
            ->first();

        if ($lastGRN) {
            $lastNumber = (int) substr($lastGRN->grn_number, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'GRN' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(StockBatch::class, 'grn_id');
    }

    /**
     * Status helpers
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Calculate total amount
     */
    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum('line_total');
    }
}


