<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\Image_car_brand;
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
        $result = Car::get();
        
        return $this->success_response(data:CarResource::collection($result));
        // $result = Car::select('name','model')->get();
        // return $this->success_response(data: $result);
        //
    }
    function getCarWithUserAndPrand(Request $request)
    {
        $user_id=$request->input('user_id');
        $prand_id=$request->input('prand_id');
        // $namePrand=Car::where('prand_id','=',$prand_id)->where('user_id','=',$user_id)->with('image_car_brands')->get();
        $namePrand=Car::where('prand_id','=',$prand_id)->where('user_id','=',$user_id)->get();

        //return $this->success_response(data: $namePrand);
        return $this->success_response(data:CarResource::collection($namePrand));

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
    public function update(Request $request, int $id)
    {
    
        $validation = $this->rules($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors(), code: 404);
        }
        $obj = Car::find($id);
        if (!is_null($obj)) {
            $result = tap($obj)->update($request->all());
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(code: 404);
        }
    
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

   public function addCarAndImage(Request $request){
    $result = Car::create($request->all());
        // return $this->success_response(data: $result);
    if ($request->has('image_car_of_brands')) {
        $image = $request->file('image_car_of_brands');
        $image_name = time() . '_image.' . $image->getClientOriginalExtension();
        $path = 'public/photo_upload/cars';
        $stored_path = $image->storeAs($path, $image_name);
        $request['url'] = $stored_path;
        $resualimage = Image_car_brand::create(['car_id'=>$result->id,'url'=>$stored_path]);
        return $this->success_response(data: [$result,$resualimage]);
    }
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
