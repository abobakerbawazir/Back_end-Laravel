<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prand extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','path'];

    function getPathAttribute() {
        return env('APP_URL').':8000/storage/'.substr($this->attributes['path'],7);
    }
}
