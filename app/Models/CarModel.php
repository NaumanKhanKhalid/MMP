<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $guarded = [];

    public function make()
    {
        return $this->belongsTo(CarMake::class, 'make_id');
    }

    public function engines()
    {
        return $this->belongsToMany(Engine::class, 'model_engines', 'model_id', 'engine_id')
                    ->withTimestamps();
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class, 'model_id');
    }
}
