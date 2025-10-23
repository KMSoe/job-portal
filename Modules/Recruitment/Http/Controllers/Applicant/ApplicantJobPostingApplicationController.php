<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
use Modules\Recruitment\App\Enums\RecruitmentStageTypes;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Entities\JobOffer;

class ApplicantJobPostingApplicationController extends Controller
{

    public function markAsOfferAccepted(Request $request, $job_offer_id)
    {
        $job_offer = JobOffer::findOrFail($job_offer_id);

        $job_offer->update([
            'status' => JobOfferStatusTypes::OFFER_ACCEPTED->value,
        ]);

        JobApplication::where('id', $job_offer->job_application_id)
            ->update([
                'status' => RecruitmentStageTypes::OFFER_ACCEPTED->value,
            ]);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function markedAsOfferDeclined(Request $request, $job_offer_id)
    {
        $job_offer = JobOffer::findOrFail($job_offer_id);

        $job_offer->update([
            'status' => JobOfferStatusTypes::OFFER_DECLINED->value,
        ]);

        JobApplication::where('id', $job_offer->job_application_id)
            ->update([
                'status' => RecruitmentStageTypes::OFFER_Declined->value,
            ]);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

}
