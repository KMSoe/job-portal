<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Organization\Entities\Employee;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Entities\JobOfferAttachment;
use Modules\Recruitment\Entities\OfferLetterTemplate;
use Modules\Recruitment\Transformers\OfferLetterTemplateResource;

class JobOfferRepository
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

        $jobOfferData = array_diff_key($data, array_flip([
            'attachments',
            'inform_departments',
            'cc_users',
        ]));

        DB::beginTransaction();
        $jobOffer = JobOffer::create($jobOfferData);

        if (! empty($data['attachments'])) {
            JobOfferAttachment::whereIn('id', $data['attachments'])
                ->update([
                    'job_offer_id' => $jobOffer->id,
                ]);
        }

        if (! empty($data['inform_departments'])) {
            $departmentIds = array_map('intval', $data['inform_departments']);
            $jobOffer->informedDepartments()->attach($departmentIds);
        }

        if (! empty($data['cc_users'])) {
            $ccSyncData = [];
            foreach ($data['cc_users'] as $cc) {
                $ccSyncData[] = [
                    'user_id'        => $cc,
                    'designation_id' => Employee::where('user_id', $cc)->value('designation_id') ?? 0,
                ];
            }
            $jobOffer->ccUsers()->attach($ccSyncData);
        }

        DB::commit();

        return $jobOffer;
    }

}
