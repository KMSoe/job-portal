<?php
namespace Modules\Recruitment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationTrackingService;

class JobApplicationInterviewController extends Controller
{
    private $service;

    public function __construct(JobApplicationTrackingService $service)
    {
        $this->service = $service;
    }

    

    public function store(Request $request, $job_application_id)
    {

    }

    public function createInterview(Request $request, $job_application_id)
    {

    }
}
