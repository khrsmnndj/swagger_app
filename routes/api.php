<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilotController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v0')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () 
    {
        Route::get('/user', 'authUser');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::apiResource('cars', CarsController::class);
    Route::apiResource('pilots', PilotController::class);

});
