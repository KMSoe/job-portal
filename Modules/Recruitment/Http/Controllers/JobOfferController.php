<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\App\Services\JobOfferService;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobOffer;
use Modules\Recruitment\Http\Requests\JobOfferFormRequest;
use Modules\Recruitment\Transformers\JobOfferResource;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

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

    public function pageData()
    {

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
        $job_offer = $this->service->findById($job_offer_id);

        // Mail::send('recruitment::emails.joboffermail', [

        // ], function ($message) use ($job_offer) {
        //     $message->to($job_offer->candidate->email);
        //     $message->subject('Email Verification For New User');

        //     // $message->attach($attachmentPath, [
        //     //     'as'   => $attachmentName, // Optional: The name the recipient will see
        //     //     'mime' => $mimeType,       // Optional: Set the correct MIME type
        //     // ]);
        // });

        $job_offer->update([
            'status' => JobOfferStatusTypes::SENT->value,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
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
