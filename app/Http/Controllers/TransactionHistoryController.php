<?php

namespace App\Http\Controllers;

use App\Models\Transaction_history;
use App\Models\Transaction_type;
use App\Models\Wallet;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionHistoryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result=Transaction_history::all();
        return $this->success_response(data:$result);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors());
        }
        $result = Transaction_history::create($request->all());
        $walletx=Wallet::where('id','=',$result->wallet_id)->first();
        $walletx->updateBalance($result->amount);
        // $result->wallet_id->updateBalance($result->amount);
        return $this->success_response(data:[$result,$walletx]);
        // $wallet=Wallet::find(18)->first();
        // $wallet->updateBalance(50);
        // $wallet=Wallet::where('user_id','=',1)->first();
        // if($wallet->balance>=500){
        //     $wallet->updateBalance(-500);
        // $branch=Wallet::where('user_id','=',3)->first();
        // $branch->updateBalance(500);
        // return $this->success_response(data: [$wallet,$branch]);
        // }
        // return response()->json(data:["فشل"],status:200);
        //
    }
    public function transfer(){
        $wallet=Wallet::find(18)->first();
        $wallet->updateBalance(50);
        $wallet=Wallet::where('user_id','=',1)->first();
        if($wallet->balance>=500){
            $wallet->updateBalance(-500);
        $branch=Wallet::where('user_id','=',3)->first();
        $branch->updateBalance(500);
        return $this->success_response(data: [$wallet,$branch]);
        }
        return response()->json(data:["فشل"],status:200);
        
    }
    //withdraw سحب
    public function withfdraw(Request $request){
        $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors());
        }
        $result = Transaction_history::create($request->all());
        $walletx=Wallet::where('id','=',$result->wallet_id)->first();
        $walletx->updateBalance($result->amount);
        // $result->wallet_id->updateBalance($result->amount);
        return $this->success_response(data:[$result,$walletx]);
    }
    //diposit ايداع
    public function diposit(Request $request){
        $type=Transaction_type::findOrFail(1);
        if(!is_null($type)){
            $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors());
        }
        $result = Transaction_history::create($request->all());
        $walletx=Wallet::where('id','=',$result->wallet_id)->first();
        $walletx->updateBalance($result->amount);
        // $result->wallet_id->updateBalance($result->amount);
        return $this->success_response(data:[$result,$walletx]);
        }
        return $this->failed_response(data:'لايمكنك استحدام هذه العملية الا فقط للايداع');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction_history $transaction_history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction_history $transaction_history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction_history $transaction_history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction_history $transaction_history)
    {
        //
    }
    function rules(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'transaction_type_id' => ['required','numeric','exists:transaction_types,id'],
                'wallet_id' => ['required','numeric','exists:wallets,id'],
                'booking_id' => ['required','numeric','exists:bookings,id'],
                'amount' => ['required','numeric']
            ]

        );
    }
}
