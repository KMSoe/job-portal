<?php
namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobApplicationBoardRepository;

class JobApplicationBoardService
{
    private JobApplicationBoardRepository $jobApplicationBoardRepository;

    public function __construct(JobApplicationBoardRepository $jobApplicationBoardRepository)
    {
        $this->jobApplicationBoardRepository = $jobApplicationBoardRepository;
    }

    public function findByParams($request)
    {
        return $this->jobApplicationBoardRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->jobApplicationBoardRepository->findById($id);
    }

    public function findByIdForApplicantSide($id)
    {
        return $this->jobApplicationBoardRepository->findByIdForApplicantSide($id);
    }

    public function store($data)
    {
        return $this->jobApplicationBoardRepository->store($data);
    }

    public function update($jobPosting, $data)
    {
        return $this->jobApplicationBoardRepository->update($jobPosting, $data);
    }

    public function delete($id)
    {
        return $this->jobApplicationBoardRepository->delete($id);
    }

}
