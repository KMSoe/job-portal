<?php
namespace Modules\Recruitment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobApplicationTrackingService;
use Modules\Recruitment\Entities\JobApplicationReviewer;
use Modules\Recruitment\Http\Requests\StoreJobApplicationReviewRequest;

class JobApplicationReviewController extends Controller
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

    public function submitReview(StoreJobApplicationReviewRequest $request, JobApplicationReviewer $review)
    {
        if ($review->reviewer_id !== auth()->id()) {
            return response()->json([
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
