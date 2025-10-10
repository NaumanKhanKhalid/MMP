<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCardLabour extends Model
{
    use HasFactory;

    protected $table = 'job_card_labour'; // Singular table name

    protected $fillable = [
        'job_card_id',
        'labour_description',
        'detailed_description',
        'hours_worked',
        'hourly_rate',
        'total_amount',
        'labour_type',
        'technician_id',
        'technician_name',
        'status',
        'started_at',
        'completed_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'hours_worked' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'primary',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => 'Unknown'
        };
    }

    public function getLabourTypeBadgeAttribute()
    {
        return match($this->labour_type) {
            'diagnostic' => 'info',
            'repair' => 'primary',
            'maintenance' => 'success',
            'installation' => 'warning',
            'other' => 'secondary',
            default => 'secondary'
        };
    }

    public function getLabourTypeTextAttribute()
    {
        return match($this->labour_type) {
            'diagnostic' => 'Diagnostic',
            'repair' => 'Repair',
            'maintenance' => 'Maintenance',
            'installation' => 'Installation',
            'other' => 'Other',
            default => 'Unknown'
        };
    }

    public function getTechnicianNameAttribute()
    {
        return $this->technician ? $this->technician->name : $this->attributes['technician_name'];
    }

    // Status Management Methods
    public function startWork()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function completeWork()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    // Calculate total amount
    public function calculateTotalAmount()
    {
        $totalAmount = $this->hours_worked * $this->hourly_rate;
        $this->update(['total_amount' => $totalAmount]);
        return $totalAmount;
    }

    // Scope Methods
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForTechnician($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    public function scopeByLabourType($query, $labourType)
    {
        return $query->where('labour_type', $labourType);
    }
}