<?php
namespace Modules\Recruitment\App\Services;

use App\Models\User;
use Modules\Recruitment\Entities\JobApplicationReviewer;
use Modules\Recruitment\Transformers\JobApplicationReviewReviewerSideResource;

class JobApplicationReviewService
{
    public function findByParams($request)
    {
        $user = auth()->user();

        $per_page = $request->input('per_page', 20);
        $keyword  = $request->search ?? '';

        $query = JobApplicationReviewer::with(['application.applicant', 'application.jobPosting', 'application.resume', 'application.supportiveDocuments'])
            ->where('reviewer_id', $user->id);

        if ($keyword != '') {
            $query->whereHas('application.applicant', function ($subquery) use ($keyword) {
                $subquery->where('name', 'LIKE', "%$keyword%");
            })
                ->orWhereHas('application.jobPosting', function ($subquery) use ($keyword) {
                    $subquery->where('title', 'LIKE', "%$keyword%");
                });
        }

        $data = $query->orderByDesc('created_at')->paginate($per_page);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new JobApplicationReviewReviewerSideResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
        $user = auth()->user();

        return JobApplicationReviewer::with(['application.applicant', 'application.jobPosting', 'application.resume', 'application.supportiveDocuments'])
            ->where('reviewer_id', $user->id)
            ->findOrFail($id);
    }
}
