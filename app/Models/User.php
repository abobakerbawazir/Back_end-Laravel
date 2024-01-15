<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'phone',
        'full_name',
        'active',
        'user_type',
        'image',
        'location',
        'email',
        'password',
    ];
    // public function cars()
    // {
    //     return $this->belongsToMany(Car::class,'bookings')->withPivot(['from','to','total'])->withTimestamps();
    // }
    public function usercars(){
        return $this->hasMany(Car::class);
    }
    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    // function getUrlAttribute() {
    //     return env('APP_URL').':8000/storage/'.substr($this->attributes['image'],7);
    // }
    function getImageAttribute() {
        return env('APP_URL').':8000/storage/'.substr($this->attributes['image'],7);
    }
    /**
     * Get the wallets that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }
    // public function booking(){
    //     return $this->hasMany(Booking::class);
    // }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
