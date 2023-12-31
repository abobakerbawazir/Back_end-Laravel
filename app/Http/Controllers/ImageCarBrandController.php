<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageCarBrandResource;
use App\Models\Image_car_brand;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageCarBrandController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    function uploadImage(Request $request)
    {
        $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors(), code: 404);
        }
        if ($request->has('image_path')) {
            $image = $request->file('image_path');
            $image_name = time() . '_image.' . $image->getClientOriginalExtension();
            $path = 'public/photo_upload/cars';
            $stored_path = $image->storeAs($path, $image_name);
            $request['url'] = $stored_path;
            $result = Image_car_brand::create($request->all());
            return $this->success_response(data: $result);
        }
    }
    function retImage()
    {
        $result = Image_car_brand::with('cars')->get();
        return $this->success_response(data: $result);

        //return ImageCarBrandResource::collection(Image_car_brand::all());
    }
    function getImageId(Request $request)
    {
        $car_id=$request->input('car_id');
        $carImageId=Image_car_brand::where('car_id','=',$car_id)->get();
        return $this->success_response(data: $carImageId);

        // $result = Image_car_brand::with('cars')->get();
        // return $this->success_response(data: $result);

        //return ImageCarBrandResource::collection(Image_car_brand::all());
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Image_car_brand $image_car_brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image_car_brand $image_car_brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image_car_brand $image_car_brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image_car_brand $image_car_brand)
    {
        //
    }
    function rules(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'image_path' => ['required'],
                'car_id' => ['required'],
            ]

        );
    }
}
