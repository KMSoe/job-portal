<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationReviewService;
use Modules\Recruitment\App\Services\JobApplicationTrackingService;
use Modules\Recruitment\Entities\JobApplicationReviewer;
use Modules\Recruitment\Http\Requests\StoreJobApplicationReviewRequest;
use Modules\Recruitment\Transformers\JobApplicationReviewReviewerSideResource;

class JobApplicationReviewController extends Controller
{
    private $service;

    public function __construct(JobApplicationReviewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_application_reviews = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_application_reviews' => $job_application_reviews,
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
        $job_application_review = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_application_review' => new JobApplicationReviewReviewerSideResource($job_application_review),
            ],
            'message' => 'success',
        ], 200);
    }

    public function submitReview(StoreJobApplicationReviewRequest $request, $id)
    {
        $review = JobApplicationReviewer::findOrFail($id);

        if ($review->reviewer_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'You are not authorized to submit this review.',
            ], 403);
        }

        if ($review->status === 'done') {
            return response()->json([
                'message' => 'This review has already been completed.',
            ], 400);
        }

        $review->update([
            'score'   => $request->score,
            'comment' => $request->comment,
            'status'  => $request->status,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Review submitted successfully.',
        ], 200);
    }
}
