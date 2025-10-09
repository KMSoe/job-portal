<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Str;
use Modules\Recruitment\Entities\OfferLetterTemplate;
use Modules\Recruitment\Transformers\offerLetterTemplateResource;

class OfferLetterTemplateRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = OfferLetterTemplate::with([
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
            return new OfferLetterTemplateResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
        $offerLetterTemplate = OfferLetterTemplate::with([
            'company',
            'createdBy',
        ])->findOrFail($id);

        return $offerLetterTemplate;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $offerLetterTemplate            = OfferLetterTemplate::create($data);

        return $offerLetterTemplate;
    }

    public function update($offerLetterTemplate, $data)
    {
        $data['updated_by'] = auth()->id();

        return $offerLetterTemplate->update($data);
    }

    public function delete($id)
    {
        $offerLetterTemplate = OfferLetterTemplate::findOrFail($id);
        $offerLetterTemplate->delete();
    }

}
