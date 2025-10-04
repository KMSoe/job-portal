<?php

use Illuminate\Support\Facades\Route;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantJobPostingController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantProfileController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantResumeController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantSkillController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantWorkExperienceController;
use Modules\Recruitment\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\Recruitment\Http\Controllers\GoogleOAuthController;
use Modules\Recruitment\Http\Controllers\JobApplicationInterviewController;
use Modules\Recruitment\Http\Controllers\JobApplicationTrackingController;
use Modules\Recruitment\Http\Controllers\JobPostingController;
use Modules\Recruitment\Http\Controllers\JobPostingTemplateController;
use Modules\Recruitment\Http\Controllers\SkillController;

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

Route::middleware(['auth:applicant'])->prefix('/v1/applicant')->group(function () {
    Route::get('profile-page-data', [ApplicantProfileController::class, 'getPageData']);
    Route::get('profile', [ApplicantProfileController::class, 'index']);
    Route::put('profile', [ApplicantProfileController::class, 'update']);
    Route::post('photo/upload', [ApplicantProfileController::class, 'uploadPhoto']);
    Route::post('resumes', [ApplicantResumeController::class, 'store']);
    Route::delete('resumes/{id}', [ApplicantResumeController::class, 'destroy']);

    Route::post('job-postings/{id}/apply', [ApplicantJobPostingController::class, 'apply']);
    Route::get('applications', [ApplicantJobPostingController::class, 'applications']);

    Route::post('skills', [ApplicantSkillController::class, 'store']);
    Route::resource('work-experiences', ApplicantWorkExperienceController::class);
});

Route::middleware(['auth:api'])->prefix('/v1')->group(function () {
    Route::resource('skills', SkillController::class);

    Route::get('job-postings', [ApplicantJobPostingController::class, 'index']);
    Route::get('job-postings/{id}', [ApplicantJobPostingController::class, 'show']);
});


Route::middleware(['auth:api'])->prefix('/v1/recruitment')->group(function () {
    Route::resource('skills', SkillController::class);

    Route::resource('job-posting-templates', JobPostingTemplateController::class);
    Route::get('job-posting-templates-page-data', [JobPostingTemplateController::class, 'getPageData']);
    Route::resource('job-postings', JobPostingController::class);
    Route::get('job-postings-page-data', [JobPostingController::class, 'getPageData']);

    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/mark-as-received', [JobApplicationTrackingController::class, 'makedAsReceived']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-review-state', [JobApplicationTrackingController::class, 'updateToReviewStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-shortlist-stage', [JobApplicationTrackingController::class, 'updateToShortlistStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-interview-stage', [JobApplicationTrackingController::class, 'updateToInterviewStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-reference-and-background-check-stage', [JobApplicationTrackingController::class, 'updateToReferneceAndBackgroundCheckStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-offer-stage', [JobApplicationTrackingController::class, 'updateToOfferStage']);

    // Google OAuth
    Route::get('/auth/google', [GoogleOAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleOAuthController::class, 'callback']);
    
    // Application Interview
    Route::resource('job-interviews', JobApplicationInterviewController::class);
    Route::post('interview-feedback/{id}', [JobApplicationInterviewController::class, 'updateFeedback']);
});
