<?php

namespace App\Http\Controllers;

use App\Http\Resources\balanceResourse;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result=Wallet::with('user')->get();
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
            return $this->failed_response(data: $validation->errors(), code: 404);
        }
        $code=$this->uniqueGenerateCode();
         $wallet=Wallet::create(['code'=>$code,'balance'=>$request->balance,'user_id'=>$request->user_id]);
        return $this->success_response(data:$wallet);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $result=Wallet::where('id',$id)->first();
        return response()->json(new balanceResourse($result));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }private function generateWalletCode(){
        $charactersUpper='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLower='abcdefghijklmnopqrstuvwxyz';
        $digits='0123456789';
        $code='';
        for($i=0;$i<4;$i++){
            $code .=$charactersUpper[rand(0,strlen($charactersUpper)-1)];
        }
        for($i=0;$i<7;$i++){
            $code .=$digits[rand(0,strlen($digits)-1)];
        }
        for($i=0;$i<4;$i++){
            $code .=$charactersLower[rand(0,strlen($charactersLower)-1)];
        }
        while(Wallet::where('code',$code)->exists()){
            $code .='';
            for($i=0;$i<4;$i++){
                $code .=$charactersUpper[rand(0,strlen($charactersUpper)-1)];
            }
            for($i=0;$i<7;$i++){
                $code .=$digits[rand(0,strlen($digits)-1)];
            }
            for($i=0;$i<4;$i++){
                $code .=$charactersLower[rand(0,strlen($charactersLower)-1)];
            }
        }
        return $code;
    }
    public function uniqueGenerateCode(){
        $code=Str::random(15);
        while(Wallet::where('code',$code)->exists()){
            $code=Str::random(15);
        }
        return$code;
    }
    function rules(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'balance' => ['required','numeric'],
                'user_id' => ['required','unique:wallets,user_id']
            ]

        );
    }
}
