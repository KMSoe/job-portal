<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\App\Services\JobApplicationTrackingService;

class JobApplicationTrackingController extends Controller
{
    private $service;

    public function __construct(JobApplicationTrackingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $skills = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'skills' => $skills,
            ],
            'message' => 'success',
        ], 200);
    }

    public function pageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        $skill = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'skill' => $skill,
            ],
            'message' => 'success',
        ], 200);
    }

    public function updateStatus(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, $request->status);
    }

    public function makedAsReceived(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, RecruitmentStageTypes::RECEIVED->value);
    }

    public function updateToReviewStage(Request $request, $job_posting_id, $job_application_id)
    {
        $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'reviewer_ids'   => 'required|array|min:1',
            'reviewer_ids.*' => 'required|exists:users,id',
        ]);

        $job_application = $this->service->findById($job_application_id);

        $this->service->assignReviewers($job_application_id, $request->reviewer_ids);
        $this->service->updateStatus($job_application, RecruitmentStageTypes::SCREENING_REVIEW->value);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Reviewer successfully assigned to the job application.',
        ], 200);

    }

    public function updateToShortlistStage(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, RecruitmentStageTypes::SHORTLISTING->value);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function updateToInterviewStage(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, RecruitmentStageTypes::INTERVIEW->value);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function updateToReferneceAndBackgroundCheckStage(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, RecruitmentStageTypes::REFERENCE_BACKGROUND_CHECKS->value);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function updateToOfferStage(Request $request, $job_posting_id, $job_application_id)
    {
        $job_application = $this->service->findById($job_application_id);

        $this->service->updateStatus($job_application, RecruitmentStageTypes::OFFER->value);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }
}
