<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationBoardService;
use Modules\Recruitment\Http\Requests\StoreSkillRequest;
use Modules\Recruitment\Http\Requests\UpdateSkillRequest;

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

    }
}
