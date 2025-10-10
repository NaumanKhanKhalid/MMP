<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'supplier_invoice_id',
        'allocated_amount',
        'allocation_date',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'allocation_date' => 'date',
    ];

    // Relationships
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function supplierInvoice()
    {
        return $this->belongsTo(SupplierInvoice::class);
    }

    // Helper methods
    public function getDocument()
    {
        return $this->invoice ?? $this->supplierInvoice;
    }

    public function getDocumentNumberAttribute()
    {
        if ($this->invoice) {
            return $this->invoice->invoice_number;
        }
        if ($this->supplierInvoice) {
            return $this->supplierInvoice->supplier_invoice_number;
        }
        return 'N/A';
    }
}
