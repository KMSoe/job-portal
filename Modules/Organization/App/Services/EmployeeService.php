<?php
namespace Modules\Organization\App\Services;
use Modules\Organization\App\Repositories\EmployeeRepository;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function findByParams($request)
    {
        return $this->employeeRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->employeeRepository->findById($id);
    }

    public function store($data)
    {
        return $this->employeeRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->employeeRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->employeeRepository->delete($id);
    }

    public function getChecklistItems($employeeId, $data)
    {
        return $this->employeeRepository->getChecklistItems($employeeId, $data);
    }

    public function updateChecklistStatus($id, $data)
    {
        return $this->employeeRepository->updateChecklistStatus($id, $data);
    }
}
