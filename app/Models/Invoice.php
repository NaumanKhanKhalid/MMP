<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'vehicle_make',
        'vehicle_model',
        'vehicle_vin',
        'vehicle_reg',
        'vehicle_mileage',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'shipping',
        'vat_amount',
        'grand_total',
        'payment_status',
        'payment_method',
        'amount_paid',
        'balance_due',
        'vat_enabled',
        'vat_rate',
        'vat_inclusive',
        'notes',
        'reference',
        'user_id',
        'quote_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'shipping' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_enabled' => 'boolean',
        'vat_inclusive' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate Invoice Number (MMP10000+)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? (int) filter_var($lastInvoice->invoice_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 10000;
        return 'MMP' . $nextNumber; // MMP10000, MMP10001...
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }

    // Helper methods
    public function isDraft()
    {
        return $this->payment_status === 'draft';
    }

    public function isPosted()
    {
        return $this->payment_status === 'posted';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isCancelled()
    {
        return $this->payment_status === 'cancelled';
    }

    public function isCashSale()
    {
        return is_null($this->customer_id);
    }

    public function getTotalProfitAttribute()
    {
        return $this->items->sum('line_profit');
    }

    public function getGrossProfitPercentageAttribute()
    {
        if ($this->subtotal == 0) return 0;
        return ($this->total_profit / $this->subtotal) * 100;
    }
}
