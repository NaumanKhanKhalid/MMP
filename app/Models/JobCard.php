<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card_number',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'assigned_technician_id',
        'vehicle_make',
        'vehicle_model',
        'vehicle_vin',
        'vehicle_registration',
        'vehicle_mileage',
        'engine_code',
        'vehicle_year',
        'job_description',
        'notes',
        'customer_complaint',
        'expected_completion_date',
        'status',
        'booked_at',
        'started_at',
        'completed_at',
        'parts_total',
        'labour_total',
        'grand_total',
        'final_invoice_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'booked_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'vehicle_year' => 'integer',
        'parts_total' => 'decimal:2',
        'labour_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // Auto-generate Job Card Number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobCard) {
            if (empty($jobCard->job_card_number)) {
                $jobCard->job_card_number = self::generateJobCardNumber();
            }
        });
    }

    public static function generateJobCardNumber()
    {
        $lastJobCard = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastJobCard ? (int) filter_var($lastJobCard->job_card_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 10000;
        return 'WS' . $nextNumber; // WS10000, WS10001...
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function items()
    {
        return $this->hasMany(JobCardItem::class);
    }

    public function labour()
    {
        return $this->hasMany(JobCardLabour::class);
    }

    public function finalInvoice()
    {
        return $this->belongsTo(Invoice::class, 'final_invoice_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'booked' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'booked' => 'Booked In',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }

    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->name : $this->attributes['customer_name'];
    }

    public function getVehicleInfoAttribute()
    {
        $info = [];
        if ($this->vehicle_make) $info[] = $this->vehicle_make;
        if ($this->vehicle_model) $info[] = $this->vehicle_model;
        if ($this->vehicle_year) $info[] = $this->vehicle_year;
        if ($this->vehicle_registration) $info[] = $this->vehicle_registration;
        
        return implode(' ', $info);
    }

    // Status Management Methods
    public function markAsBooked()
    {
        $this->update([
            'status' => 'booked',
            'booked_at' => now(),
        ]);
    }

    public function markAsInProgress()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    // Calculate Totals
    public function calculateTotals()
    {
        $partsTotal = $this->items()->sum('line_total');
        $labourTotal = $this->labour()->sum('total_amount');
        $grandTotal = $partsTotal + $labourTotal;

        $this->update([
            'parts_total' => $partsTotal,
            'labour_total' => $labourTotal,
            'grand_total' => $grandTotal,
        ]);

        return [
            'parts_total' => $partsTotal,
            'labour_total' => $labourTotal,
            'grand_total' => $grandTotal,
        ];
    }

    // Check if can be converted to invoice
    public function canConvertToInvoice()
    {
        return $this->status === 'completed' && !$this->final_invoice_id;
    }

    // Scope Methods
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}