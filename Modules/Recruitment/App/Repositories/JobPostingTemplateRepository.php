<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobPostingTemplate;
use Modules\Recruitment\Transformers\JobPostingTemplateResource;

class JobPostingTemplateRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = JobPostingTemplate::with([
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
            return new JobPostingTemplateResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
        $jobPostingTemplate = JobPostingTemplate::with([
            'company',
            'createdBy',
        ])->findOrFail($id);

        return $jobPostingTemplate;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $jobPostingTemplate            = JobPostingTemplate::create($data);

        return $jobPostingTemplate;
    }

    public function update($jobPostingTemplate, $data)
    {
        $data['updated_by'] = auth()->id();

        return $jobPostingTemplate->update($data);
    }

    public function delete($id)
    {
        $jobPostingTemplate = JobPostingTemplate::findOrFail($id);
        $jobPostingTemplate->delete();
    }

}
