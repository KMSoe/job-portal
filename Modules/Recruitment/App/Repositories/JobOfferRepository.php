<?php
namespace Modules\Recruitment\App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Organization\Entities\Employee;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Entities\JobOfferAttachment;
use Modules\Recruitment\Transformers\JobOfferResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;
use TCPDF;
use TCPDF_FONTS;

class JobOfferRepository
{
    private StorageInterface $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

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
            'department',
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
            'department',
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

        $job_application = JobApplication::with(['applicant', 'jobPosting'])->findOrFail($data['job_application_id']);

        $jobOfferData['job_posting_id']     = $job_application->job_posting_id;
        $jobOfferData['job_application_id'] = $job_application->id;
        $jobOfferData['candicate_id']       = $job_application->applicant_id;

        DB::beginTransaction();

        $jobOffer = JobOffer::create($jobOfferData);

        $offer_letter_file_path = $this->storeOfferLetter($jobOffer, $job_application, $job_application->applicant->name . "-" . time());

        $jobOffer->update([
            'offer_letter_file_path' => $offer_letter_file_path,
        ]);

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

        $job_application = JobApplication::with(['applicant', 'jobPosting'])->findOrFail($data['job_application_id']);

        $jobOfferData['job_posting_id']     = $job_application->job_posting_id;
        $jobOfferData['job_application_id'] = $job_application->id;
        $jobOfferData['candicate_id']       = $job_application->applicant_id;

        DB::beginTransaction();

        $jobOffer->update($jobOfferData);

        $jobOffer = JobOffer::findOrFail($id);

        if ($jobOffer->offer_letter_file_path) {
            $this->storage->delete($jobOffer->offer_letter_file_path);
        }

        $offer_letter_file_path = $this->storeOfferLetter($jobOffer, $job_application, $job_application->applicant->name . "-" . time());

        $jobOffer->update([
            'offer_letter_file_path' => $offer_letter_file_path,
        ]);

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

    public function storeOfferLetter($jobOffer, $job_application, $file_name)
    {
        $html = view('recruitment::offer_letter', [
            'job_offer'          => $jobOffer,
            'candicate_name'     => $job_application->applicant?->name,
            'candicate_position' => $job_application->jobPosting?->title,
        ])->render();

        $fontFileBold = public_path('font/OpenSans-Bold.ttf');
        $fontFile     = public_path('font/OpenSans-Regular.ttf');

        $opensan_bold    = TCPDF_FONTS::addTTFfont($fontFileBold, 'TrueTypeUnicode', '', 12);
        $opensan_regular = TCPDF_FONTS::addTTFfont($fontFile, 'TrueTypeUnicode', '', 12);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle("Test");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont($opensan_bold, '', 12);
        $pdf->SetFont($opensan_regular, '', 12);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        // $pdf->SetY(-20);
        // $pdf->SetLineStyle([
        //     'width' => 0.5,
        //     'dash'  => '3,2',           // 3mm dash, 2mm gap
        //     'color' => [128, 128, 128], // Gray color
        // ]);
        // $pdf->Line(15, $pdf->GetY(), $pdf->getPageWidth() - 15, $pdf->GetY());
        $filePath = storage_path('app/offer_letters/' . $file_name . ".pdf");
        $pdf->Output($filePath, 'F');

        return "offer_letters/$file_name.pdf";
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
