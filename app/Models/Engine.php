<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    protected $fillable = ['code', 'displacement', 'fuel_type', 'cylinder', 'power'];

    public function models()
    {
        return $this->belongsToMany(CarModel::class, 'model_engines', 'engine_id', 'model_id')
                    ->withTimestamps();
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class, 'engine_id');
    }
}
