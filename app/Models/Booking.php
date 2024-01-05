<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'from',
        "to",
        "total",
        "user_id",
        "car_id",
        "status"
    ];
    protected $dates = ['from', 'to'];

    /**
     * The user that belong to the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The cars that belong to the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cars()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
