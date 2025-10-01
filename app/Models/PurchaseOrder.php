<?php

namespace App\Models;

use App\Models\PurchaseOrderItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'po_number', 'supplier_id', 'order_date', 'expected_date', 'status', 'total_amount', 'notes', 'user_id'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function goodsReceipts()
    {
        return $this->hasMany(\App\Models\GoodsReceipt::class, 'purchase_order_id');
    }
}