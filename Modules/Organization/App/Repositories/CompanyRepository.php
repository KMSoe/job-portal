<?php
namespace Modules\Organization\App\Repositories;

use Illuminate\Support\Str;
use Modules\Organization\Entities\Company;
use Modules\Organization\Transformers\CompanyResource;

class CompanyRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->per_page ? $request->per_page : 20;

        $data = Company::with([
            'country',
            'city',
            'createdBy',
        ])
            ->where(function ($query) use ($keyword) {
                if ($keyword != '') {
                    $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('registration_name', 'LIKE', "%$keyword%");
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

            return CompanyResource::collection($items);
        } else {
            $data = $data->paginate($perPage);

            $items = $data->getCollection();

            $items = collect($items)->map(function ($item) {
                return new CompanyResource($item);
            });

            $data = $data->setCollection($items);
        }

        return $data;
    }

    public function findById($id)
    {
        $company = Company::with([
            'country',
            'city',
            'createdBy',
        ])->findOrFail($id);

        return $company;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $company            = Company::create($data);

        return $company;
    }

    public function update($company, $data)
    {
        $data['updated_by'] = auth()->id();

        return $company->update($data);
    }

    public function delete($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
    }
}
