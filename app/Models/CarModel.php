<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id', 'name', 'image', 'ports_number', 'seats', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'brand_id' => 'exists:brands,id', 
            'name' => 'required|unique:car_models,name,'.$this->id, 
            'image' => 'required|file|mimes:png,jpg,jpeg', 
            'ports_number' => 'required|integer', 
            'seats' => 'required|integer',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
