<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
    protected $fillable = [
        'customer_id',
        'make_id',
        'model_id',
        'engine_id',
        'engine',
        'registration_number',
        'vin_number',
        'year',
        'color',
        'mileage',
        'notes',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class, 'engine_id');
    }

    // Helper method to get full vehicle name
    public function getFullNameAttribute()
    {
        $parts = [];
        
        if ($this->make) {
            $parts[] = $this->make->name;
        }
        
        if ($this->model) {
            $parts[] = $this->model->name;
        }
        
        if ($this->year) {
            $parts[] = "({$this->year})";
        }
        
        return implode(' ', $parts) ?: 'Unknown Vehicle';
    }
}
