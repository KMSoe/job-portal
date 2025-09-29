<?php

namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\SkillRepository;

class SkillService
{
    private SkillRepository $skillRepository;

    public function __construct(SkillRepository $skillRepository)
    {
        $this->skillRepository = $skillRepository;
    }

    public function findByParams($request)
    {
        return $this->skillRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->skillRepository->findById($id);
    }

    public function store($data)
    {
        return $this->skillRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->skillRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->skillRepository->delete($id);
    }

}
