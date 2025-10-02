<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationInterviewService;

class JobApplicationInterviewController extends Controller
{
    private $service;

    public function __construct(JobApplicationInterviewService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $this->service->createInterview();

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'success',
        ], 200);
    }

    public function createInterview(Request $request, $job_application_id)
    {

    }
}
