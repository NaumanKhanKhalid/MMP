<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'customer_code',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'vehicle_make',
        'vehicle_model',
        'vehicle_vin',
        'vehicle_reg',
        'vehicle_mileage',
        'terms',
        'credit_limit',
        'price_tier',
        'statement_delivery',
        'balance',
        'tax_number',
        'date_of_birth',
        'company_name',
        'contact_person',
        'customer_type',
        'customer_status',
        'marketing_consent',
        'sms_consent',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'balance' => 'decimal:2',
        'date_of_birth' => 'date',
        'marketing_consent' => 'boolean',
        'sms_consent' => 'boolean',
    ];

    // Auto-generate customer code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = self::generateCustomerCode();
            }
        });
    }

    public static function generateCustomerCode()
    {
        $lastCustomer = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastCustomer ? (int) filter_var($lastCustomer->customer_code, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;
        return 'CUST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT); // CUST-0001, CUST-0002...
    }

    // Relationships
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(CustomerLedger::class)->orderBy('transaction_date', 'desc');
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
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
        return $this->customer_status === 'active';
    }

    public function isInactive()
    {
        return $this->customer_status === 'inactive';
    }

    public function isSuspended()
    {
        return $this->customer_status === 'suspended';
    }

    public function isIndividual()
    {
        return $this->customer_type === 'individual';
    }

    public function isBusiness()
    {
        return $this->customer_type === 'business';
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

    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->postal_code) $address .= ', ' . $this->postal_code;
        if ($this->country) $address .= ', ' . $this->country;
        return $address;
    }

    public function getDisplayNameAttribute()
    {
        if ($this->isBusiness() && $this->company_name) {
            return $this->company_name;
        }
        return $this->name;
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        return null;
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('customer_status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('customer_status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('customer_status', 'suspended');
    }

    public function scopeIndividuals($query)
    {
        return $query->where('customer_type', 'individual');
    }

    public function scopeBusinesses($query)
    {
        return $query->where('customer_type', 'business');
    }

    public function scopeWithCreditLimit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    public function scopeOverCreditLimit($query)
    {
        return $query->whereRaw('ABS(balance) > credit_limit AND credit_limit > 0');
    }
}
