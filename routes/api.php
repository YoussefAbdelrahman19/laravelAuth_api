<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//route for email verifiy
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
//public routes
Route::resource('/products', ProductsController::class);
Route::get('/products/{id}',[ProductsController::class,'sow']);
Route::get('/products/search/{name}',[ProductsController::class,'search']);
//for register and login
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
//for password forget and reset
Route::post('forget-password',[NewPasswordController::class,'forgetPassword']);
Route::post('reset-password',[NewPasswordController::class,'reset']);
//for email verification
Route::post('email/verification-notification',[EmailVerificationController::class,'sendVerificationEmail'])->middleware('auth:sanctum');



// protected routes
Route::group(['middleware' =>['auth:sanctum','verified'] ], function () {
    Route::post('/products',[ProductsController::class,'store']);
    Route::put('/products/{id}',[ProductsController::class,'update']);
    Route::delete('/products/{id}',[ProductsController::class,'destroy']);
    Route::post('/logout',[AuthController::class,'logout']);



});
Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
