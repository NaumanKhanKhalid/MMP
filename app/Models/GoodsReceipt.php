<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'grn_number',
        'received_date',
        'invoice_number',
        'total_amount',
        'status',
        'notes',
        'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batches()
    {
        return $this->hasMany(StockBatch::class, 'grn_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


