<?php
namespace Modules\Organization\App\Repositories;

use Modules\Organization\Entities\Designation;
use Modules\Organization\Transformers\DesignationResource;

class DesignationRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = Designation::where(function ($query) use ($request, $keyword) {
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
            $items = isset($request->only_this_page) && $request->only_this_page == 1
                ? $data->skip(($request->page - 1) * $perPage)->take($perPage)->get()
                : $data->get();

            return DesignationResource::collection($items);
        } else {
            $data = $data->paginate($perPage);

            $items = $data->getCollection();

            $items = collect($items)->map(function ($item) {
                return new DesignationResource($item);
            });

            $data = $data->setCollection($items);
        }

        return $data;
    }

    public function findById($id)
    {
        $designation = Designation::findOrFail($id);

        return $designation;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();

        $designation = Designation::create($data);

        return $designation;
    }

    public function update($id, $data)
    {
        $data['updated_by'] = auth()->id();

        $designation = Designation::findOrFail($id);
        return $designation->update($data);
    }

    public function delete($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
    }

}
