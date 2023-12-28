<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = User::with(['roles'])->get();
        // foreach($result as $role){
        //     $role->role_name=$result->roles()->first()->name;
        // }
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
            return $this->failed_response(data: $validation->errors(), code: 404);
        }
        $result = User::create($request->all());
        return $this->success_response(data: $result, code: 201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        $result = User::find($id);
        if (!is_null($result)) {
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(message: "id is not found", code: 404);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // $validation = $this->rules($request);
        // if ($validation->fails()) {
        //     return $this->failed_response(data: $validation->errors(), code: 404);
        // }
        $obj = User::find($id);
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
        $obj = User::find($id);
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
        // echo Route::currentRouteName();
        $signIn = Route::currentRouteName() == 'login';

        return Validator::make(
            $request->all(),
            [

                'username' => $signIn ? '' : ['required', 'unique:users', 'regex:/^[a-zA-Z ]+$/u'],
                'email' => $signIn ? '' : ['required', $signIn ? '' : 'unique:users,email'],
                'phone' => $signIn ? '' : 'required|unique:users,phone|numeric|digits:9',
                'password' => $signIn ? '' : ['required', $signIn ? '' : 'confirmed', 'min:8'],
                'user_type' => $signIn ? '' : 'required',
                'role' => $signIn ? '' : 'required|string'
            ]

        );
    }

    //******************************************* */

    function login(Request $request)
    {
        $validate = $this->rules($request);
        if ($validate->fails()) {
            return $this->failed_response(data: $validate->errors());
        }

        $user = User::whereEmail($request->email)->first();
        if (is_null($user)) {
            return $this->failed_response(message: 'not_found');
        } else if (!Hash::check($request->password, $user->password)) {
            return $this->failed_response(message: 'incorrect_pass');
        }

        $user->token = $user->createToken('api_token')->plainTextToken;
        $user->role = $user->roles()->first()->name;
        return $this->success_response(data: $user);
    }

    function signup(Request $request)
    {
        $validate = $this->rules($request);
        if ($validate->fails()) {
            return $this->failed_response(data: $validate->errors());
        }

        $request['password'] = bcrypt($request['password']);
        $request['active'] = false;
        $user = User::create($request->all());
        $user->assignRole($request->role);
        $user->role = $user->roles()->first()->name;
        return $this->success_response(data: $user);
    }

    function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success_response(data: ["Seccufully"]);
    }
}
