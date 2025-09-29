<?php

namespace Modules\Recruitment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobPostingService;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Http\Requests\StoreJobPostingRequest;
use Modules\Recruitment\Http\Requests\UpdateJobPostingRequest;
use Modules\Recruitment\Transformers\JobPostingResource;

class JobPostingController extends Controller
{
    private $service;

    public function __construct(JobPostingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_postings = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_postings' => $job_postings,
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
        $job_posting = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreJobPostingRequest $request)
    {

        $job_posting = $this->service->store($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(UpdateJobPostingRequest $request, JobPosting $jobPosting)
    {
        $job_posting = $this->service->update($jobPosting, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'Successfully updated',
        ], 200);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }
}
