<?php
namespace Modules\Recruitment\App\Repositories;

use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobApplicationReviewer;

class JobApplicationTrackingRepository
{
    public function findById($id)
    {
        return JobApplication::findOrFail($id);
    }

    public function updateStatus($job_application, $status)
    {
        $data['updated_by'] = auth()->id();

        return $job_application->update([
            'status' => $status,
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
    }
}
