<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $table = 'stock_ledger';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
