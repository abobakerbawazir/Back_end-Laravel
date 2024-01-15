<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction_history extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['transaction_type_id','wallet_id','booking_id','amount','description','status'];
    /**
     * Get the transactionType that owns the Transaction_history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactionType()
    {
        return $this->belongsTo(Transaction_type::class,);
    }
    /**
     * Get the wallet that owns the Transaction_history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(wallet::class, 'wallet_id', 'id');
    }
    /**
     * Get the Booking that owns the Transaction_history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
    public function updateStatus(){
        $this->status=true;
        $this->save();
    }
   
}
