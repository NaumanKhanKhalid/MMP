<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'transaction_type',
        'transaction_id',
        'document_number',
        'transaction_date',
        'debit',
        'credit',
        'balance',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public static function createEntry($supplierId, $type, $transactionId, $documentNumber, $date, $debit, $credit, $notes = null)
    {
        // Get last balance for this supplier
        $lastEntry = self::where('supplier_id', $supplierId)
            ->orderBy('id', 'desc')
            ->first();
        
        $previousBalance = $lastEntry ? $lastEntry->balance : 0;
        $newBalance = $previousBalance + $credit - $debit;

        return self::create([
            'supplier_id' => $supplierId,
            'transaction_type' => $type,
            'transaction_id' => $transactionId,
            'document_number' => $documentNumber,
            'transaction_date' => $date,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $newBalance,
            'notes' => $notes,
            'user_id' => auth()->id(),
        ]);
    }
}
