<?php
namespace Modules\Recruitment\App\Repositories\Applicant;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Transformers\JobPostingResource;

class ApplicantJobPostingRepository
{

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

    public function apply($data)
    {

    }

}
