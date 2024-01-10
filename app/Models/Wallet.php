<?php

namespace App\Models;

use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use PhpParser\Node\Expr\Cast\Double;
use Illuminate\Support\Str;


class Wallet extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['code','balance','user_id'];
    /**
     * Get the user associated with the Wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function updateBalance($amount){
        $this->balance+=$amount;
        $this->save();
    }public function uniqueGenerateCode(){
        $code=Str::random(15);
        while(Wallet::where('code',$code)->exists()){
            $code=Str::random(15);
        }
        return$code;
    }
    
}
