<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\QuoteItem;
use App\Models\Invoice;
use App\Models\User;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Engine;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'valid_until' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleMake()
    {
        return $this->belongsTo(CarMake::class, 'vehicle_make_id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(CarModel::class, 'vehicle_model_id');
    }

    public function vehicleEngine()
    {
        return $this->belongsTo(Engine::class, 'vehicle_engine_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function convertedInvoice()
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }
}
