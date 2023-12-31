<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrandResource;
use App\Models\Prand;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrandController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prand $prand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prand $prand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prand $prand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $obj = Prand::find($id);
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
    function uploadImage(Request $request)
    {
        $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors(), code: 404);
        }
        if ($request->has('image_path')) {
            $image = $request->file('image_path');
            $image_name = time() . '_image.' . $image->getClientOriginalExtension();
            $path = 'public/photo_upload/products';
            $stored_path = $image->storeAs($path, $image_name);
            $request['path'] = $stored_path;
            $result = Prand::create($request->all());
            return $this->success_response(data: $result);
        }
    }
    function getPrandName(Request $request)
    {
        $name=$request->input('name');
        $namePrand=Prand::where('name','=',$name)->get();
        return $this->success_response(data: $namePrand);

        // $result = Image_car_brand::with('cars')->get();
        // return $this->success_response(data: $result);

        //return ImageCarBrandResource::collection(Image_car_brand::all());
    }

    function retImage()
    {

        return PrandResource::collection(Prand::all());
    }
    function retImageID(int $id)
    {

        $result = Prand::find($id);
        return $this->success_response(data: $result);

    }
    function rules(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|unique:prands',

        ]);
    }
}
