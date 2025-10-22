<?php
namespace Modules\Recruitment\App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Modules\Recruitment\App\Services\JobPostingService;
use Modules\Recruitment\Entities\EducationLevel;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Entities\Skill;
use Modules\Recruitment\Http\Requests\StoreJobPostingRequest;
use Nnjeim\World\Models\Currency;

class JobPostingImport implements WithHeadingRow, SkipsEmptyRows, ToCollection, SkipsOnFailure
{
    use SkipsFailures;
    protected $service;

    public function __construct(JobPostingService $service)
    {
        $this->service = $service;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rules = (new StoreJobPostingRequest())->rules();
            // $rules = [
            //     // Organizational Foreign Keys
            //     'company'                                => 'required|exists:companies,name',
            //     'department'                             => 'required|exists:departments,name',
            //     'designation'                            => 'required|exists:designations,name',

            //     // Job Details
            //     'title'                                  => 'required|string|max:255',
            //     'experience_level'                       => 'required|exists:experience_levels,name',
            //     'job_function'                           => 'required|exists:job_functions,name',
            //     'min_education_level'                    => 'required|exists:education_levels,name',
            //     'summary'                                => 'required|string|max:65535', // longText
            //     'open_to'                                => 'nullable|string|max:255',
            //     'roles_and_responsibilities'             => 'nullable|string|max:65535',
            //     'requirements'                           => 'nullable|string|max:65535',
            //     'what_we_can_offer_include'              => 'sometimes|boolean',

            //     // Conditional "What We Can Offer" Fields (Required if include is true)
            //     'what_we_can_offer_benefits'             => 'required_if:what_we_can_offer_include,true|nullable|string',
            //     'what_we_can_offer_highlights'           => 'required_if:what_we_can_offer_include,true|nullable|string',
            //     'what_we_can_offer_career_opportunities' => 'required_if:what_we_can_offer_include,true|nullable|string',

            //     // Type and Location
            //     'job_type'                               => ['required', Rule::in(JobTypes::values())],
            //     'work_arrangement'                       => ['required', Rule::in(WorkArrangementTypes::values())],

            //     // Conditional Location (Required if hybrid or on-site)
            //     'location'                               => 'required_if:work_arrangement,hybrid,on-site|nullable|string|max:255',

            //     'skills'                                 => 'required',

            //     // Compensation
            //     'salary_type'                            => ['required', Rule::in(JobPostingSalaryTypes::values())],
            //     'salary_currency'                        => 'required_unless:salary_type,Negotiable|nullable|exists:currencies,name',
            //     'salary_notes'                           => 'nullable|string|max:255',

            //     // Conditional Salary Fields
            //     // Required if type is up_to, around, or fixed
            //     'salary_amount'                          => 'required_if:salary_type,Up_To,Around,Fixed|nullable|numeric|min:0',
            //     // Required if type is range
            //     'min_salary'                             => 'required_if:salary_type,Range|nullable|numeric|min:0',
            //     'max_salary'                             => 'required_if:salary_type,Range|nullable|numeric|gt:min_salary',

            //     // Status and Dates
            //     'vacancies'                              => 'required|integer|min:1',
            //     'status'                                 => ['required', Rule::in(JobPostingStatusTypes::values())],
            //     'published_at'                           => 'required|date',
            //     'deadline_date'                          => 'nullable|date|after_or_equal:published_at',
            // ];
            $data = $row->toArray();

            $data['company_id']             = Company::where('name', $row['company'])->first()->id ?? null;
            $data['department_id']          = Department::where('name', $row['department'])->first()->id ?? null;
            $data['designation_id']         = Designation::where('name', $row['designation'])->first()->id ?? null;
            $data['experience_level_id']    = ExperienceLevel::where('name', $row['experience_level'])->first()->id ?? null;
            $data['job_function_id']        = JobFunction::where('name', $row['job_function'])->first()->id ?? null;
            $data['min_education_level_id'] = EducationLevel::where('name', $row['min_education_level'])->first()->id ?? null;

            $skill_ids = [];

            $skill_string = $row['skills'] ?? '';

            $skill_names = array_map('trim', explode(',', $skill_string));
            $skill_names = array_filter($skill_names); // Remove empty values

            foreach ($skill_names as $key => $skill_name) {
                $skill = Skill::where('name', $skill_name)->first();

                if (! $skill) {
                    $skill = Skill::create([
                        'name' => $skill_name,
                    ]);
                }

                $skill_ids[] = $skill->id;
            }

            $data['skill_ids']          = $skill_ids;
            $data['salary_currency_id'] = Currency::where('code', $row['currency_code'])->first()->id ?? null;
            $data['published_at']       = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['published_at'])
                ->format('Y-m-d');
            $data['deadline_date'] = $data['deadline_date'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['deadline_date'])
                ->format('Y-m-d') : null;

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    $this->onFailure(new Failure(
                        $index + 2,
                        $field,
                        $messages,
                        $row->toArray()
                    ));
                }
                continue;
            }

            $this->service->store($data);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
