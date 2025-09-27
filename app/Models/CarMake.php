<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    protected $fillable = ['name', 'country', 'status'];

    public function models()
    {
        return $this->hasMany(CarModel::class, 'make_id');
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class);
    }
}
