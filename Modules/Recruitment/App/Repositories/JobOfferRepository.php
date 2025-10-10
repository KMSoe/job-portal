<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Organization\Entities\Employee;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Entities\JobOfferAttachment;
use Modules\Recruitment\Transformers\JobOfferResource;

class JobOfferRepository
{
    public function findByParams($request)
    {
        $keyword = $request->search ? $request->search : '';
        $perPage = $request->perPage ? $request->perPage : 20;

        $data = JobOffer::with([
            // Core Belongs To Relationships
            'jobPosting',
            'application',
            'candidate',
            'company',
            'designation',
            'template',
            'salaryCurrency',
            'approver',

            // Has Many / Belongs To Many Relationships
            'attachments',
            'informedDepartments',
            'ccUsers',
            'bccUsers',

            // Note: If 'ccUsers' and 'bccUsers' pivot data is needed,
            // you can access it via $jobOffer->ccUsers[0]->pivot->designation_id
        ]);

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
            return new JobOfferResource($item);
        });

        $data = $data->setCollection($items);

        return $data;
    }

    public function findById($id)
    {
        $jobOffer = JobOffer::with([
            // Core Belongs To Relationships
            'jobPosting',
            'application',
            'candidate',
            'company',
            'designation',
            'template',
            'salaryCurrency',
            'approver',

            // Has Many / Belongs To Many Relationships
            'attachments',
            'informedDepartments',
            'ccUsers',
            'bccUsers',

            // Note: If 'ccUsers' and 'bccUsers' pivot data is needed,
            // you can access it via $jobOffer->ccUsers[0]->pivot->designation_id
        ])
            ->findOrFail($id);

        return $jobOffer;
    }

    public function store($data)
    {
        $data['created_by'] = auth()->id();
        $data['status']     = $data['status'] ?? JobOfferStatusTypes::DRAFT->value;

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

        if (! empty($data['bcc_users'])) {
            $bccSyncData = [];
            foreach ($data['cc_users'] as $cc) {
                $bccSyncData[] = [
                    'user_id'        => $cc,
                    'designation_id' => Employee::where('user_id', $cc)->value('designation_id') ?? 0,
                ];
            }
            $jobOffer->bccUsers()->attach($bccSyncData);
        }

        DB::commit();

        return $jobOffer;
    }

    public function update($id, $data)
    {
        $jobOffer = JobOffer::findOrFail($id);

        $jobOfferData = array_diff_key($data, array_flip([
            'attachments',
            'inform_departments',
            'cc_users',
        ]));

        DB::beginTransaction();
        $jobOffer->update($jobOfferData);

        if (! empty($data['attachments'])) {
            JobOfferAttachment::whereIn('id', $data['attachments'])
                ->update([
                    'job_offer_id' => $jobOffer->id,
                ]);
        }

        if (! empty($data['inform_departments'])) {
            $departmentIds = array_map('intval', $data['inform_departments']);
            $jobOffer->informedDepartments()->sync($departmentIds);
        }

        if (! empty($data['cc_users'])) {
            $ccSyncData = [];
            foreach ($data['cc_users'] as $cc) {
                $ccSyncData[] = [
                    'user_id'        => $cc,
                    'designation_id' => Employee::where('user_id', $cc)->value('designation_id') ?? 0,
                ];
            }
            $jobOffer->ccUsers()->sync($ccSyncData);
        }

        if (! empty($data['bcc_users'])) {
            $bccSyncData = [];
            foreach ($data['cc_users'] as $cc) {
                $bccSyncData[] = [
                    'user_id'        => $cc,
                    'designation_id' => Employee::where('user_id', $cc)->value('designation_id') ?? 0,
                ];
            }
            $jobOffer->bccUsers()->sync($bccSyncData);
        }

        DB::commit();
    }

    public function send($job_offer_id)
    {
        $jobOffer = JobOffer::with(['attachments'])->findOrFail($job_offer_id);
        DB::beginTransaction();

        $jobOffer->update([
            'status' => 'sent',
        ]);

        DB::commit();
    }

}
