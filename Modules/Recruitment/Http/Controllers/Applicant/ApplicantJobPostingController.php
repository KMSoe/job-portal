<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Organization\Entities\Company;
use Modules\Recruitment\App\Enums\JobPostingStatusTypes;
use Modules\Recruitment\App\Repositories\Applicant\ApplicantJobPostingRepository;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Http\Requests\JobApplyRequest;
use Modules\Recruitment\Transformers\Applicant\ApplicantSideJobPostingDetailResource;
use Modules\Recruitment\Transformers\JobPostingResource;

class ApplicantJobPostingController extends Controller
{
    private $service;

    public function __construct(ApplicantJobPostingRepository $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_postings = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'total_job_postings' => JobPosting::where('status', JobPostingStatusTypes::PUBLISHED)->count(),
                'job_postings'       => $job_postings,
            ],
            'message' => 'success',
        ], 200);
    }

    public function getCareerPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'num_of_job_postings' => JobPosting::where('status', JobPostingStatusTypes::PUBLISHED)->count(),
                'num_of_companies'    => Company::count(),
                'num_of_candicates'   => Applicant::count(),
                'latest_job_postings' => JobPostingResource::collection($this->service->getLatestJobPosting(6)),
            ],
            'message' => 'success',
        ], 200);
    }

    public function getFilterData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'job_functions' => JobFunction::select('id', 'name')->get(),
                'date_posted'    => [
                    ['value' => 'today', 'label' => 'Today'],
                    ['value' => 'last_3_days', 'label' => 'Last 3 Days'],
                    ['value' => 'last_7_days', 'label' => 'Last 7 Days'],
                    ['value' => 'last_14_days', 'label' => 'Last 14 Days'],
                    ['value' => 'last_30_days', 'label' => 'Last 30 Days'],
                ],
                'companies'      => Company::select('id', 'name')->get(),
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
                'job_posting' => new ApplicantSideJobPostingDetailResource($job_posting),
            ],
            'message' => 'success',
        ], 200);
    }

    public function apply(JobApplyRequest $request, $job_posting_id)
    {
        try {
            $this->service->applyJob($job_posting_id, $request);

            return response()->json([
                'status'  => true,
                'message' => 'Job Applied Successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function applications(Request $request)
    {
        $applicant_id = auth()->guard('applicant')->id();
        $applications = $this->service->getApplications($applicant_id, $request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'applications' => $applications,
            ],
            'message' => 'success',
        ], 200);
    }
}
