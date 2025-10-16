<?php
namespace Modules\Recruitment\App\Repositories;

use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobApplicationReviewer;

class JobApplicationTrackingRepository
{
    public function findById($id)
    {
        return JobApplication::with(['resume', 'jobPosting.skills'])->findOrFail($id);
    }

    public function updateStatus($job_application, $status)
    {
        return $job_application->update([
            'status'          => $status,
            'last_updated_by' => auth()->id(),
        ]);
    }

    public function updateComment($job_application, $recruiter_comment)
    {
        return $job_application->update([
            'recruiter_comment' => $recruiter_comment,
            'last_updated_by'   => auth()->id(),
        ]);
    }

    public function assignReviewers($job_application_id, $reviewer_ids)
    {
        foreach ($reviewer_ids as $reviewer_id) {
            JobApplicationReviewer::updateOrCreate(
                [
                    'application_id' => $job_application_id,
                    'reviewer_id'    => $reviewer_id,
                ],
                [
                    'status' => 'pending',
                ]
            );
        }

        JobApplicationReviewer::where('application_id', $job_application_id)->whereNotIn('reviewer_id', $reviewer_ids)->delete();
    }
}
