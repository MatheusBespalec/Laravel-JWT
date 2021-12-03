<?php

namespace App\Models;

use App\Models\CarModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function rules()
    {
        return [
            'name' =>  'required|unique:brands,name,'.$this->id,
            'image' => 'required|file|mimes:png'
        ];
    }

    public function models()
    {
        return $this->hasMany(CarModel::class);
    }
}
