<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        "model",
        "active",
        "price",
        "user_id",
        "prand_id"
    ];
    public function image_car_brands(){
        return $this->hasMany(Image_car_brand::class);
    }
}
