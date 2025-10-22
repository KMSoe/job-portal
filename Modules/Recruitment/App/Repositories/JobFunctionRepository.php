<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\JobFunction;

class JobFunctionRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = JobFunction::where('is_active', true)
            ->where(function ($query) use ($request, $keyword) {
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

        if ($request->export) {
            $items = $data->get();

            return $items;
        } else {
            $data = $data->paginate($perPage);
        }

        return $data;
    }

    public function findById($id)
    {
        $job_function = JobFunction::findOrFail($id);

        return $job_function;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();

        $job_function = JobFunction::create($data);

        return $job_function;
    }

    public function update($id, $data)
    {
        $data['updated_by'] = auth()->id();

        $job_function = JobFunction::findOrFail($id);
        return $job_function->update($data);
    }

    public function delete($id)
    {
        $job_function = JobFunction::findOrFail($id);
        $job_function->delete();
    }
}
