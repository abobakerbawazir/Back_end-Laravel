<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransferResourse;
use App\Models\Booking;
// use App\Models\Car;
use App\Models\Transaction_history;
use App\Models\Transaction_type;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class TransactionHistoryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Transaction_history::with('transactionType', 'wallet',)->get();
        return $this->success_response(data: $result);
        //
    }
    public function getInfoAllTransactionHistoryToTransfer()
    {
        $results = Transaction_history::where('transaction_type_id', 2)->with('transactionType', 'wallet.user', 'booking.user', 'booking.cars.users')->get();
        $resultStatus = $results->map(function ($result) {
            $result->status = $result->status == 1 ? true : false;
            $result->wallet->user->image = $result->wallet->user->image != null ? $result->wallet->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
            return $result;
        });
        // //$x=Booking::find(298);
        // $x=$result->booking->cars->users->id;
        // $w=Wallet::where('user_id',$x)->first();
        return $this->success_response(data: $resultStatus);
        //
    }
    public function getInfoOneTransactionHistoryToTransfer($id)
    {
        $result = Transaction_history::where('transaction_type_id', 2)->where('id', $id)->with('transactionType', 'booking.user', 'wallet.user', 'booking.cars.users')->first();
        $result->status = $result->status == 1 ? true : false;
        $result->booking->user->active = $result->booking->user->active == 1 ? true : false;
        $result->booking->cars->users->active =  $result->booking->cars->users->active == 1 ? true : false;
        $result->wallet->user->active =  $result->wallet->user->active == 1 ? true : false;
        $result->booking->cars->active =  $result->booking->cars->active == 1 ? true : false;
        $result->wallet->user->active =  $result->wallet->user->active == 1 ? true : false;
        $result->wallet->user->image = $result->wallet->user->image != null ? $result->wallet->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
        $result->booking->user->image = $result->booking->user->image != null ? $result->booking->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
        $result->booking->cars->users->image = $result->booking->cars->users->image != null ? $result->booking->cars->users->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
        $x = $result->booking->cars->users->id;
        $userCustomer = $result->wallet->user;
        $userBooking = $result->booking->user;
        $userCars = $result->booking->cars->users;
        $transactionType = array_merge(["id" => $result->transactiontype->id, "name" => $result->transactiontype->name]);
        $treansaction = array_merge([
            "id" => $result->id, "transaction_type_id" => $result->transaction_type_id, "wallet_id" => $result->wallet_id, "booking_id" => $result->booking_id,
            "amount" => $result->amount, "status" => $result->status, "description" => $result->description
        ]);
        $booking = array_merge([
            "id" => $result->booking->id,
            "from" => $result->booking->from,
            "to" => $result->booking->to, "total" => $result->booking->total, "user_id" => $result->booking->user_id, "car_id" => $result->booking->car_id, "status" => $result->booking->status, "payment_status" => $result->booking->payment_status
        ]);
        $walletCustomer = array_merge(["id" => $result->wallet->id, "code" => $result->wallet->code, "balance" => $result->wallet->balance, "user_id" => $result->wallet->user_id]);
        $walletBranch = Wallet::where('user_id', $x)->first();
        $walletBranchx = array_merge(["id" => $walletBranch->id, "code" => $walletBranch->code, "balance" => $walletBranch->balance, "user_id" => $walletBranch->user_id]);
        $userBranch = User::where('id', $walletBranch->user_id)->first();
        $userBranch->image = $userBranch->image != null ? $userBranch->booking->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";

        $userBranch->active =  $userBranch->active  == 1 ? true : false;

        $car = array_merge([
            "id" => $result->booking->cars->id, "name" => $result->booking->cars->name, "model" => $result->booking->cars->model,
            "active" => $result->booking->cars->active, "price" => $result->booking->cars->price, "user_id" => $result->booking->cars->user_id, "prand_id" => $result->booking->cars->prand_id,
        ]);
        return $this->success_response(data: ["userBranch" => $userBranch, "transactionType" => $transactionType, "treansaction" => $treansaction, "booking" => $booking, "userCars" => $userCars, "userBooking" => $userBooking, 'walletCustomer' => $walletCustomer, 'userCustomer' => $userCustomer, "walletBranch" => $walletBranchx, "car" => $car]);
    }
    public function getInfoAllTransactionHistory()
    {
        $result = Transaction_history::with('transactionType', 'wallet', 'booking')->get();
        return $this->success_response(data: $result);
        //
    }
    public function getInfoAllTransactionHistoryNotTransfer($id)
    {
        if ($id == 'all') {
            $results = Transaction_history::with('transactionType', 'wallet.user')->get();
            $resultStatus = $results->map(function ($result) {
                $result->status = $result->status == 1 ? true : false;
                $result->wallet->user->active = $result->wallet->user->active == 1 ? true : false;
                $result->booking_id = $result->booking_id != null ? $result->booking_id : -1;

                $result->wallet->user->image = $result->wallet->user->image != null ? $result->wallet->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
                return $result;
            });
            return $this->success_response(data: $resultStatus);
        } else {
            $results = Transaction_history::where('transaction_type_id', '=', $id)->with('transactionType', 'wallet.user')->get();
            if ($id != '2') {
                $resultStatus = $results->map(function ($result) {
                    $result->status = $result->status == 1 ? true : false;
                    $result->wallet->user->active = $result->wallet->user->active == 1 ? true : false;
                    $result->booking_id = $result->booking_id != null ? $result->booking_id : -1;
                    $result->wallet->user->image = $result->wallet->user->image != null ? $result->wallet->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
                    return $result;
                });
                return $this->success_response(data: $resultStatus);
            }
            return $this->success_response(data: []);
        }


        //
    }
    public function getInfoAllTransactionHistoryDiposit()
    {
        $results = Transaction_history::where('transaction_type_id', '=', 3)->with('transactionType', 'wallet.user')->get();
        $resultStatus = $results->map(function ($result) {
            $result->status = $result->status == 1 ? true : false;
            $result->wallet->user->image = $result->wallet->user->image != null ? $result->wallet->user->image : env('APP_URL') . ":8000/storage/photo_upload/cars/404.png";
            return $result;
        });
        return $this->success_response(data: $resultStatus);
        //
    }
    public function getConutTransactionHistory($id)
    {
        if ($id == 'all') {
            $results = Transaction_history::count();
            return $this->success_response(data: $results);
        } 
        else {
            $results = Transaction_history::where('transaction_type_id', '=', $id)->count();
            return $this->success_response(data: $results);
        }

        //
    }
    public function getConutTransactionHistoryDipositStateFalse()
    {
        $results = Transaction_history::where('transaction_type_id', '=', 3)->where('status', '=', false)->with('transactionType', 'wallet.user')->count();
        return $this->success_response(data: $results);
    }
    public function getonlyTransactionHistoryDipositWithStatusFalse()
    {
        $results = Transaction_history::where('transaction_type_id', '=', 1)->where('status', '=', false)->with('transactionType', 'wallet')->get();
        $resultStatus = $results->map(function ($result) {
            $result->status = $result->status == 1 ? true : false;
            return $result;
        });
        return $this->success_response(data: $resultStatus);
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
        $walletx = Wallet::where('id', '=', $result->wallet_id)->first();
        $walletx->updateBalance($result->amount);
        // $result->wallet_id->updateBalance($result->amount);
        return $this->success_response(data: [$result, $walletx]);
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
    public function transfer(Request $request)
    {
        $type = Transaction_type::find(2);
        if (!is_null($type)) {
            $validation = $this->rulesdiposit($request);
            if ($validation->fails()) {
                return $this->failed_response(data: $validation->errors());
            }
            $checkBookingId = Transaction_history::where('booking_id', '=', $request->booking_id)->first();
            if (!is_null($checkBookingId)) {
                return $this->failed_response(data: 'لايمكن الدفع مرة اخرى لنفس عملية الحجز');
            }
            $booking = Booking::find($request->booking_id);
            $walletCustomer = Wallet::where('id', '=', $request->wallet_id)->first();
            if ($request->amount <= $walletCustomer->balance) {
                if ($booking->total > $walletCustomer->balance) {
                    return $this->failed_response(data: 'لايمكن ان يكون مبلغ الحجز' . $booking->total . 'اكبر من المبلغ الموجود في المحفظة الا وهو ' . $walletCustomer->balance);
                }
                $result = Transaction_history::create(['wallet_id' => $request->wallet_id, 'booking_id' => $request->booking_id, 'amount' => $booking->total, 'transaction_type_id' => $type->id]);
                $branchId = $this->getBranchIdBooking($request->booking_id);
                $walletBranch = Wallet::where('user_id', '=', $branchId)->first();
                $booking->updatePaymentStatus('عبر المحفظة');
                $booking->updateStatus('مؤكد');
                $walletCustomer->updateBalance(- ($result->amount));
                $walletBranch->updateBalance($result->amount);
                return response()->json(data: TransferResourse::collection(["result" => $result, "walletCustomer" => $walletCustomer, "walletBranch" => $walletBranch, "booking" => $booking]));
            }
            return $this->failed_response(data: 'لا يمكن أن يكون مبلغ الحجز' . $booking->total . 'اكبر من المبلغ الموجود في المحفظة الا وهو' . $walletCustomer->balance);
        }

        return $this->failed_response(data: 'لايمكنك استحدام هذه العملية الا فقط للسحب');

        // $wallet = Wallet::find(18)->first();
        // $wallet->updateBalance(50);
        // $wallet = Wallet::where('user_id', '=', 1)->first();
        // if ($wallet->balance >= 500) {
        //     $wallet->updateBalance(-500);
        //     $branch = Wallet::where('user_id', '=', 3)->first();
        //     $branch->updateBalance(500);
        //     return $this->success_response(data: [$wallet, $branch]);
        // }
        // return response()->json(data: ["فشل"], status: 200);
    }
    //withdraw سحب
    public function withdraw(Request $request)
    {
        $type = Transaction_type::find(1);
        if (!is_null($type)) {
            $validation = $this->rulesdiposit($request);
            if ($validation->fails()) {
                return $this->failed_response(data: $validation->errors());
            }
            $walletx = Wallet::where('id', '=', $request->wallet_id)->first();
            if ($request->amount <= $walletx->balance) {
                $result = Transaction_history::create(['wallet_id' => $request->wallet_id, 'booking_id' => null, 'amount' => $request->amount, 'transaction_type_id' => $type->id]);
                $walletx->updateBalance(- ($result->amount));
                // $result->wallet_id->updateBalance($result->amount);
                return $this->success_response(data: [$result, $walletx]);
            }
            return $this->failed_response(data: 'لا يمكن أن يكون المبلغ' . $request->amount . 'اكبر من المبلغ الموجود في المحفظة الا وهو' . $walletx->balance);
        }

        return $this->failed_response(data: 'لايمكنك استحدام هذه العملية الا فقط للسحب');
    }
    //diposit ايداع
    public function diposit(Request $request)
    {
        $type = Transaction_type::find(3);
        if (!is_null($type)) {
            $validation = $this->rulesdiposit($request);
            if ($validation->fails()) {
                return $this->failed_response(data: $validation->errors());
            }
            $result = Transaction_history::create(['wallet_id' => $request->wallet_id, 'booking_id' => null, 'amount' => $request->amount, 'transaction_type_id' => $type->id]);
            $walletx = Wallet::where('id', '=', $result->wallet_id)->first();
            if ($result->status == true) {
                $walletx->updateBalance($result->amount);
            }
            // $result->wallet_id->updateBalance($result->amount);
            return $this->success_response(data: [$result, $walletx]);
        }

        return $this->failed_response(data: 'لايمكنك استحدام هذه العملية الا فقط للايداع');
    }
    public function updateDiposit(int $id)
    {
        $obj = Transaction_history::find($id);
        if (!is_null($obj)) {
            // $result = tap($obj)->update($obj->status);

            if ($obj->status == true) {
                return $this->success_response(data: 'تم قبول الطلب بالفعل ولا يمكن تكرار نفس العملية بنفس السند');
            }
            $obj->updateStatus();
            $wallet = Wallet::find($obj->wallet_id);
            $b = $obj->amount;
            $wallet->updateBalance($b);
            return $this->success_response(data: [$obj, $wallet]);
        } else {
            return $this->failed_response(code: 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $result = Transaction_history::find($id);
        if (!is_null($result)) {
            return $this->success_response(data: $result);
        } else {
            return $this->failed_response(message: "id is not found", code: 404);
        }
        //
    }
    public function getBranchIdBooking($bookingId)
    {
        $bookingR = Booking::where('id', '=', $bookingId)->with('cars')->with('cars.users')->first();
        $branchId = $bookingR->cars->users->id;
        return $branchId;
        // return $this->success_response(data: $branchId);
        // $booking = Booking::where('id', '=', 296)->first();
        // $car = Car::where('id', '=', $booking->car_id)->first();
        // $branch = User::where('id', '=', $car->user_id)->first();
        // $walletx = Wallet::where('user_id', '=', $branch->id)->first();
        // return $this->success_response(data: ['booking' => $booking, 'car' => $car, 'branch' => $branch, 'walletBranch' => $walletx]);
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
                'transaction_type_id' => ['required', 'numeric', 'exists:transaction_types,id'],
                'wallet_id' => ['required', 'numeric', 'exists:wallets,id'],
                'booking_id' => ['required', 'numeric', 'exists:bookings,id'],
                'amount' => ['required', 'numeric']
            ]

        );
    }
    function rulesdiposit(Request $request)
    {
        return Validator::make(
            $request->all(),
            [
                // 'transaction_type_id' => ['required','numeric','exists:transaction_types,id'],
                'wallet_id' => ['required', 'numeric', 'exists:wallets,id'],
                // 'booking_id' => ['required','numeric','exists:bookings,id'],
                'amount' => ['required', 'numeric']
            ]

        );
    }
}
