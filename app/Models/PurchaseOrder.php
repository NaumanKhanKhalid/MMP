<?php

namespace App\Models;

use App\Models\PurchaseOrderItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'po_number', 'supplier_id', 'user_id', 'status', 'order_date', 'expected_delivery_date', 
        'received_date', 'notes', 'delivery_address', 'payment_terms', 'subtotal', 'total_discount', 
        'shipping', 'vat', 'vat_enabled', 'grand_total'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'received_date' => 'date',
        'subtotal' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'vat_enabled' => 'boolean',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function goodsReceipts()
    {
        return $this->hasMany(\App\Models\GoodsReceipt::class, 'purchase_order_id');
    }

    /**
     * Generate unique PO number
     */
    public static function generatePONumber(): string
    {
        $lastPO = self::whereNotNull('po_number')
            ->orderByRaw('CAST(SUBSTRING(po_number, 3) AS UNSIGNED) DESC')
            ->first();

        if ($lastPO) {
            $lastNumber = (int) substr($lastPO->po_number, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'PO' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Status helpers
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isPartiallyReceived(): bool
    {
        return $this->status === 'partially_received';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
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
     * Check if all items are fully received
     */
    public function allItemsReceived(): bool
    {
        foreach ($this->items as $item) {
            $receivedQty = \App\Models\GoodsReceiptItem::where('purchase_order_item_id', $item->id)
                ->sum('received_qty');
            
            if ($receivedQty < $item->quantity) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Calculate total amount
     */
    public function calculateTotal(): void
    {
        $this->total_amount = $this->items->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}