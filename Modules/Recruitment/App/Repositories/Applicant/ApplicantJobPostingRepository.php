<?php
namespace Modules\Recruitment\App\Repositories\Applicant;

use Illuminate\Support\Str;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Transformers\JobPostingResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

class ApplicantJobPostingRepository
{
    private StorageInterface $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = JobPosting::with([
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
            ->whereNotNull('published_at')
            ->where(function ($query) use ($request, $keyword) {
                if ($request->company_id) {
                    $query->where('company_id', $request->company_id);
                }

                if ($request->department_id) {
                    $query->where('department_id', $request->department_id);
                }

                if ($request->designation_id) {
                    $query->where('designation_id', $request->designation_id);
                }

                if ($keyword != '') {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%")
                            ->orWhere('description', 'LIKE', "%$keyword%");
                    });
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
            return new JobPostingResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
        $applicantId = auth()->guard('applicant')->id();

        $jobPosting = JobPosting::with([
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

        return $application;
    }

    public function getApplications($applicant_id, $request)
    {
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = JobPosting::with([
            'company',
            'department',
            'designation',
            'template',
            'experienceLevel',
            'jobFunction',
            'minimumEducationLevel',
            'salaryCurrency',
            'skills',
            'applications' => function ($query) use ($applicant_id) {
                $query->where('applicant_id', $applicant_id);
            },
        ])
            ->join('job_applications', 'job_applications.job_posting_id', '=', 'job_postings.id')
            ->where('job_applications.applicant_id', $applicant_id)
            ->where(function ($query) use ($request) {
                if ($request->status) {
                    $query->where('job_applications.status', $request->status);
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
            $data->orderBy('job_applications.applied_at', 'DESC');
        }

        $data = $data->select('job_postings.*', 'job_applications.status AS application_status')->paginate($perPage);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new JobPostingResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

}
