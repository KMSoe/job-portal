<?php
namespace Modules\Recruitment\App\Services\Applicant;

use Modules\Recruitment\App\Repositories\Applicant\ApplicantWorkExperienceRepository;

class ApplicantWorkExperienceService
{
    private ApplicantWorkExperienceRepository $applicantWorkExperienceRepository;

    public function __construct(ApplicantWorkExperienceRepository $applicantWorkExperienceRepository)
    {
        $this->applicantWorkExperienceRepository = $applicantWorkExperienceRepository;
    }

    public function store($data)
    {
        return $this->applicantWorkExperienceRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->applicantWorkExperienceRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->applicantWorkExperienceRepository->delete($id);
    }

}
