<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Modules\Recruitment\App\Enums\JobPostingSalaryTypes;
use Modules\Recruitment\App\Enums\JobPostingStatusTypes;
use Modules\Recruitment\App\Enums\JobTypes;
use Modules\Recruitment\App\Enums\WorkArrangementTypes;
use Modules\Recruitment\App\Services\JobPostingService;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\EducationLevel;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Entities\JobPostingTemplate;
use Modules\Recruitment\Entities\Skill;
use Modules\Recruitment\Http\Requests\StoreJobPostingRequest;
use Modules\Recruitment\Transformers\JobPostingResource;
use Nnjeim\World\Models\Currency;

class JobPostingController extends Controller
{
    private $service;

    public function __construct(JobPostingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_postings = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_postings' => $job_postings,
            ],
            'message' => 'success',
        ], 200);
    }

    public function getPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'templates'         => JobPostingTemplate::all(),
                'companies'         => Company::select('id', 'name')->get(),
                'departments'       => Department::select('id', 'name', 'company_id')->get(),
                'designations'      => Designation::select('id', 'name')->get(),
                'experience_levels' => ExperienceLevel::all(),
                'job_functions'     => JobFunction::all(),
                'education_levels'  => EducationLevel::all(),
                'job_types'         => JobTypes::values(),
                'work_arrangements' => WorkArrangementTypes::values(),
                'salary_types'      => JobPostingSalaryTypes::values(),
                'statuses'          => JobPostingStatusTypes::values(),
                'currencies'        => Currency::all(),
                'skills'            => Skill::all(),
            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        $job_posting = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreJobPostingRequest $request)
    {
        $job_posting = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(StoreJobPostingRequest $request, JobPosting $jobPosting)
    {
        $job_posting = $this->service->update($jobPosting, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new JobPostingResource($job_posting),
            ],
            'message' => 'Successfully updated',
        ], 200);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }
}
