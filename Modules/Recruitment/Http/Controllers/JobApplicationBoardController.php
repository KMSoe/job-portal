<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationBoardService;
use Modules\Recruitment\Transformers\JobPostingApplicationDetailResource;

class JobApplicationBoardController extends Controller
{
    private $service;

    public function __construct(JobApplicationBoardService $service)
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

    public function getApplicants(Request $request, $job_posting_id)
    {
        $applicants = $this->service->getApplicants($request, $job_posting_id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'applicants' => $applicants,
            ],
            'message' => 'success',
        ], 200);
    }

    public function getApplications(Request $request, $job_posting_id)
    {
        $applications = $this->service->getApplicants($request, $job_posting_id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'applications' => $applications,
            ],
            'message' => 'success',
        ], 200);
    }

    public function getApplicationDetail($job_posting_id, $job_application_id)
    {
        $application = $this->service->getApplicationDetail($job_application_id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'application' => new JobPostingApplicationDetailResource($application),
            ],
            'message' => 'success',
        ], 200);
    }
}
