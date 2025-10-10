<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Recruitment\App\Services\JobOfferService;
use Modules\Recruitment\Http\Requests\JobOfferFormRequest;

class JobOfferController extends Controller
{
    private $service;

    public function __construct(JobOfferService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

    }

    public function pageData()
    {

    }

    public function show($id)
    {

    }

    public function store(JobOfferFormRequest $request)
    {
        $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }

    public function update(JobOfferFormRequest $request, $job_application_id, $id)
    {
        $this->service->update($id, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }

    public function send(Request $request, $job_offer_id)
    {

        $job_offer = $this->service->findById($job_offer_id);

        Mail::send('recruitment::emails.joboffermail', [

        ], function ($message) use ($job_offer) {
            $message->to($job_offer->candidate->email);
            $message->subject('Email Verification For New User');

            // $message->attach($attachmentPath, [
            //     'as'   => $attachmentName, // Optional: The name the recipient will see
            //     'mime' => $mimeType,       // Optional: Set the correct MIME type
            // ]);
        });

        $job_offer->update([
            'status' => 'sent',
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }

    public function markedAsOfferAccepted(Request $request, $job_offer_id)
    {
        $job_offer = $this->service->findById($job_offer_id);

        $job_offer->update([
            'status' => '',
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
            'status' => '',
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }
}
