<?php

use Illuminate\Support\Facades\Route;
use Modules\Organization\Http\Controllers\Api\AuthController;
use Modules\Organization\Http\Controllers\Api\CompanyController;
use Modules\Organization\Http\Controllers\Api\DepartmentController;
use Modules\Organization\Http\Controllers\Api\DesignationController;
use Modules\Organization\Http\Controllers\Api\EmployeeController;
use Modules\Organization\Http\Controllers\Api\WorldController;

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
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:api'])->prefix('/v1')->group(function () {
    Route::post('/hrm/password/change', [AuthController::class, 'changePassword']);
    Route::resource('companies', CompanyController::class);
    Route::get('company-sample-download', [CompanyController::class, 'downloadSampleExcelFile']);
    Route::post('company/import', [CompanyController::class, 'import'])->name('company.import');
    Route::resource('departments', DepartmentController::class);
    Route::get('department-sample-download', [DepartmentController::class, 'downloadSampleExcelFile']);
    Route::post('department/import', [DepartmentController::class, 'import'])->name('department.import');
    Route::get('departments-page-data', [DepartmentController::class, 'getPageData']);
    Route::resource('designations', DesignationController::class);
    Route::get('designation-sample-download', [DesignationController::class, 'downloadSampleExcelFile']);
    Route::post('designation/import', [DesignationController::class, 'import'])->name('designation.import');
    Route::resource('employees', EmployeeController::class);
    Route::get('employees-form-data', [EmployeeController::class, 'formData'])->name('employees.form-data');
    Route::get('onboarding/{employee_id}/checklist-items', [EmployeeController::class, 'getChecklistItems']);
    Route::post('onboarding/checklist-items/{item_id}', [EmployeeController::class, 'updateChecklistStatus']);
});
