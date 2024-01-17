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
    /**
     * Get the cars that owns the Image_car_brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cars()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }
    function getUrlAttribute() {
        return env('APP_URL').'/storage/'.substr($this->attributes['url'],7);
    }
}
