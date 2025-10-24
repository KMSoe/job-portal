<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Transformers\JobPostingResource;

class JobPostingRepository
{

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $company_ids = collect(explode(",", $request->company_id))->filter(function ($company_id) {
            return $company_id;
        })->values();

        $department_ids = collect(explode(",", $request->department_id))->filter(function ($department_id) {
            return $department_id;
        })->values();

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
            'applicants',
        ])
            ->withCount(['applicants'])
            ->where(function ($query) use ($request, $company_ids, $department_ids, $keyword) {
                if (count($company_ids) > 0 && strtolower($request->company_id) != 'all') {
                    $query->whereIn('company_id', $company_ids);
                }

                if (count($department_ids) > 0 && strtolower($request->department_id) != 'all') {
                    $query->whereIn('department_id', $department_ids);
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
        ])
            ->withCount(['applicants'])
            ->findOrFail($id);

        return $jobPosting;
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

    public function checkUsage($id)
    {
        $count = JobApplication::where('job_posting_id', $id)->count();

        return $count ? true : false;
    }

}
