<?php

use Illuminate\Support\Facades\Route;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantJobPostingController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantProfileController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantResumeController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantSkillController;
use Modules\Recruitment\Http\Controllers\Applicant\ApplicantWorkExperienceController;
use Modules\Recruitment\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\Recruitment\Http\Controllers\Auth\RegisteredUserController;
use Modules\Recruitment\Http\Controllers\ChecklistTemplateController;
use Modules\Recruitment\Http\Controllers\GoogleOAuthController;
use Modules\Recruitment\Http\Controllers\JobApplicationBoardController;
use Modules\Recruitment\Http\Controllers\JobApplicationInterviewController;
use Modules\Recruitment\Http\Controllers\JobApplicationReviewController;
use Modules\Recruitment\Http\Controllers\JobApplicationTrackingController;
use Modules\Recruitment\Http\Controllers\JobOfferAttachmentController;
use Modules\Recruitment\Http\Controllers\JobOfferController;
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
    Route::get('verify-email/{id}', [RegisteredUserController::class, 'verifyEmail']);
    Route::get('resend-otp/{id}', [RegisteredUserController::class, 'resendOtp']);
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
    Route::patch('resumes/{id}/default', [ApplicantResumeController::class, 'setDefault']);

    Route::post('job-postings/{id}/apply', [ApplicantJobPostingController::class, 'apply']);
    Route::get('applications', [ApplicantJobPostingController::class, 'applications']);

    Route::post('skills', [ApplicantSkillController::class, 'store']);
    Route::resource('work-experiences', ApplicantWorkExperienceController::class);
});

Route::middleware(['auth:api,applicant'])->prefix('/v1')->group(function () {
    Route::resource('skills', SkillController::class);
});

// Route::middleware(['auth:applicant'])->prefix('/v1')->group(function () {
//     Route::resource('skills', SkillController::class);
// });

Route::prefix('/v1')->group(function () {
    Route::get('job-postings', [ApplicantJobPostingController::class, 'index']);
    Route::get('job-postings/{id}', [ApplicantJobPostingController::class, 'show']);
    Route::get('career-page-data', [ApplicantJobPostingController::class, 'getCareerPageData']);
    Route::get('job-posting-filters', [ApplicantJobPostingController::class, 'getFilterData']);

    Route::get('/auth/google/callback', [GoogleOAuthController::class, 'callback']);
});

Route::middleware(['auth:api'])->prefix('/v1/recruitment')->group(function () {
    Route::resource('skills', SkillController::class);

    Route::resource('job-posting-templates', JobPostingTemplateController::class);
    Route::get('job-posting-templates-page-data', [JobPostingTemplateController::class, 'getPageData']);
    Route::resource('job-postings', JobPostingController::class);
    Route::get('job-postings-page-data', [JobPostingController::class, 'getPageData']);
    Route::get('job-postings-detail-page-data', [JobApplicationBoardController::class, 'getPageData']);
    Route::get('job-postings/{job_posting_id}/applicants', [JobApplicationBoardController::class, 'getApplicants']);
    Route::get('job-postings/{job_posting_id}/job-applications', [JobApplicationBoardController::class, 'getApplications']);
    Route::get('job-postings/{job_posting_id}/job-applications/{job_application_id}', [JobApplicationBoardController::class, 'getApplicationDetail']);

    Route::post('job-postings/{job_posting_id}/job-applications/{job_application_id}/parse-resume', [JobApplicationTrackingController::class, 'parseResume']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/mark-as-received', [JobApplicationTrackingController::class, 'markAsReceived']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-review-state', [JobApplicationTrackingController::class, 'updateToReviewStage']);
    Route::post('job-postings/{job_posting_id}/job-applications/{job_application_id}/reviewers', [JobApplicationTrackingController::class, 'assignReviewers']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-shortlist-stage', [JobApplicationTrackingController::class, 'updateToShortlistStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-assessment-testing-stage', [JobApplicationTrackingController::class, 'updateToAssessmentTesting']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-interview-stage', [JobApplicationTrackingController::class, 'updateToInterviewStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-evaluation-selection', [JobApplicationTrackingController::class, 'updateToEvaluationSelection']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-reference-and-background-check-stage', [JobApplicationTrackingController::class, 'updateToReferneceAndBackgroundCheckStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-offer-stage', [JobApplicationTrackingController::class, 'updateToOfferStage']);
    Route::patch('job-postings/{job_posting_id}/job-applications/{job_application_id}/update-to-onboarding-stage', [JobApplicationTrackingController::class, 'updateToOnboardingStage']);

    Route::get('job-application-reviews', [JobApplicationReviewController::class, 'index']);
    Route::get('job-application-reviews/{id}', [JobApplicationReviewController::class, 'show']);
    Route::post('job-application-reviews/{id}/review', [JobApplicationReviewController::class, 'submitReview']);

    Route::resource('offer-letter-templates', OfferLetterTemplateController::class);
    Route::get('offer-letter-templates-page-data', [OfferLetterTemplateController::class, 'getPageData']);

    Route::post('job-offer-attachments', [JobOfferAttachmentController::class, 'store']);
    Route::post('job-applications/{job_application_id}/job-offers', [JobOfferController::class, 'store']);

    Route::get('job-offers', [JobOfferController::class, 'index']);
    Route::get('job-offers-page-data', [JobOfferController::class, 'getPageData']);
    Route::get('job-offers/{id}', [JobOfferController::class, 'show']);
    Route::put('job-applications/{job_application_id}/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::post('job-offers/{id}/approver-signature', [JobOfferController::class, 'uploadSignature']);
    Route::post('job-offers/{id}/send', [JobOfferController::class, 'send']);
    Route::patch('job-offers/{id}/mark-as-offer-accepted', [JobOfferController::class, 'markAsOfferAccepted']);
    Route::patch('job-offers/{id}/mark-as-offer-declined', [JobOfferController::class, 'markedAsOfferDeclined']);

    Route::resource('checklist-templates', ChecklistTemplateController::class);
    Route::delete('checklist-template/bulk-delete', [ChecklistTemplateController::class, 'bulkDelete']);
    Route::put('checklist-template-items/{id}/update', [ChecklistTemplateController::class, 'updateItem']);
    Route::delete('checklist-template-items/{id}/delete', [ChecklistTemplateController::class, 'destroyItem']);

    // Google OAuth
    Route::get('/auth/google', [GoogleOAuthController::class, 'redirect']);

    // Application Interview
    Route::resource('job-interviews', JobApplicationInterviewController::class);
    Route::post('interview-feedback', [JobApplicationInterviewController::class, 'updateFeedback']);
});
