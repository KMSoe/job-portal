<?php
namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobFunctionRepository;

class JobFunctionService
{
    private JobFunctionRepository $jobFunctionRepository;

    public function __construct(JobFunctionRepository $jobFunctionRepository)
    {
        $this->$jobFunctionRepository = $jobFunctionRepository;
    }

    public function findByParams($request)
    {
        return $this->jobFunctionRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->jobFunctionRepository->findById($id);
    }

    public function store($data)
    {
        return $this->jobFunctionRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->jobFunctionRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->jobFunctionRepository->delete($id);
    }

}
