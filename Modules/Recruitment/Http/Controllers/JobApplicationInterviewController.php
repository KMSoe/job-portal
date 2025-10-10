<?php
namespace Modules\Recruitment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Recruitment\App\Services\JobApplicationInterviewService;
use Modules\Recruitment\Http\Requests\JobApplicationInterviewRequest;
use Modules\Recruitment\Transformers\JobApplicationInterviewResource;

class JobApplicationInterviewController extends Controller
{
    private $service;

    public function __construct(JobApplicationInterviewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $interviews = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'interviews' => $interviews,
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(JobApplicationInterviewRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $interview = $this->service->createInterview($validatedData);
            return new JobApplicationInterviewResource($interview);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $interview = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'interview' => new JobApplicationInterviewResource($interview),
            ],
            'message' => 'success',
        ], 200);
    }

    public function update(JobApplicationInterviewRequest $request, $id)
    {
        $validatedData = $request->validated();

        try {
            $interview = $this->service->updateInterview($id, $validatedData);
            return new JobApplicationInterviewResource($interview);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->json([], 204);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'interview_id' => 'required|exists:job_application_interviews,id',
            'score' => 'nullable|integer|min:1|max:10',
            'feedback' => 'nullable|string'
        ]);

        try {
            $interviewer = $this->service->updateFeedback($request->all(), $id);
            return new JobApplicationInterviewResource($interviewer);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
