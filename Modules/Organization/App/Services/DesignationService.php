<?php

namespace Modules\Organization\App\Services;

use Modules\Organization\App\Repositories\DesignationRepository;

class DesignationService
{
    
    private DesignationRepository $designationRepository;

    public function __construct(DesignationRepository $designationRepository)
    {
        $this->designationRepository = $designationRepository;
    }

    public function findByParams($request)
    {
        return $this->designationRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->designationRepository->findById($id);
    }

    public function store($data)
    {
        return $this->designationRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->designationRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->designationRepository->delete($id);
    }

}
