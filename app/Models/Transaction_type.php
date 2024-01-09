<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction_type extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['name'];
    /**
     * Get all of the transactionHistory for the Transaction_type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionHistory()
    {
        return $this->hasMany(Transaction_history::class, 'transaction_type_id', 'id');
    }
}
