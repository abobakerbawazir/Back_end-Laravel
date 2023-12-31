<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Car::with('image_car_brands')->get();
        
        return $this->success_response(data:CarResource::collection($result));
        // $result = Car::with('image_car_brands')->get();
        // return $this->success_response(data: $result);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $result = Car::all();
        return $this->success_response(data: $result);
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
        $result = Car::create($request->all());
        return $this->success_response(data: $result);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $result = Car::find($id);
        if (!is_null($result)) {
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(message: "id is not found", code: 404);
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $obj = Car::find($id);
        if (!is_null($obj)) {
            $result = $obj->delete();
            if (!is_null($result)) {
                return $this->success_response($result);
            }
        } else {
            return $this->failed_response(message: "id is not found", code: 404);
        }
        //
    }
    function rules(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'name' => ['required'],
                'model' => ['required'],
                'price' => ['required','numeric'],
                'user_id' => ['required'],
                'prand_id' => ['required']
            ]

        );
    }
}
