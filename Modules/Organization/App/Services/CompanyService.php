<?php
namespace Modules\Organization\App\Services;

use Modules\Organization\App\Repositories\CompanyRepository;

class CompanyService
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function findByParams($request)
    {
        return $this->companyRepository->findByParams($request);
    }

    public function findById($id)
    {
        return $this->companyRepository->findById($id);
    }

    public function store($data)
    {
        return $this->companyRepository->store($data);
    }

    public function update($company, $data)
    {
        return $this->companyRepository->update($company, $data);
    }

    public function delete($id)
    {
        return $this->companyRepository->delete($id);
    }

}
