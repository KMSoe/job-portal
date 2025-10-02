<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Organization\Entities\Company;
use Modules\Recruitment\App\Services\JobPostingTemplateService;
use Modules\Recruitment\Entities\JobPostingTemplate;
use Modules\Recruitment\Http\Requests\StoreJobPostingTemplateRequest;
use Modules\Recruitment\Http\Requests\UpdateJobPostingTemplateRequest;
use Modules\Recruitment\Transformers\JobPostingTemplateResource;

class JobPostingTemplateController extends Controller
{
    private $service;

    public function __construct(JobPostingTemplateService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_posting_templates = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting_templates' => $job_posting_templates,
            ],
            'message' => 'success',
        ], 200);
    }

    public function pageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'companies' => Company::select('id', 'name')->get(),
            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        $job_posting_template = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting_template' => new JobPostingTemplateResource($job_posting_template),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreJobPostingTemplateRequest $request)
    {

        $job_posting_template = $this->service->store($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting_template' => new JobPostingTemplateResource($job_posting_template),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(UpdateJobPostingTemplateRequest $request, JobPostingTemplate $jobPostingTemplate)
    {
        $job_posting_template = $this->service->update($jobPostingTemplate, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting_template' => new JobPostingTemplateResource($job_posting_template),
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
