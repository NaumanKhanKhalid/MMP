<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_id',
        'product_id',
        'product_sku',
        'product_name',
        'product_barcode',
        'quantity_used',
        'unit_price',
        'line_total',
        'reserved',
        'consumed',
        'reserved_at',
        'consumed_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity_used' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'reserved' => 'boolean',
        'consumed' => 'boolean',
        'reserved_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Methods
    public function getStatusAttribute()
    {
        if ($this->consumed) {
            return 'consumed';
        } elseif ($this->reserved) {
            return 'reserved';
        } else {
            return 'pending';
        }
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'consumed' => 'success',
            'reserved' => 'warning',
            'pending' => 'secondary',
            default => 'secondary'
        };
    }

    // Stock Management Methods
    public function reserveFromStock()
    {
        if ($this->reserved || !$this->product_id) {
            return false;
        }

        $product = $this->product;
        if (!$product) {
            return false;
        }

        // Check if enough stock available (allow negative stock)
        $this->update([
            'reserved' => true,
            'reserved_at' => now(),
        ]);

        return true;
    }

    public function consumeFromStock()
    {
        if ($this->consumed || !$this->product_id) {
            return false;
        }

        $product = $this->product;
        if (!$product) {
            return false;
        }

        // Consume from stock (FIFO)
        $this->update([
            'consumed' => true,
            'consumed_at' => now(),
        ]);

        // Update product stock
        $product->decrement('on_hand', $this->quantity_used);

        // Create stock ledger entry
        StockLedger::create([
            'product_id' => $this->product_id,
            'document_type' => 'job_card',
            'document_id' => $this->job_card_id,
            'qty' => -$this->quantity_used, // Negative for consumption
            'unit_cost' => $this->unit_price,
            'total_cost' => $this->line_total,
            'user_id' => auth()->id(),
            'notes' => 'Job Card consumption - ' . $this->jobCard->job_card_number,
        ]);

        return true;
    }

    public function releaseReservation()
    {
        if (!$this->reserved || $this->consumed) {
            return false;
        }

        $this->update([
            'reserved' => false,
            'reserved_at' => null,
        ]);

        return true;
    }

    // Calculate line total
    public function calculateLineTotal()
    {
        $lineTotal = $this->quantity_used * $this->unit_price;
        $this->update(['line_total' => $lineTotal]);
        return $lineTotal;
    }

    // Scope Methods
    public function scopeReserved($query)
    {
        return $query->where('reserved', true)->where('consumed', false);
    }

    public function scopeConsumed($query)
    {
        return $query->where('consumed', true);
    }

    public function scopePending($query)
    {
        return $query->where('reserved', false)->where('consumed', false);
    }
}