<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResourse;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    // public function index(){}
    public function index()
    {
        // Booking::with('user')->whereHas('cars',function($query){ $query->where('cars.user_id',1);})->get()
        // $users=User::with('bookings.cars')->get();
        // return response()->json($users);

        // $users=User::with(['bookings'=>function($query){
        //     $query->select('user_id','from');
        // },'bookings.cars'=>function($query){
        //     $query->select('name');
        // }])->get();
        //return response()->json($users);

        // $users=User::with(['bookings.cars'=>function($query){
        //     $query->select('name');
        // }])->get();
        //  return response()->json($users);

        $result = User::with('roles')->get();
        return $this->success_response(data: UserResource::collection($result));
        // // foreach($result as $role){
        // //     $role->role_name=$result->roles()->first()->name;
        // // }

        // // 'roles' => $this->roles->name
        // //return $this->success_response(data:$result);
        // return $this->success_response(data: UserResource::collection($result));
        // $result = User::with('bookings')->get();
        // return $this->success_response(data: UserResource::collection($result));



        //
    }
    public function fltterUser(Request $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $roles = $request->input('roles');
        $result = User::query();
        //$r=Role::all();
        //$x=Role::all();
        if ($username) {
            $result->where('username', 'LIKE', '%' . $username . '%');
        }
        if ($email) {
            $result->where('email', 'LIKE', '%' . $email . '%');
        }
        // if($roles){
        //     $result->where('roles','LIKE','%'. $roles .'%');
        // }
        $filteredResult = $result->get();
        return $this->success_response(data: UserResource::collection($filteredResult));
    }
    public function viewAllBranchActive()
    {
        $result = User::role('branch')->where('active', '=', 1)->get();
        // $result = User::whereDoesntHave('roles',function($query){
        //     $query->where('name','admin');
        // })->get();
        return $this->success_response(data: UserResource::collection($result));
    }
    public function viewAllBranchActiveSearch(Request $request)
    {
        $full_name = $request->input('full_name');
       // $email = $request->input('email');
        $result = User::role('branch')->where('active', '=', 1);
        if ($full_name) {
            $result->where('full_name', 'LIKE', '%' . $full_name . '%');
        }
        // if ($email) {
        //     $result->where('email', 'LIKE', '%' . $email . '%');
        // }
        $filteredResult = $result->get();
        return $this->success_response(data: UserResource::collection($filteredResult));
    }
    public function viewAlluserDoesNotAdmin()
    {
        $result = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        return $this->success_response(data: UserResource::collection($result));
    }
    public function viewAlluserByRoleName(String $name, int $id)
    {
        //$result = User::role('branch')->where('active', '=', 1)->get();
        $result = User::role($name)->where('active', '=', $id)->get();
        return $this->success_response(data: UserResource::collection($result));
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
        $result = User::create($request->all());
        return $this->success_response(data: $result);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        $result = User::find($id);
        if (!is_null($result)) {
            return $this->success_response(data: new UserResource($result));
        } else {
            return $this->failed_response(message: "id is not found", code: 404);
        }
        //
    }
    public function showBranch(string $roles)
    {

        $result = User::find($roles)->roles->first()->name;
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
        // $validation = $this->rulesUpdate($request);
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
                'email' => $signIn ? '' : ['required', 'email', $signIn ? '' : 'unique:users,email'],
                'phone' => $signIn ? '' : 'required|unique:users,phone|numeric|digits:9',
                'password' => $signIn ? '' : ['required', $signIn ? '' : 'confirmed', 'min:8'],
                'role' => $signIn ? '' : 'required|string'
            ]

        );
    }
    function rulesUpdate(Request $request)
    {
        // echo Route::currentRouteName();
        $signIn = Route::currentRouteName() == 'login';

        return Validator::make(
            $request->all(),
            [

                'email' => $signIn ? '' : ['required', 'email', $signIn ? '' : 'unique:users,email'],
                'phone' => $signIn ? '' : 'required|unique:users,phone|numeric|digits:9',
                'password' => $signIn ? '' : ['required', $signIn ? '' : 'confirmed', 'min:8'],

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
        $user->wallet;

        //$user->role = $user->roles()->first()->name;
        return $this->success_response(data: new ProfileResourse($user));
    }
    public function addUserAndAddImage(Request $request)
    {
        $validate = $this->rules($request);
        if ($validate->fails()) {
            return $this->failed_response(data: $validate->errors());
        }
        $request['password'] = bcrypt($request['password']);
        $request['active'] = false;
        if ($request->has('image_path')) {
            $image = $request->file('image_path');
            $image_name = time() . '_image.' . $image->getClientOriginalExtension();
            $path = 'public/photo_upload/userImage';
            $stored_path = $image->storeAs($path, $image_name);
            $request['image'] = $stored_path;
            $user = User::create($request->all());
            $user->assignRole($request->role);
            $user->role = $user->roles()->first()->name;
            $code = $this->uniqueGenerateCode();
            $wallet = Wallet::create(['code' => $code, 'balance' => 0, 'user_id' => $user->id]);
            // return $this->success_response(data:$wallet);
            return $this->success_response(data: $user);
            // return $this->success_response(data: $result);
        }
    }
    public function updateImageUser(int $id, Request $request)
    {
        $validate = $this->rulesupdateImageUser($request);
        if ($validate->fails()) {
            return $this->failed_response(data: $validate->errors());
        }
        if ($request->has('image_path')) {
            $image = $request->file('image_path');
            $image_name = time() . '_image.' . $image->getClientOriginalExtension();
            $path = 'public/photo_upload/userImage';
            $final_path = $image->storeAs($path, $image_name);
            $user = User::find($id);
            // if ($user->image == "http://192.168.129.98:8000/storage/photo_upload/users/404.png")
                if ($user->image == (env('APP_URL') . '/storage/' ."photo_upload/users/404.png"))
                 {
            $path = 'public/photo_upload/userImage';
            $stored_path = $image->storeAs($path, $image_name);
            $request['image'] = $stored_path;
            $user->image =$final_path;
            $user->save();}
                // $oldImage = $user->image;
                // if ($oldImage) {
                //     Storage::disk('public')->delete($path . $oldImage);
                // }
                
             else {
                // $oldImage=$user->image;
                unlink(storage_path() . '/app/public/' . explode('storage/', $user->image)[1]);
                $user->image = $final_path;
                // if($oldImage){
                // Storage::disk('public')->delete($path . $oldImage);
                // }
            }
                $user->save();

                return $this->success_response(data: $user);
            
        }
    }
    function rulesupdateImageUser(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                'image_path' => 'required|image|mimes:png,jpg,jpeg,gif,svg'

            ]

        );
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
        $code = $this->uniqueGenerateCode();
        $wallet = Wallet::create(['code' => $code, 'balance' => 0, 'user_id' => $user->id]);
        // return $this->success_response(data:$wallet);
        return $this->success_response(data: $user);
    }
    public function uniqueGenerateCode()
    {
        $code = Str::random(15);
        while (Wallet::where('code', $code)->exists()) {
            $code = Str::random(15);
        }
        return $code;
    }

    function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success_response(data: ["تم تسجيل الخروج بنجاح"]);
    }
}
