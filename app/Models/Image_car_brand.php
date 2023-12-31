<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_car_brand extends Model
{
    use HasFactory;
    protected $fillable = [
        "url",
        "car_id"
    ];
    public function cars()
    {
        return $this->belongsTo(Car::class);
    }
    function getUrlAttribute() {
        return env('APP_URL').':8000/storage/'.substr($this->attributes['url'],7);
    }
}
