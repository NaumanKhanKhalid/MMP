<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $table = 'stock_ledger';
    protected $fillable = ['product_id','document_type','document_id','qty','unit_cost','total_cost','user_id','notes'];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(\App\Models\User::class); }
}
