<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Transformers\JobPostingApplicantResource;
use Modules\Recruitment\Transformers\JobPostingBoardResource;

class JobApplicationBoardRepository
{

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = JobPosting::with([
            'company',
            'department',
            'designation',
            'applicants',
        ])
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
            return new JobPostingBoardResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
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
            'applicants',
        ])->findOrFail($id);

        return $jobPosting;
    }

    public function getApplicants($request, $job_posting_id)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $skill_ids = collect(explode(",", $request->skills))->filter(function ($skill) {
            return $skill;
        })->values();

        $experience_level_ids = collect(explode(",", $request->experience_levels))->filter(function ($experience_level) {
            return $experience_level;
        })->values();

        $data = JobApplication::with(['applicant.skills', 'applicant.experienceLevel', 'resume', 'supportiveDocuments'])
            ->where('job_applications.job_posting_id', $job_posting_id)
            ->where(function ($query) use ($request, $skill_ids, $experience_level_ids, $keyword) {
                if ($request->status != null) {
                    $query->where('job_applications.status', $request->status);
                }

                if (count($skill_ids) > 0 && strtolower($request->skills) != 'all') {
                    $query->whereHas('applicant.skills', function ($query) use ($skill_ids) {
                        $query->whereIn('id', $skill_ids);
                    });
                }

                if (count($experience_level_ids) > 0 && strtolower($request->experience_levels) != 'all') {
                    $query->whereHas('applicant.experienceLevel', function ($query) use ($experience_level_ids) {
                        $query->whereIn('id', $experience_level_ids);
                    });
                }

                if ($keyword != '') {
                    $query->whereHas('applicant', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    })
                        ->orWhereHas('applicant.skills', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', '%' . $keyword . '%');
                        });
                }

            })
            ->orderByDesc('job_applications.applied_at')
            ->paginate($perPage);

        $items = $data->getCollection();

        $items = collect($items)->map(function ($item) {
            return new JobPostingApplicantResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function getApplicationDetail($job_application_id)
    {
        $job_application = JobApplication::with([
            'jobPosting',
            'applicant.skills',
            'applicant.salaryCurrency',
            'resume',
            'supportiveDocuments',
            'extractedData',
            'reviewers.reviewer',
            'interviews.interviewers',
            'jobOffer' => function ($query) {
                $query->with([
                    'company',
                    'department',
                    'designation',
                    'template',
                    'salaryCurrency',
                    'approver',
                    'approverPosition',

                    // Has Many / Belongs To Many Relationships
                    'attachments',
                    'informedDepartments',
                    'ccUsers',
                    'bccUsers',
                ]);
            },
        ])
            ->findOrFail($job_application_id);

        return $job_application;
    }

    public function findByIdForApplicantSide($id)
    {
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
        ])->findOrFail($id);

        return $jobPosting;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $jobPosting         = JobPosting::create($data);

        $jobPosting->skills()->sync($data['skill_ids']);

        return $jobPosting;
    }

    public function update($jobPosting, $data)
    {
        $data['updated_by'] = auth()->id();

        $jobPosting->skills()->sync($data['skill_ids']);

        return $jobPosting->update($data);
    }

    public function delete($id)
    {
        $jobPosting = JobPosting::findOrFail($id);

        $jobPosting->skills()->sync([]);
        $jobPosting->delete();
    }

}
