<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'product_id',
        'qty_received',
        'qty_left',
        'landed_unit_cost',
        'received_date',
        'document_type',
        'document_id',
        'grn_id',
    ];

    protected $casts = [
        'qty_received' => 'decimal:3',
        'qty_left' => 'decimal:3',
        'landed_unit_cost' => 'decimal:2',
        'received_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function grn()
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

}