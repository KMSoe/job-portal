<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\App\Repositories\Applicant\ApplicantJobPostingRepository;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Transformers\Applicant\ApplicantSideJobPostingResource;
use Modules\Storage\App\Classes\LocalStorage;

class ApplicantJobPostingController extends Controller
{
    private $service;
    private $storage;

    public function __construct(ApplicantJobPostingRepository $service, LocalStorage $storage)
    {
        $this->service = $service;
        $this->storage = $storage;
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

    public function show($id)
    {
        $job_posting = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_posting' => new ApplicantSideJobPostingResource($job_posting),
            ],
            'message' => 'success',
        ], 200);
    }

    public function apply(Request $request, $job_posting_id)
    {
        $applicant_id = auth()->guard('applicant')->id();
        $cvFile       = $request->file('cv');
        $cvPath       = $this->storage->store('resumes', $cvFile);

        $application = JobApplication::create([
            'job_posting_id'   => $job_posting_id,
            'applicant_id'     => $applicant_id,
            'expected_salary'  => $request->expected_salary,
            'uploaded_cv_path' => $cvPath,
            'uploaded_cv_name' => $cvFile->getClientOriginalName(),
            'status'           => RecruitmentStageTypes::SUBMITTED->value,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $docPath = $this->storage->store('resumes', $document);

                $application->supportiveDocuments()->create([
                    'path'      => $docPath,
                    'filename'  => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

}
