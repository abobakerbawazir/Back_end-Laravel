<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ImageCarBrandController;
use App\Http\Controllers\PrandController;
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
});
Route::group(["prefix" => "admin"], function () {

    Route::get('viewAllBranchActive', [AuthController::class, "viewAllBranchActive"]);
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
    Route::get('getCarWithUserAndPrand', [CarController::class, 'getCarWithUserAndPrand']);
});
Route::group(["prefix" => "image"], function () {

    Route::get('getImageId', [ImageCarBrandController::class, 'getImageId']);
    Route::post('uploadImage', [ImageCarBrandController::class, 'uploadImage']);
    Route::get('retImage', [ImageCarBrandController::class, 'retImage']);
    Route::delete('delete/{id}', [ImageCarBrandController::class, "destroy"]);
});
