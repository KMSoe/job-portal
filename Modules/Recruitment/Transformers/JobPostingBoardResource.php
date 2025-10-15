<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPostingBoardResource extends JsonResource
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
            'id'                                     => $this->id,

            // Organizational
            'company_id'                             => $this->company_id,
            'department_id'                          => $this->department_id,
            'designation_id'                         => $this->designation_id,
            'template_id'                            => $this->template_id,

            // Job Details
            'title'                                  => $this->title,
            'slug'                                   => $this->slug,
            'experience_level_id'                    => $this->experience_level_id,
            'job_function_id'                        => $this->job_function_id,
            'min_education_level_id'                 => $this->min_education_level_id,
            'summary'                                => $this->summary,
            'open_to'                                => $this->open_to,
            'roles_and_responsibilities'             => $this->roles_and_responsibilities,
            'requirements'                           => $this->requirements,
            'what_we_can_offer_include'              => (bool) $this->what_we_can_offer_include,
            'what_we_can_offer_benefits'             => $this->what_we_can_offer_benefits,
            'what_we_can_offer_highlights'           => $this->what_we_can_offer_highlights,
            'what_we_can_offer_career_opportunities' => $this->what_we_can_offer_career_opportunities,

            // Type and Location
            'job_type'                               => $this->job_type,
            'work_arrangement'                       => $this->work_arrangement,
            'location'                               => $this->location,

            // Compensation
            'salary_type'                            => $this->salary_type,
            'salary_currency_id'                     => $this->salary_currency_id,
            'salary_amount'                          => $this->salary_amount,
            'min_salary'                             => $this->min_salary,
            'max_salary'                             => $this->max_salary,
            'salary_notes'                           => $this->salary_notes,

            // Status and Dates
            'vacancies'                              => $this->vacancies,
            'status'                                 => $this->status,
            'published_at'                           => $this->published_at ? $this->published_at->toDateTimeString() : null,
            'deadline_date'                          => $this->deadline_date ? $this->deadline_date->toDateTimeString() : null,

            // Auditing
            'created_by'                             => $this->createdBy ? $this->createdBy : [
                "id"   => 0,
                "name" => '',
            ],
            'updated_by'                             => $this->updatedBy ? $this->updatedBy : [
                "id"   => 0,
                "name" => '',
            ],

            // Relationships (Eager Loaded)

            'company'                                => $this->whenLoaded('company'),
            'department'                             => $this->whenLoaded('department'),
            'designation'                            => $this->whenLoaded('designation'),
            'template'                               => $this->whenLoaded('template'),

            // Job detail relationships
            'experience_level'                       => $this->whenLoaded('experienceLevel'),
            'job_function'                           => $this->whenLoaded('jobFunction'),
            'minimum_education_level'                => $this->whenLoaded('minimumEducationLevel'),

            // Compensation relationship
            // This is the direct call you requested, wrapped in whenLoaded
            'salary_currency'                        => $this->whenLoaded('salaryCurrency'),

            // For a collection/many-to-many relationship
            'skills'                                 => $this->whenLoaded('skills'),
            'applicants'                             => $this->applicants,

            'created_at'                             => $this->created_at,
            'updated_at'                             => $this->updated_at,
        ];
    }
}
