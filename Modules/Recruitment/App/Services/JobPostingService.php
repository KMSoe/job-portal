<?php

namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobPostingRepository;

class JobPostingService
{
    private JobPostingRepository $jobPostingRepostitory;

    public function __construct(JobPostingRepository $jobPostingRepostitory)
    {
        $this->jobPostingRepostitory = $jobPostingRepostitory;
    }

    public function findByParams($request)
    {
        return $this->jobPostingRepostitory->findByParams($request);
    }

    public function findById($id)
    {
        return $this->jobPostingRepostitory->findById($id);
    }

    public function store($data)
    {
        return $this->jobPostingRepostitory->store($data);
    }

    public function update($jobPosting, $data)
    {
        return $this->jobPostingRepostitory->update($jobPosting, $data);
    }

    public function delete($id)
    {
        return $this->jobPostingRepostitory->delete($id);
    }

}
