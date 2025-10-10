<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'payment_type',
        'customer_id',
        'supplier_id',
        'payment_method',
        'reference',
        'payment_date',
        'gross_amount',
        'fee_amount',
        'net_amount',
        'allocated_amount',
        'unallocated_amount',
        'notes',
        'user_id',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'gross_amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'allocated_amount' => 'decimal:2',
        'unallocated_amount' => 'decimal:2',
    ];

    // Auto-generate Payment Number (PAY10000+)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }
            
            // Calculate net amount if not set
            if (is_null($payment->net_amount)) {
                $payment->net_amount = $payment->gross_amount - $payment->fee_amount;
            }
            
            // Set unallocated amount initially to net amount
            if (is_null($payment->unallocated_amount)) {
                $payment->unallocated_amount = $payment->net_amount;
            }
        });
    }

    public static function generatePaymentNumber()
    {
        $lastPayment = self::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastPayment ? (int) filter_var($lastPayment->payment_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 10000;
        return 'PAY' . $nextNumber; // PAY10000, PAY10001...
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    // Helper methods
    public function isCustomerPayment()
    {
        return $this->payment_type === 'customer';
    }

    public function isSupplierPayment()
    {
        return $this->payment_type === 'supplier';
    }

    public function hasUnallocatedAmount()
    {
        return $this->unallocated_amount > 0;
    }

    public function isFullyAllocated()
    {
        return $this->unallocated_amount == 0;
    }

    public function getPayer()
    {
        return $this->isCustomerPayment() ? $this->customer : $this->supplier;
    }

    public function getPayerNameAttribute()
    {
        $payer = $this->getPayer();
        return $payer ? $payer->name : 'N/A';
    }
}
