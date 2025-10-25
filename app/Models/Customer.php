<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

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
        'customer_category',
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
        $maxRetries = 5;
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                // Use database transaction to ensure atomicity
                return \DB::transaction(function () {
                    // Get the current max number atomically
                    $maxNumber = \DB::table('customers')
                        ->whereNotNull('customer_code')
                        ->where('customer_code', 'REGEXP', '^CUST-[0-9]+$')
                        ->selectRaw('MAX(CAST(SUBSTRING(customer_code, 6) AS UNSIGNED)) as max_num')
                        ->value('max_num');
                    
                    $nextNumber = ($maxNumber ?? 0) + 1;
                    $customerCode = 'CUST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                    
                    // Double-check that this code doesn't exist (extra safety)
                    $exists = \DB::table('customers')
                        ->where('customer_code', $customerCode)
                        ->exists();
                    
                    if ($exists) {
                        throw new \Exception('Customer code already exists, retrying...');
                    }
                    
                    return $customerCode;
                });
            } catch (\Exception $e) {
                $attempt++;
                if ($attempt >= $maxRetries) {
                    throw new \Exception('Failed to generate unique customer code after ' . $maxRetries . ' attempts');
                }
                // Small random delay to reduce collision probability
                usleep(rand(10000, 50000)); // 10-50ms
            }
        }
        
        throw new \Exception('Failed to generate customer code');
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

    public function vehicles()
    {
        return $this->hasMany(CustomerVehicle::class)->orderBy('is_primary', 'desc');
    }

    public function primaryVehicle()
    {
        return $this->hasOne(CustomerVehicle::class)->where('is_primary', true);
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



    // Removed old methods - now using customer_category instead of customer_type for individual/business

    public function hasCreditLimit()
    {
        return $this->credit_limit > 0;
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - abs((float) $this->balance);
    }

    public function isOverCreditLimit()
    {
        return $this->hasCreditLimit() && abs((float) $this->balance) > $this->credit_limit;
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
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
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

    

    public function scopeIndividuals($query)
    {
        return $query->where('customer_category', 'individual');
    }

    public function scopeBusinesses($query)
    {
        return $query->where('customer_category', 'business');
    }

    public function scopeCashCustomers($query)
    {
        return $query->where('customer_type', 'cash');
    }

    public function scopeCreditCustomers($query)
    {
        return $query->where('customer_type', 'credit');
    }

    public function scopeWithCreditLimit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    public function scopeOverCreditLimit($query)
    {
        return $query->whereRaw('ABS(balance) > credit_limit AND credit_limit > 0');
    }

    // Customer Type Methods
    public function isCashCustomer()
    {
        return $this->customer_type === 'cash';
    }

    public function isCreditCustomer()
    {
        return $this->customer_type === 'credit';
    }

    public function isIndividual()
    {
        return $this->customer_category === 'individual';
    }

    public function isBusiness()
    {
        return $this->customer_category === 'business';
    }

    // Payment terms based on customer type
    public function getDefaultPaymentTerms()
    {
        return $this->customer_type === 'cash' ? 'cash' : 'credit';
    }

    // Credit limit validation for credit customers
    public function canMakeCreditPurchase($amount)
    {
        if ($this->isCashCustomer()) {
            return false; // Cash customers cannot make credit purchases
        }

        if ($this->isCreditCustomer()) {
            $currentBalance = abs((float) $this->balance);
            return ($currentBalance + $amount) <= $this->credit_limit;
        }

        return true;
    }

    // Get customer type display name
    public function getCustomerTypeDisplayAttribute()
    {
        return match($this->customer_type) {
            'cash' => 'Cash Customer',
            'credit' => 'Credit Customer',
            default => 'Unknown'
        };
    }

    // Get customer category display name
    public function getCustomerCategoryDisplayAttribute()
    {
        return match($this->customer_category) {
            'individual' => 'Individual',
            'business' => 'Business',
            default => 'Unknown'
        };
    }
}