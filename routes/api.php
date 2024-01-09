<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ImageCarBrandController;
use App\Http\Controllers\PrandController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\WalletController;
use App\Models\Prand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('uploadImage', [PrandController::class, 'uploadImage'])->name('uploadImage');
Route::get('retImage', [PrandController::class, 'retImage'])->name('retImage');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('signup', [AuthController::class, 'signup'])->name('signup');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    ///////////////////////////////////////////////
    Route::group(["prefix" => "booking"], function () {
        Route::post('bookingCarsByUser_id_and_car_id_select_only_date_from_to/{userId}/{carId}', [BookingController::class, 'bookingCarsByUser_id_and_car_id_select_only_date_from_to']);
    });
    ///////////////////////////////////////////////////
    // Route::group(["prefix" => "admin"], function () {

    //     Route::get('viewAllBranchActive', [AuthController::class, "viewAllBranchActive"]);
    //     Route::get('index', [AuthController::class, "index"]);
    //     Route::delete('delete/{id}', [AuthController::class, "destroy"]);
    //     Route::post('store', [AuthController::class, "store"]);
    //     Route::get('show/{id}', [AuthController::class, "show"]);
    //     Route::get('showBranch/{roles}', [AuthController::class, "showBranch"]);

    //     Route::put('update/{id}', [AuthController::class, "update"]);
    // });
    /////////////////////////////////////////////////////////
    // Route::group(["prefix" => "car"], function () {
    //     Route::post('addCarAndImage', [CarController::class, 'addCarAndImage']);
    //     Route::post('store', [CarController::class, 'store']);
    //     Route::get('index', [CarController::class, 'index']);
    //     Route::put('update/{id}', [CarController::class, "update"]);

    //     Route::get('getCarWithUserAndPrand', [CarController::class, 'getCarWithUserAndPrand']);
    // });
    /////////////////////////////////////////////////////////////
    // Route::group(["prefix" => "image"], function () {

    //     Route::get('getImageId', [ImageCarBrandController::class, 'getImageId']);
    //     Route::post('uploadImage', [ImageCarBrandController::class, 'uploadImage']);
    //     Route::get('retImage', [ImageCarBrandController::class, 'retImage']);
    //     Route::delete('delete/{id}', [ImageCarBrandController::class, "destroy"]);
    // });
});
Route::group(["prefix" => "admin"], function () {
    Route::get('viewAlluserByRoleName/{name}/{id}', [AuthController::class, "viewAlluserByRoleName"]);
    Route::get('viewAllBranchActive', [AuthController::class, "viewAllBranchActive"]);
    Route::get('viewAlluserDoesNotAdmin', [AuthController::class, "viewAlluserDoesNotAdmin"]);
    Route::get('index', [AuthController::class, "index"]);
    Route::delete('delete/{id}', [AuthController::class, "destroy"]);
    Route::post('store', [AuthController::class, "store"]);
    Route::get('show/{id}', [AuthController::class, "show"]);
    Route::get('showBranch/{roles}', [AuthController::class, "showBranch"]);
    Route::put('update/{id}', [AuthController::class, "update"]);
});
Route::get('index', [AuthController::class, "index"]);
Route::delete('delete/{id}', [AuthController::class, "destroy"]);
Route::post('store', [AuthController::class, "store"]);
Route::get('show/{id}', [AuthController::class, "show"]);
Route::put('update/{id}', [AuthController::class, "update"]);
Route::group(["prefix" => "prand"], function () {
    Route::get('getPrandName', [PrandController::class, 'getPrandName']);
    Route::post('uploadImage', [PrandController::class, 'uploadImage']);
    Route::get('retImage', [PrandController::class, 'retImage']);
    Route::delete('delete/{id}', [PrandController::class, "destroy"]);
});
Route::group(["prefix" => "car"], function () {
    Route::post('addCarAndImage', [CarController::class, 'addCarAndImage']);
    Route::post('store', [CarController::class, 'store']);
    Route::get('index', [CarController::class, 'index']);
    Route::put('update/{id}', [CarController::class, "update"]);
    Route::delete('delete/{id}', [CarController::class, "destroy"]);
    Route::get('show/{id}', [CarController::class, "show"]);
    Route::get('getCarWithUserAndPrand', [CarController::class, 'getCarWithUserAndPrand']);
});
Route::group(["prefix" => "image"], function () {
    Route::get('getImageId', [ImageCarBrandController::class, 'getImageId']);
    Route::post('uploadImage', [ImageCarBrandController::class, 'uploadImage']);
    Route::get('retImage', [ImageCarBrandController::class, 'retImage']);
    Route::delete('delete/{id}', [ImageCarBrandController::class, "destroy"]);
});
Route::group(["prefix" => "booking"], function () {
    Route::get('getAllInformationBookingForOnlyCustomer/{user_id}', [BookingController::class, 'getAllInformationBookingForOnlyCustomer']);
    Route::get('getBookingByBranchId/{branch_id}', [BookingController::class, 'getBookingByBranchId']);
    Route::get('getBookingByBranchIdByCustomerIdForCoustomer/{branch_id}/{customer_id}', [BookingController::class, 'getBookingByBranchIdByCustomerIdForCoustomer']);
    Route::get('getAllInformationBookingForAllCustomer', [BookingController::class, 'getAllInformationBookingForAllCustomer']);
    Route::get('getByIDInformationBookingForAllCustomer/{id}', [BookingController::class, 'getByIDInformationBookingForAllCustomer']);
    Route::post('store', [BookingController::class, 'store']);
    Route::get('index', [BookingController::class, 'index']);
    Route::get('show/{id}', [BookingController::class, 'show']);
    Route::put('update/{id}', [BookingController::class, 'update']);
    Route::put('updateBookingStateByBranch/{id}', [BookingController::class, 'updateBookingStateByBranch']);
    Route::delete('delete/{id}', [BookingController::class, 'destroy']);
    Route::get('bookingwithcaranduserbyId/{user_id}/{car_id}', [BookingController::class, 'bookingwithcaranduserbyId']);
    Route::get('convertdays', [BookingController::class, 'convertdays']);
    Route::get('bookingwithusersId/{user_id}', [BookingController::class, 'bookingwithusersId']);
    Route::post('bookingcarsactive/{userId}/{carId}', [BookingController::class, 'bookingcarsactive']);
    // Route::post('bookingCarsByUser_id_and_car_id_select_only_date_from_to/{userId}/{carId}', [BookingController::class, 'bookingCarsByUser_id_and_car_id_select_only_date_from_to']);



});
Route::group(["prefix" => 'wallet'], function () {
    Route::post('store', [WalletController::class, 'store']);
    Route::get('index', [WalletController::class, 'index']);
});
Route::group(["prefix" => 'transaction_type'], function () {
    Route::post('store', [TransactionTypeController::class, 'store']);
    Route::get('index', [TransactionTypeController::class, 'index']);
});
Route::group(["prefix" => 'transaction_history'], function () {
    Route::post('store', [TransactionHistoryController::class, 'store']);
    Route::get('index', [TransactionHistoryController::class, 'index']);
    Route::post('transfer', [TransactionHistoryController::class, 'transfer']);
    Route::post('diposit', [TransactionHistoryController::class, 'diposit']);
    Route::post('withfdraw', [TransactionHistoryController::class, 'withfdraw']);
});
