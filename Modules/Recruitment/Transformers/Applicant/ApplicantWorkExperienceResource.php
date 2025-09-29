<?php
namespace Modules\Recruitment\Transformers\Applicant;

use Illuminate\Http\Resources\Json\Resource;

class ApplicantWorkExperienceResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'job_title'        => $this->job_title,
            'company_name'     => $this->company_name,
            'from_date'        => $this->from_date, // Format date for API response
            'to_date'          => $this->to_date,   // Format date for API response
            'is_current'       => $this->is_current,
            'job_description'  => $this->job_description,
            'job_function'     => $this->jobFunction,
            'experience_level' => $this->experienceLevel,
            'country'          => $this->country,
        ];
    }
}
