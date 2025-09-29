<?php
namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\JobPostingTemplateRepository;

class JobPostingTemplateService
{

    private JobPostingTemplateRepository $jobPostingTemplateRepostitory;

    public function __construct(JobPostingTemplateRepository $jobPostingTemplateRepostitory)
    {
        $this->jobPostingTemplateRepostitory = $jobPostingTemplateRepostitory;
    }

    public function findByParams($request)
    {
        return $this->jobPostingTemplateRepostitory->findByParams($request);
    }

    public function findById($id)
    {
        return $this->jobPostingTemplateRepostitory->findById($id);
    }

    public function store($data)
    {
        return $this->jobPostingTemplateRepostitory->store($data);
    }

    public function update($jobPostingTemplate, $data)
    {
        return $this->jobPostingTemplateRepostitory->update($jobPostingTemplate, $data);
    }

    public function delete($id)
    {
        return $this->jobPostingTemplateRepostitory->delete($id);
    }
}
