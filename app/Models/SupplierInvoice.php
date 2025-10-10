<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'grn_id',
        'supplier_invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'vat_amount',
        'total_amount',
        'paid_amount',
        'balance_due',
        'status',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // Set balance due initially to total amount
            if (is_null($invoice->balance_due)) {
                $invoice->balance_due = $invoice->total_amount;
            }
        });

        static::created(function ($invoice) {
            // Create ledger entry when invoice is posted
            if ($invoice->status === 'posted') {
                SupplierLedger::createEntry(
                    $invoice->supplier_id,
                    'supplier_invoice',
                    $invoice->id,
                    $invoice->supplier_invoice_number,
                    $invoice->invoice_date,
                    0, // debit
                    $invoice->total_amount, // credit (we owe them)
                    'Supplier invoice posted'
                );
            }
        });
    }

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function grn()
    {
        return $this->belongsTo(GoodsReceipt::class, 'grn_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    // Helper methods
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPartiallyPaid()
    {
        return $this->paid_amount > 0 && $this->balance_due > 0;
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->balance_due > 0;
    }
}
