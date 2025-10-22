<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'credit_note_number',
        'credit_note_date',
        'return_id',
        'invoice_id',
        'linked_invoice_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'vehicle_make',
        'vehicle_model',
        'vehicle_reg',
        'vehicle_vin',
        'vehicle_mileage',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'shipping',
        'vat_amount',
        'grand_total',
        'vat_enabled',
        'vat_rate',
        'vat_inclusive',
        'reason_for_return',
        'refund_method',
        'handling_fee',
        'customer_type',
        'apply_to_account',
        'status',
        'notes',
        'reference',
        'user_id',
        'posted_by',
        'issued_at',
        'applied_at',
        'posted_at',
    ];

    protected $casts = [
        'credit_note_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'shipping' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'vat_enabled' => 'boolean',
        'vat_rate' => 'decimal:2',
        'vat_inclusive' => 'boolean',
        'handling_fee' => 'decimal:2',
        'apply_to_account' => 'boolean',
        'issued_at' => 'datetime',
        'applied_at' => 'datetime',
        'posted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($creditNote) {
            if (empty($creditNote->credit_note_number)) {
                $creditNote->credit_note_number = self::generateCreditNoteNumber();
            }
            if (empty($creditNote->credit_note_date)) {
                $creditNote->credit_note_date = now();
            }
        });
    }

    /**
     * Generate unique credit note number
     */
    public static function generateCreditNoteNumber(): string
    {
        $lastCreditNote = self::whereNotNull('credit_note_number')
            ->orderByRaw('CAST(SUBSTRING(credit_note_number, 3) AS UNSIGNED) DESC')
            ->first();

        if ($lastCreditNote) {
            $lastNumber = (int) substr($lastCreditNote->credit_note_number, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'CN' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function productReturn(): BelongsTo
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function postedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class);
    }

    /**
     * Status helpers
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'posted' => 'success',
            'voided' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'posted' => 'Posted',
            'voided' => 'Voided',
            default => 'Unknown'
        };
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted';
    }

    public function isVoided(): bool
    {
        return $this->status === 'voided';
    }

    public function canBePosted(): bool
    {
        return $this->status === 'draft';
    }

    public function canBeVoided(): bool
    {
        return $this->status === 'posted';
    }

    /**
     * Calculate totals
     */
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('return_line_total');
        $this->grand_total = $this->subtotal + $this->vat_amount + $this->shipping - $this->discount_amount - $this->handling_fee;
    }
}