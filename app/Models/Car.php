<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarModel;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'plate', 'available', 'km'];

    public function rules()
    {
        return [
            'model_id' => 'exists:car_models,id',
            'plate' => 'required',
            'available' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function model() {
        return $this->belongsTo(CarModel::class);
    }
}
