<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\TestResumeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorldDataController;
use Illuminate\Http\Request;
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

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1/')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::get('countries', [WorldDataController::class, 'getAllCountries']);
    Route::get('cities', [WorldDataController::class, 'getAllCities']);
    Route::get('states', [WorldDataController::class, 'getAllStates']);
    Route::get('currencies', [WorldDataController::class, 'getAllCurrencies']);
    Route::get('timezones', [WorldDataController::class, 'getAllTimezones']);

    // Testing
    Route::get('resume', [TestResumeController::class, 'index']);
    Route::get('email', [TestEmailController::class, 'index']);

});

Route::middleware(['auth:api'])->prefix('v1/')->group(function () {
    Route::post('files', [FileController::class, 'store']);
});
