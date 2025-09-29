<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Transformers\JobPostingResource;

class JobPostingRepository
{

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = JobPosting::with([
            'company',
            'createdBy',
        ])
            ->where(function ($query) use ($keyword) {
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
        $jobPosting = JobPosting::with([
            'company',
            'createdBy',
        ])->findOrFail($id);

        return $jobPosting;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $jobPosting = JobPosting::create($data);

        return $jobPosting;
    }

    public function update($jobPosting, $data)
    {
        $data['updated_by'] = auth()->id();

        return $jobPosting->update($data);
    }

    public function delete($id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $jobPosting->delete();
    }

}
