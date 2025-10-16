<?php
namespace Modules\Recruitment\App\Repositories\Applicant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Recruitment\App\Enums\JobPostingStatusTypes;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\App\Services\PdfResumeParserService;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Entities\Resume;
use Modules\Recruitment\Transformers\Applicant\ApplicantJobPostingResource;
use Modules\Recruitment\Transformers\Applicant\JobApplicationResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;
use Storage;

class ApplicantJobPostingRepository
{
    private StorageInterface $storage;
    private PdfResumeParserService $pdfResumeParserService;

    public function __construct(LocalStorage $storage, PdfResumeParserService $pdfResumeParserService)
    {
        $this->storage                = $storage;
        $this->pdfResumeParserService = $pdfResumeParserService;
    }

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = JobPosting::publishedAndActive()
            ->with([
                'company',
                'department',
                'designation',
                'template',
                'experienceLevel',
                'jobFunction',
                'minimumEducationLevel',
                'salaryCurrency',
                'skills',
            ])

        // ->whereNotNull('published_at')
            ->where(function ($query) use ($request, $keyword) {
                if (isset($request->job_function_id) && $request->job_function_id != null && $request->job_function_id != '') {
                    $query->where('job_function_id', $request->job_function_id);
                }

                if ($keyword != null && $keyword != '') {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('title', 'LIKE', "%$keyword%")
                            ->orWhere('summary', 'LIKE', "%$keyword%")
                            ->orWhere('roles_and_responsibilities', 'LIKE', "%$keyword%")
                            ->orWhere('requirements', 'LIKE', "%$keyword%");
                    });
                    $query->orWhereHas('company', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    });
                }

                if (isset($request->date_posted) && $request->date_posted != null && $request->date_posted != '') {
                    $dateFilters = explode(',', $request->date_posted);
                    $dates       = [];

                    foreach ($dateFilters as $filter) {
                        switch ($filter) {
                            case 'today':
                                $dates[] = now()->startOfDay();
                                break;
                            case 'last_3_days':
                                $dates[] = now()->subDays(3);
                                break;
                            case 'last_7_days':
                                $dates[] = now()->subDays(7);
                                break;
                            case 'last_14_days':
                                $dates[] = now()->subDays(14);
                                break;
                            case 'last_30_days':
                                $dates[] = now()->subDays(30);
                                break;
                        }
                    }

                    if (! empty($dates)) {
                        $minDate = collect($dates)->min();
                        $query->where('created_at', '>=', $minDate);
                    }
                }

                if (isset($request->companies) && $request->companies != null && $request->companies != '') {
                    $companies = explode(',', $request->companies);
                    $query->whereIn('company_id', $companies);
                }
            });

        if ($request->sort != null && $request->sort != '') {
            $sorts = explode(',', $request->input('sort', ''));

            foreach ($sorts as $sortColumn) {
                $sortDirection = Str::startsWith($sortColumn, '-') ? 'DESC' : 'ASC';
                $sortColumn    = ltrim($sortColumn, '-');

                $data->orderBy($sortColumn, $sortDirection);
            }
        } else {
            $data->orderBy('created_at', 'DESC');
        }

        $data = $data->paginate($perPage);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new ApplicantJobPostingResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function getLatestJobPosting($count)
    {

        $data = JobPosting::publishedAndActive()
            ->with([
                'company',
                'department',
                'designation',
                'template',
                'experienceLevel',
                'jobFunction',
                'minimumEducationLevel',
                'salaryCurrency',
                'skills',
            ])
            ->where('status', JobPostingStatusTypes::PUBLISHED->value)
            ->take($count)->get();
        // ->whereNotNull('published_at')

        return $data;
    }

    public function findById($id)
    {
        $applicantId = auth()->guard('applicant')->id();

        $jobPosting = JobPosting::publishedAndActive()
            ->with([
                'company',
                'department',
                'designation',
                'template',
                'experienceLevel',
                'jobFunction',
                'minimumEducationLevel',
                'salaryCurrency',
                'skills',
            ])
            ->leftJoin('job_applications', function ($join) use ($applicantId) {
                $join->on('job_applications.job_posting_id', '=', 'job_postings.id')
                    ->where('job_applications.applicant_id', '=', $applicantId);
            })
            ->select('job_postings.*')
            ->addSelect('job_applications.id as application_id')
            ->findOrFail($id);

        return $jobPosting;
    }

    public function applyJob($job_posting_id, $request)
    {
        $applicant_id = auth()->guard('applicant')->id();

        $existingApplication = JobApplication::where('job_posting_id', $job_posting_id)
            ->where('applicant_id', $applicant_id)
            ->first();

        if ($existingApplication) {
            throw new \Exception('You have already applied for this job.');
        }

        $resume = Resume::findOrFail($request->resume_id);

        DB::beginTransaction();
        $application = JobApplication::create([
            'job_posting_id'  => $job_posting_id,
            'applicant_id'    => $applicant_id,
            'expected_salary' => $request->expected_salary,
            'resume_id'       => $request->resume_id,
            'status'          => RecruitmentStageTypes::SUBMITTED->value,
            'applied_at'      => now(),
        ]);

        if ($request->supportive_documents) {
            foreach ($request->supportive_documents as $document) {
                $filePath = $this->storage->store('supportive_documents', $document);

                $application->supportiveDocuments()->create([
                    'path'      => $filePath,
                    'filename'  => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                ]);
            }
        }

        // $extract_data = $this->pdfResumeParserService->parse(storage_path("app/" . $resume->file_path));

        // ApplicantResumeExtractData::create([
        //     'job_application_id' => $application->id,
        //     'extract_data'       => $extract_data,
        // ]);

        DB::commit();

        return $application;
    }

    public function getApplications($applicant_id, $request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = JobApplication::with(['jobPosting.company'])
            ->where('job_applications.applicant_id', $applicant_id)
            ->where(function ($query) use ($request, $keyword) {
                if ($request->status != null) {
                    $query->where('job_applications.status', $request->status);
                }

                if ($keyword != '') {
                    $query->whereHas('jobPosting', function ($query) use ($keyword) {
                        $query->where('title', 'LIKE', '%' . $keyword . '%');
                    });
                }

            })
            ->orderByDesc('job_applications.applied_at')
            ->paginate($perPage);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new JobApplicationResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

}
