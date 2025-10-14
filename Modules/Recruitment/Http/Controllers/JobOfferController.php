<?php
namespace Modules\Recruitment\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\App\Mails\JobOfferMail;
use Modules\Recruitment\App\Services\JobOfferService;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Entities\OfferLetterTemplate;
use Modules\Recruitment\Http\Requests\JobOfferFormRequest;
use Modules\Recruitment\Transformers\JobOfferResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;
use Nnjeim\World\Models\Currency;

class JobOfferController extends Controller
{
    private $service;
    private StorageInterface $storage;

    public function __construct(JobOfferService $service, LocalStorage $storage)
    {
        $this->service = $service;
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $job_offers = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_offers' => $job_offers,
            ],
            'message' => 'Success',
        ], 200);
    }

    public function getPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'companies'              => Company::all(),
                'departments'            => Department::all(),
                'designations'           => Designation::all(),
                'offer_letter_templates' => OfferLetterTemplate::all(),
                'currencies'             => Currency::all(),
                'employment_types'       => EmploymentTypes::values(),
                'users'                  => User::all(),
            ],
            'message' => 'Success',
        ], 200);
    }

    public function show($id)
    {
        $job_offer = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_offer' => new JobOfferResource($job_offer),
            ],
            'message' => 'Success',
        ], 200);
    }

    public function store(JobOfferFormRequest $request, $job_application_id)
    {
        $request->merge(input: [
            'job_application_id' => $job_application_id,
        ]);

        $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 201);
    }

    public function update(JobOfferFormRequest $request, $job_application_id, $id)
    {
        $request->merge(input: [
            'job_application_id' => $job_application_id,
        ]);

        $this->service->update($id, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }

    public function uploadSignature($id, Request $request)
    {
        $request->validate([
            'approver_signature' => 'required|file',
        ]);

        $job_offer = JobOffer::findOrFail($id);
        $url       = $this->storage->store($request->path ?? 'approver_signatures', $request->file('approver_signature'));

        $job_offer->update([
            'approver_signature' => $url,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Success',
        ], 200);
    }

    public function send(Request $request, $job_offer_id)
    {
        $job_offer  = $this->service->findById($job_offer_id);
        $logoFile   = $this->storage->getFile($job_offer->company?->logo);
        $mimeType   = $this->storage->getMimeType($job_offer->company?->logo);
        $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($logoFile);

        $mailData = [
            'subject'                => $job_offer->offer_letter_subject,
            'offer_letter_file_path' => $job_offer->offer_letter_file_path,
            'attachments'            => $job_offer->attachments,
            'logo'                   => $logoBase64,
            'logoFile'               => $logoFile, // Pass the raw contents
            'logoMime'               => $mimeType,
            'job_offer'              => $job_offer,
            'candicate_name'         => $job_offer->candidate?->name,
            'candicate_position'     => $job_offer->jobPosting?->title,
        ];

        try {
            Mail::to(["maythuaung415@gmail.com"])
            // Mail::to([$job_offer->candidate?->email])
                ->cc($job_offer->ccUsers->pluck('email')->toArray())
                ->bcc($job_offer->bccUsers->pluck('email')->toArray())
                ->send(new JobOfferMail($mailData));

            $job_offer->update([
                'status' => JobOfferStatusTypes::SENT->value,
            ]);

            return response()->json([
                'status'  => true,
                'data'    => [],
                'message' => 'Success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => false,
                'data'    => [],
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    public function markAsOfferAccepted(Request $request, $job_offer_id)
    {
        $job_offer = $this->service->findById($job_offer_id);

        $job_offer->update([
            'status' => JobOfferStatusTypes::OFFER_ACCEPTED->value,
        ]);

        JobApplication::where('id', $job_offer->job_application_id)
            ->update([
                'status' => RecruitmentStageTypes::OFFER_ACCEPTED->value,
            ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }

    public function markedAsOfferDeclined(Request $request, $job_offer_id)
    {
        $job_offer = $this->service->findById($job_offer_id);

        $job_offer->update([
            'status' => JobOfferStatusTypes::OFFER_DECLINED->value,
        ]);

        JobApplication::where('id', $job_offer->job_application_id)
            ->update([
                'status' => RecruitmentStageTypes::OFFER_Declined->value,
            ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }
}
