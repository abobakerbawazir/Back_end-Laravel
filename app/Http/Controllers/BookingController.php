<?php

namespace App\Http\Controllers;

use App\Http\Resources\Booking2Resource;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $result = Booking::all();
    //     // $res=Auth::user()->cars;
    //     $resultmodify = $result->map(function ($result) {
    //         $start = Carbon::parse($result['from']);
    //         $end = Carbon::parse($result['to']);
    //         $diffInDays = $start->diffInDays($end);
    //         if ($start->greaterThan($end)) {
    //             $result['days'] = 'التاريخ غير مقبول لحساب عدد الايام';
    //         } else {
    //             $result['days'] = $diffInDays;
    //         }
    //         return $result;
    //     });
    //     return $this->success_response(data: $resultmodify);


    //     //
    // }
    public function getBookingByBranchIdByCustomerIdForCoustomer($branch_id, $customer_id)
    {
        $booking = Booking::with('cars.users')->with('cars.image_car_brands')->with('user')->where('user_id', $customer_id)->whereHas('cars', function ($query) use ($branch_id) {
            $query->where('cars.user_id', $branch_id);
        })->get();
        return response()->json($booking, 200);
    }
    public function getAllInformationBookingForAllCustomer()
    {
        $booking = Booking::with('cars.users')->with('cars.image_car_brands')->with('user')->get();
        //$booking = Booking::find(294)->with('cars.users')->with('cars.image_car_brands')->with('user')->first();

        return response()->json($booking, 200);
    }
    public function getAllInformationBookingForOnlyCustomer(int $user_id)
    {
        $booking = Booking::where('user_id', $user_id)->with('cars.users')->with('cars.image_car_brands')->with('user')->get();
        // foreach($booking as $booking){
        //     $booking->user->image =  $booking->user->image ?? "http://192.168.179.98:8000/storage/photo_upload/users/100.png";

        // }
        return response()->json(BookingResource::collection($booking));
    }
    public function getByIDInformationBookingForAllCustomer(int $id)
    {
        $booking = Booking::where('id', $id)->with('cars.users')->with('cars.image_car_brands')->with('user')->first();
        $booking->user->image =  $booking->user->image !="http://192.168.179.98:8000/storage/"?$booking->user->image: "8000///photo_upload/users/100.png";
        // $result = [
        //     'booking' => $booking,
        //     'cars' =>  ,
        //     'user' => 
        // ];
        return response()->json(new BookingResource($booking));
    }
    public function getBookingByBranchId($branch_id)
    {
        //  $booking=Booking::with('cars')->with('user')->where('user_id',4)->
        //  whereHas('cars',function($query)use($branch_id){ $query->where('cars.user_id',$branch_id);})->get();
        // $branch_id=request()->user()->id;
        $booking = Booking::with('cars.image_car_brands')->with('user')->whereHas('cars', function ($query) use ($branch_id) {
            $query->where('cars.user_id', $branch_id);
        })->get();
        //  $booking->user->image = 
        //   $booking->user->image ?? "http://192.168.179.98:8000/storage/photo_upload/users/100.png";

        // $booking=Booking::with('cars')
        // ->whereHas('cars.users',function($query)use($branch_id)
        // {
        //     return $query->where('user_id',$branch_id);
        // })
        // ->get();
        return response()->json(Booking2Resource::collection($booking), 200);

        // return response()->json($branch_id,200);
    }
    public function index()
    {


        // $result = User::find(2);
        // $res=Booking::where('user_id','=',2)->get();
        // $resultmodify = $res->map(function ($res) {
        //     // $res['from']='fg';
        //     return $res;
        // });
        // return $this->success_response(data: ["user"=>$result,"booking"=>$resultmodify]);
        $result = Booking::all();
        //$result = Booking::where('user_id', 10)->where('car_id', 14)->first();

        return $this->success_response(data: BookingResource::collection($result));


        //
    }
    public function bookingwithcaranduserbyId(int $user_id, int $car_id)
    {
        $result = Car::find($car_id)->users()->find($user_id);
        return $this->success_response(data: $result);
        //
    }
    public function bookingwithusersId(int $user_id)
    {
        // $result = User::find($user_id)->cars()->get()->first();
        $result = User::find($user_id)->bookings()->get();
        return $this->success_response(data: $result);
        //
    }
    public function bookingcarsactive(Request $request, int $userId, int $carId)
    {
        $car = Car::findOrFail($carId);
        if ($car->active == 1) {
            $user = User::findOrFail($userId);
            $booking = Booking::where('car_id', $car->id)->where('user_id', $user->id)->first();
            if ($booking) {
                return response()->json(['Message' => 'User is already booking in the cars']);
            }
            $booking = Booking::create(['car_id' => $car->id, 'user_id' => $user->id, 'from' => $request->from, 'to' => $request->to, 'total' => $request->total]);
            // $bookingData = $request->input('from');
            // $bookingData = $request->input('to');
            // $bookingData = $request->input('total');
            //$booking = new Booking();
            // $booking->from = $bookingData['from'];
            // $booking->to = $bookingData['to'];
            // $booking->total = $bookingData['total'];
            // $booking->total = $bookingData['user_id'];
            // $booking->total = $bookingData['car_id'];
            // $booking->user_id = $user->id;
            // $booking->car_id = $car->id;
            //$booking->save();
            return response()->json(['Message' => 'User Booking Successfully']);
        } else {
            return response()->json(['Message' => 'Cannot Booking in an intactive cars'], 403);
        }
    }


    public function bookingCarsByUser_id_and_car_id_select_only_date_from_to(Request $request, int $userId, int $carId)
    {
        if (($request->to == null && $request->from == null) || ($request->to == 'null' && $request->from == 'null')) {
            return response()->json(['data' => 'لا يمكن ان لا تدخل تاريخ ليداية و لنهاية للحجز', "type" => 'خطا'], status: 209);
        } elseif (($request->from == null) || ($request->from == 'null')) {
            return response()->json(['data' => 'لا يمكن ان لا تدخل تاريخ لبداية للحجز', "type" => 'خطا'], status: 208);
        } elseif (($request->to == null) || $request->to == 'null') {
            return response()->json(['data' => 'لا يمكن ان لا تدخل تاريخ لنهاية للحجز', "type" => 'خطا'], status: 207);
        } else {
            //$user = User::findOrFail($userId);
            $b = Booking::where('user_id', $userId)->get();
            $bc = Booking::where('user_id', $userId)->where('car_id', $carId)->get()->first();


            //$b = Booking::where('user_id', $userId)->get()->first();
            //$b = Booking::findOrFail($userId);
            $car = Car::findOrFail($carId);
            if ($car->active == 1) {

                return response()->json(['data' => "لا يمكنك حجز السيارة", "type" => 'السيارة محجوزة'], status: 206);
            } else {
                $start = Carbon::parse($request->from);
                $end = Carbon::parse($request->to);
                $diffInDays = $start->diffInDays($end);
                // return response()->json(["days"=>intval($diffInDays)]);
                if ($start->greaterThan($end)) {
                    return response()->json(['data' => 'لا يمكن أن يكون تاريخ البداية أكبر من تاريخ النهاية', "type" => 'خطا'], status: 205);
                } elseif ($start == $end) {
                    return response()->json(['data' => 'لا يمكن أن يكون تاريخ البداية مثل تاريخ النهاية الحجز ', "type" => 'خطا'], status: 203);
                } elseif ($b) {
                    $counter = 0;
                    foreach ($b as $b) {
                        if ($b && $b->status == 'معلق') {
                            $counter++;
                            // break;
                        }
                    }

                    if ($counter >= 3) {
                        return response()->json(['data' => 'لا يمكنك الحجز أكثر من ثلاثة سيارات في نفس الوقت والطلب مازال تحت الانتضار الرجاء التواصل مع الفرع المسوؤل ', "type" => 'خطا'], status: 212);
                    } elseif ($bc && $b->status == 'معلق') {
                        return response()->json(['data' => 'لا يمكنك حجز نفس السيارة مرة اخرى ', "type" => 'خطا'], status: 213);
                    }
                    $booking = Booking::create(['car_id' => $carId, 'user_id' => $userId, 'from' => $request->from, 'to' => $request->to, 'total' => intval($diffInDays) * $car->price, 'status' => $request->status ?? 'معلق',]);
                    $obj = $car;
                    $result = tap($obj);
                    if ($booking->status == 'مؤكد') {
                        $result = tap($obj)->update(['active' => true]);
                        $result->save();
                    }
                    return response()->json(['data' => $booking, "type" => 'تم حجز السيارة بنجاح', "days" => intval($diffInDays), "active" => $result], status: 201);
                } else {
                    $booking = Booking::create(['car_id' => $carId, 'user_id' => $userId, 'from' => $request->from, 'to' => $request->to, 'total' => intval($diffInDays) * $car->price, 'status' => $request->status ?? 'معلق',]);
                    $obj = $car;
                    $result = tap($obj);
                    if ($booking->status == 'مؤكد') {
                        $result = tap($obj)->update(['active' => true]);
                        $result->save();
                    }
                    return response()->json(['data' => $booking, "type" => 'تم حجز السيارة بنجاح', "days" => intval($diffInDays), "active" => $result], status: 201);
                }
            }
        }
    }
    public function convertdays()
    {
        $start = Carbon::parse('2022-01-01');
        $end = Carbon::parse('2024-01-10');
        $diffInDays = $start->diffInDays($end);
        return response()->json(["days" => intval($diffInDays)]);
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
        $result = Booking::create($request->all());
        return $this->success_response(data: $result);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $result = Booking::find($id);
        if (!is_null($result)) {
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(message: "id is not found", code: 400);
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
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
            return $this->failed_response(data: $validation->errors(), code: 400);
        }
        $obj = Booking::find($id);
        if (!is_null($obj)) {
            $result = tap($obj)->update($request->all());
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(code: 400);
        }
        //
    }
    public function updateBookingStateByBranch(Request $request, int $id)
    {
        $validation = $this->rulesstatus($request);
        if ($validation->fails()) {
            return $this->failed_response(data: $validation->errors(), code: 400);
        }
        $obj = Booking::find($id);
        if (!is_null($obj)) {
            $car = Car::findOrFail($obj->car_id);
            if ($request->status == 'مؤكد') {
                if($obj->payment_status!='عبر المحفظة'){
                    $obj->updatePaymentStatus('عند الاستلام');
                }
                $car->active = true;
            } elseif ($request->status == 'مكتمل') {
                $car->active = false;
            }
            $car->save();
            //$car->update(["active"=>$car->active]);
            // $idcar=$car->id;
            $result = tap($obj)->update([$obj->status = $request->status]);
            return $this->success_response(data: [$result, $car,]);
        } else {
            return $this->failed_response(code: 400,);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int  $id)
    {
        $obj = Booking::find($id);
        if (!is_null($obj)) {
            $result = $obj->delete();
            if (!is_null($result)) {
                return $this->success_response(data: $result);
            }
        } else {
            return $this->failed_response(message: "id is not found", code: 400);
        }
        //
    }
    function rules(Request $request)
    {
        return Validator::make($request->all(), [
            'from' => 'required:bookings',
            'to' => 'required:bookings',
            'total' => 'required:bookings',
            'user_id' => 'required:bookings',
            'car_id' => 'required:bookings',
            'status' => 'required|in:معلق,مؤكد,ملغى,مكتمل,مرفوض'
        ]);
    }
    function rulesstatus(Request $request)
    {
        return Validator::make($request->all(), [
            'status' => 'required|in:معلق,مؤكد,ملغى,مكتمل,مرفوض'
        ]);
    }
}
