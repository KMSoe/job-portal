<?php

namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobOfferRepository;
use Modules\Recruitment\App\Repositories\SkillRepository;

class JobOfferService
{
    private JobOfferRepository $jobOfferRepository;

    public function __construct(JobOfferRepository $jobOfferRepository)
    {
        $this->jobOfferRepository = $jobOfferRepository;
    }

    public function findByParams($request)
    {
        return $this->jobOfferRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->jobOfferRepository->findById($id);
    }

    public function store($data)
    {
        return $this->jobOfferRepository->store($data);
    }
}
