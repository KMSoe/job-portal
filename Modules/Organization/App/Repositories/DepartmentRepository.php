<?php
namespace Modules\Organization\App\Repositories;

use Illuminate\Support\Str;
use Modules\Organization\Entities\Department;
use Modules\Organization\Transformers\DepartmentResource;

class DepartmentRepository
{

    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = Department::with([
            'company',
            'createdBy'
        ])
            ->where(function ($query) use ($request, $keyword) {
                if ($request->company_id) {
                    $query->where('company_id', $request->company_id);
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

        if ($request->export) {
            if ($request->only_this_page) {
                $data = $data->skip(($request->page - 1) * $perPage)->take($perPage)->get();
            } else {
                $data = $data->get();
            }
        } else {
            $data = $data->paginate($perPage);

            $items = $data->getCollection();

            $items = collect($items)->map(function ($item) {
                return new DepartmentResource($item);
            });

            $data = $data->setCollection($items);
        }

        return $data;
    }

    public function findById($id)
    {
        $department = Department::with([
            'company',
            'createdBy',
        ])->findOrFail($id);

        return $department;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();

        $department = Department::create($data);

        return $department;
    }

    public function update($id, $data)
    {
        $data['updated_by'] = auth()->id();

        $department = Department::findOrFail($id);
        return $department->update($data);
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
    }

}
