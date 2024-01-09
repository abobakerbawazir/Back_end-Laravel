<?php

namespace App\Http\Controllers;

use App\Models\Transaction_type;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionTypeController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result=Transaction_type::all();
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
        $result = Transaction_type::create($request->all());
        return $this->success_response(data: $result);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction_type $transaction_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction_type $transaction_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction_type $transaction_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction_type $transaction_type)
    {
        //
    }
    function rules(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => ['required','string','unique:transaction_types,name'],
            ]

        );
    }
}
