<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelEngine extends Model
{
    protected $guarded = [];

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    public function engine()
    {
        return $this->belongsTo(Engine::class, 'engine_id');
    }
}
