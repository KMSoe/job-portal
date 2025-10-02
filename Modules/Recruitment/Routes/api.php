<?php

use Modules\Recruitment\Http\Controllers\Applicant\ApplicantProfileController;
use Modules\Recruitment\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::prefix('/v1/applicant')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    // Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
    // Route::post('password/reset', [AuthController::class, 'resetPassword']);
});


Route::middleware(['auth:applicant'])->prefix('/v1')->group(function () {
    Route::get('applicant/profile', [ApplicantProfileController::class, 'index']);
    Route::put('applicant/profile', [ApplicantProfileController::class, 'update']);
    Route::post('applicant/photo/upload', [ApplicantProfileController::class, 'uploadPhoto']);
});
