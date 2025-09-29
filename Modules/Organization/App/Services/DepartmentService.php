<?php
namespace Modules\Organization\App\Services;

use Modules\Organization\App\Repositories\DepartmentRepository;

class DepartmentService
{

    private DepartmentRepository $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function findByParams($request)
    {
        return $this->departmentRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->departmentRepository->findById($id);
    }

    public function store($data)
    {
        return $this->departmentRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->departmentRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->departmentRepository->delete($id);
    }
}
