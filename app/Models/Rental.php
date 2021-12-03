<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'car_id', 'start', 'expected_end', 'end', 'daily_value', 'km_initial', 'km_end'];

    public function rules()
    {
        return [
            'customer_id' => 'exists:customers,id',
            'car_id' => 'exists:cars,id',
            'start' => 'required|date',
            'expected_end' => 'required|date',
            'end' => 'required|date',
            'daily_value' => 'required|numeric',
            'km_initial' => 'required|numeric',
            'km_end' => 'required|numeric',
        ];
    }
}
