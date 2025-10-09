<?php

use Illuminate\Support\Facades\Route;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantJobPostingController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantProfileController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantResumeController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantSkillController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantWorkExperienceController;
use Modules\Recruitment\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\Recruitment\Http\Controllers\Auth\RegisteredUserController;
use Modules\Recruitment\Http\Controllers\GoogleOAuthController;
use Modules\Recruitment\Http\Controllers\JobApplicationBoardController;
use Modules\Recruitment\Http\Controllers\JobApplicationInterviewController;
use Modules\Recruitment\Http\Controllers\JobApplicationReviewController;
use Modules\Recruitment\Http\Controllers\JobApplicationTrackingController;
use Modules\Recruitment\Http\Controllers\JobPostingController;
use Modules\Recruitment\Http\Controllers\JobPostingTemplateController;
use Modules\Recruitment\Http\Controllers\OfferLetterTemplateController;
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
    Route::post('/register', [RegisteredUserController::class, 'applicantRegister']);
    Route::get('verify-email/{id}/{hash}', [RegisteredUserController::class, 'verifyEmail']);
    Route::post('forgot-password', [RegisteredUserController::class, 'forgotPassword']);
    Route::post('reset-password', [RegisteredUserController::class, 'resetPassword']);
});

Route::middleware(['auth:applicant'])->prefix('/v1/applicant')->group(function () {
    Route::get('profile-page-data', [ApplicantProfileController::class, 'getPageData']);
    Route::get('profile', [ApplicantProfileController::class, 'index']);
    Route::put('profile', [ApplicantProfileController::class, 'update']);
    Route::post('photo/upload', [ApplicantProfileController::class, 'uploadPhoto']);
    Route::get('resumes', [ApplicantResumeController::class, 'index']);
    Route::post('resumes', [ApplicantResumeController::class, 'store']);
    Route::delete('resumes/{id}', [ApplicantResumeController::class, 'destroy']);

    Route::post('job-postings/{id}/apply', [ApplicantJobPostingController::class, 'apply']);
    Route::get('applications', [ApplicantJobPostingController::class, 'applications']);

    Route::post('skills', [ApplicantSkillController::class, 'store']);
    Route::resource('work-experiences', ApplicantWorkExperienceController::class);
});

Route::middleware(['auth:api'])->prefix('/v1')->group(function () {
    Route::resource('skills', SkillController::class);
});

Route::prefix('/v1')->group(function () {
    Route::get('job-postings', [ApplicantJobPostingController::class, 'index']);
    Route::get('job-postings/{id}', [ApplicantJobPostingController::class, 'show']);
    Route::get('career-page-data', [ApplicantJobPostingController::class, 'getCareerPageData']);
});

Route::middleware(['auth:api'])->prefix('/v1/recruitment')->group(function () {
    Route::resource('skills', SkillController::class);

    Route::resource('job-posting-templates', JobPostingTemplateController::class);
    Route::get('job-posting-templates-page-data', [JobPostingTemplateController::class, 'getPageData']);
    Route::resource('job-postings', JobPostingController::class);
    Route::get('job-postings-page-data', [JobPostingController::class, 'getPageData']);
    Route::get('job-postings/{job_posting_id}/applicants', [JobApplicationBoardController::class, 'getApplicants']);
    Route::get('job-postings/{job_posting_id}/job-applications', [JobApplicationBoardController::class, 'getApplications']);
    Route::get('job-postings/{job_posting_id}/job-applications/{job_application_id}', [JobApplicationBoardController::class, 'getApplicationDetail']);

    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/mark-as-received', [JobApplicationTrackingController::class, 'markAsReceived']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-review-state', [JobApplicationTrackingController::class, 'updateToReviewStage']);
    Route::post('job-postings/{job_posting_id}/job-applications/{job_application_id}/reviewers', [JobApplicationTrackingController::class, 'assignReviewers']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-shortlist-stage', [JobApplicationTrackingController::class, 'updateToShortlistStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-interview-stage', [JobApplicationTrackingController::class, 'updateToInterviewStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-reference-and-background-check-stage', [JobApplicationTrackingController::class, 'updateToReferneceAndBackgroundCheckStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-offer-stage', [JobApplicationTrackingController::class, 'updateToOfferStage']);

    Route::get('job-application-reviews', [JobApplicationReviewController::class, 'index']);
    Route::get('job-application-reviews/{id}', [JobApplicationReviewController::class, 'show']);
    Route::post('job-application-reviews/{id}/review', [JobApplicationReviewController::class, 'submitReview']);

    Route::resource('offer-letter-templates', OfferLetterTemplateController::class);
    Route::get('offer-letter-templates-page-data', [OfferLetterTemplateController::class, 'getPageData']);

    // Google OAuth
    Route::get('/auth/google', [GoogleOAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleOAuthController::class, 'callback']);

    // Application Interview
    Route::resource('job-interviews', JobApplicationInterviewController::class);
    Route::post('interview-feedback/{id}', [JobApplicationInterviewController::class, 'updateFeedback']);
});
