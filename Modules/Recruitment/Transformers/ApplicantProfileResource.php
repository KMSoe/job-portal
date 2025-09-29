<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ApplicantProfileResource extends Resource
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
            'name'             => $this->name,
            'email'            => $this->email,

            'photo_url'        => $this->photo_url,

            'job_title'        => $this->job_title,
            'phone_dial_code'  => $this->phone_dial_code,
            'phone_no'         => $this->phone_no,
            'open_to_work'     => $this->open_to_work,
            'experience_level' => $this->experienceLevel,
            'job_function'     => $this->jobFunction,
            'salary_currency'  => $this->salaryCurrency,
            'expected_salary'  => $this->expected_salary,
            'workExperiences'  => $this->workExperiences,
            'skills'           => $this->skills,
        ];
    }
}
