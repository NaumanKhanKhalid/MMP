<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'supplier_code',
        'email',
        'phone',
        'address',
        'lead_time',
        'payment_terms',
        'tax_number',
        'bank_details',
        'contact_person',
        'notes',
        'supplier_type',
        'credit_limit',
        'balance',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'lead_time' => 'integer',
    ];

    // Auto-generate supplier code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->supplier_code)) {
                $supplier->supplier_code = self::generateSupplierCode();
            }
        });
    }

    public static function generateSupplierCode()
    {
        $lastSupplier = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastSupplier ? (int) filter_var($lastSupplier->supplier_code, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        return 'SUP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // SUP-0001, SUP-0002...
    }

    // Relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier');
    }

    public function grns()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function supplierInvoices()
    {
        return $this->hasMany(SupplierInvoice::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(SupplierLedger::class)->orderBy('transaction_date', 'desc');
    }

    // Calculate current balance from ledger
    public function calculateBalance()
    {
        $lastEntry = $this->ledgerEntries()->orderBy('id', 'desc')->first();
        return $lastEntry ? $lastEntry->balance : 0;
    }

    // Update balance field
    public function updateBalance()
    {
        $this->update(['balance' => $this->calculateBalance()]);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompany()
    {
        return $this->supplier_type === 'company';
    }

    public function isIndividual()
    {
        return $this->supplier_type === 'individual';
    }

    public function hasCreditLimit()
    {
        return $this->credit_limit > 0;
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - abs($this->balance);
    }

    public function isOverCreditLimit()
    {
        return $this->hasCreditLimit() && abs($this->balance) > $this->credit_limit;
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompanies($query)
    {
        return $query->where('supplier_type', 'company');
    }

    public function scopeIndividuals($query)
    {
        return $query->where('supplier_type', 'individual');
    }
}
