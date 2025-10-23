<?php

use Illuminate\Support\Facades\Route;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantJobPostingApplicationController;
use Modules\Recruitment\Http\Controllers\JobOfferController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('uploads/{any}', function ($any) {
    return Storage::response($any);
})->where('any', '.*');
// require __DIR__.'/auth.php';

Route::prefix('/v1/applicant')->name('applicant.')->group(function () {
    Route::get('job-offers/{id}/accept-action', [ApplicantJobPostingApplicationController::class, 'markAsOfferAccepted'])
        ->name('job-offer.accept-action')
        ->middleware('signed'); // Crucial for security

    Route::get('job-offers/{id}/decline-action', [ApplicantJobPostingApplicationController::class, 'markedAsOfferDeclined'])
        ->name('job-offer.decline-action')
        ->middleware('signed'); // Crucial for security

    // Route::patch('job-offers/{id}/mark-as-offer-accepted', [JobOfferController::class, 'markAsOfferAccepted'])->name('job-offer.accept');
    // Route::patch('job-offers/{id}/mark-as-offer-declined', [JobOfferController::class, 'markedAsOfferDeclined'])->name('job-offer.decline');
});