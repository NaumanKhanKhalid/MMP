<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'product_id',
        'product_name',
        'product_sku',
        'ordered_qty',
        'received_qty',
        'rejected_qty',
        'unit_cost',
        'line_total',
        'batch_number',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'ordered_qty' => 'integer',
        'received_qty' => 'integer',
        'rejected_qty' => 'integer',
        'unit_cost' => 'decimal:2',
        'line_total' => 'decimal:2',
        'expiry_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Status helpers
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReceived(): bool
    {
        return $this->status === 'received';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Calculate line total
     */
    public function calculateLineTotal(): void
    {
        $this->line_total = $this->unit_cost * $this->received_qty;
    }

    /**
     * Generate batch number
     */
    public static function generateBatchNumber(): string
    {
        $lastBatch = self::whereNotNull('batch_number')
            ->orderByRaw('CAST(SUBSTRING(batch_number, 7) AS UNSIGNED) DESC')
            ->first();

        if ($lastBatch) {
            $lastNumber = (int) substr($lastBatch->batch_number, 7);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'BATCH-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
