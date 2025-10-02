<?php

use Modules\Organization\Http\Controllers\Api\AuthController;
use Modules\Organization\Http\Controllers\Api\CompanyController;
use Modules\Organization\Http\Controllers\Api\DepartmentController;
use Modules\Organization\Http\Controllers\Api\DesignationController;

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
Route::prefix('/v1/hrm')->name('api.auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum'])->prefix('/v1')->group(function () {
    Route::post('/hrm/password/change', [AuthController::class, 'changePassword']);
    Route::resource('companies', CompanyController::class);
    Route::resource('departments', DepartmentController::class);
    Route::get('departments-page-data', [DepartmentController::class, 'getPageData']);
    Route::resource('designations', DesignationController::class);
});
