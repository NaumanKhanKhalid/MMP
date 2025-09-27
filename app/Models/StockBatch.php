<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    protected $fillable = ['product_id','batch_code','qty_received','qty_left','landed_unit_cost','received_date','grn_id'];

    public function product() { return $this->belongsTo(Product::class); }
}
