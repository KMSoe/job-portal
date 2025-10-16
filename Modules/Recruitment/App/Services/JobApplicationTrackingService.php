<?php
namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobApplicationTrackingRepository;

class JobApplicationTrackingService
{
    private JobApplicationTrackingRepository $jobApplicationTrackingRepository;

    public function __construct(JobApplicationTrackingRepository $jobApplicationTrackingRepository)
    {
        $this->jobApplicationTrackingRepository = $jobApplicationTrackingRepository;
    }

    public function findById($id)
    {
        return $this->jobApplicationTrackingRepository->findById($id);
    }

    public function updateStatus($job_application, $status)
    {
        return $this->jobApplicationTrackingRepository->updateStatus($job_application, $status);
    }

    public function updateComment($job_application, $recruiter_comment)
    {
        return $this->jobApplicationTrackingRepository->updateComment($job_application, $recruiter_comment);
    }

    public function assignReviewers($job_application_id, $reviewer_ids)
    {
        return $this->jobApplicationTrackingRepository->assignReviewers($job_application_id, $reviewer_ids);
    }

}
