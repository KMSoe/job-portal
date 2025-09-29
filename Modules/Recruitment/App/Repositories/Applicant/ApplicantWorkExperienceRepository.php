<?php
namespace Modules\Recruitment\App\Repositories\Applicant;

use Modules\Recruitment\Entities\ApplicantWorkExperience;

class ApplicantWorkExperienceRepository
{
    public function store($data)
    {
        // Enforce data consistency based on 'is_current' toggle
        if (isset($data['is_current']) && $data['is_current']) {
            $data['to_date'] = null;
        }

        return ApplicantWorkExperience::create($data);
    }

    public function update($id, $data)
    {
        $experience = ApplicantWorkExperience::findOrFail($id);

        // Enforce data consistency on update
        if (isset($data['is_current']) && $data['is_current']) {
            $data['to_date'] = null;
        }

        return $experience->update($data);
    }

    public function delete($id)
    {
        return ApplicantWorkExperience::where('id', $id)->delete();
    }
}
