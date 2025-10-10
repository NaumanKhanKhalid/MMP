<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'invoice_id',
        'customer_id',
        'user_id',
        'status',
        'return_type',
        'reason',
        'notes',
        'total_amount',
        'refund_amount',
        'refund_method',
        'stock_handling_type',
        'restock_items',
        'returned_at',
        'processed_at',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'processed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'restock_items' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (empty($return->return_number)) {
                $return->return_number = 'RT' . str_pad(ProductReturn::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function creditNote(): HasMany
    {
        return $this->hasMany(CreditNote::class, 'return_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    public function getReturnTypeBadgeAttribute(): string
    {
        return match($this->return_type) {
            'full' => 'danger',
            'partial' => 'warning',
            'exchange' => 'info',
            default => 'secondary'
        };
    }

    public function getRefundMethodBadgeAttribute(): string
    {
        return match($this->refund_method) {
            'cash' => 'success',
            'store_credit' => 'primary',
            'exchange' => 'info',
            'bank_transfer' => 'secondary',
            default => 'secondary'
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeProcessed(): bool
    {
        return $this->status === 'approved';
    }
}