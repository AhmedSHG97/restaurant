<?php

use App\Http\Controllers\Api\Auth\UserAuthenticationController;
use App\Http\Controllers\Api\ProblemSolvingController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login',[UserAuthenticationController::class,"login"]);
Route::post("social/login", [UserAuthenticationController::class, 'socialLogin']);
Route::post('/register',[UserAuthenticationController::class,"register"]);
Route::post('/resendVerification',[UserAuthenticationController::class,"resendVerification"]);
Route::post('/verify',[UserAuthenticationController::class,"verifyUser"]);
Route::post('refresh/token',[UserAuthenticationController::class,"refreshToken"]);
Route::post('/reset/password',[UserAuthenticationController::class,"resetPassword"]);
Route::post('/change/password',[UserAuthenticationController::class,"changePassword"]);
Route::get('/getNumbersCount',[ProblemSolvingController::class,"getNumbersCount"]);
Route::get('/getStringIndex',[ProblemSolvingController::class,"getStringIndex"]);

Route::group(['middleware'=>["auth:api"]],function(){
    Route::post('password/update',[UserAuthenticationController::class,"updatePassword"]);
    Route::group(['prefix' => "me"],function(){
        Route::get('info',[UserController::class, "getUserInfo"]);

        Route::post('info/update',[UserController::class, "infoUpdate"]);
    });
    Route::delete("user/delete", [UserController::class, "delete"]);
    Route::get("users/all", [UserController::class, "allUsers"]);
});
