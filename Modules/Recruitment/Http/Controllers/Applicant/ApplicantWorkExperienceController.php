<?php
namespace Modules\Recruitment\Http\Controllers\Api\Applicant;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\Applicant\ApplicantWorkExperienceService;
use Modules\Recruitment\Http\Requests\ApplicantWorkExperienceRequest;

class ApplicantWorkExperienceController extends Controller
{
    private ApplicantWorkExperienceService $applicantWorkExperienceService;

    public function __construct(ApplicantWorkExperienceService $applicantWorkExperienceService)
    {
        $this->applicantWorkExperienceService = $applicantWorkExperienceService;
    }

    public function store(ApplicantWorkExperienceRequest $request)
    {

        $applicant = auth()->guard('applicant')->user();

        $request->merge([
            'applicant_id' => $applicant->id,
        ]);

        $this->applicantWorkExperienceService->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Success',
        ], 200);
    }

    public function update(ApplicantWorkExperienceRequest $request, $id)
    {

        $applicant = auth()->guard('applicant')->user();

        $request->merge([
            'applicant_id' => $applicant->id,
        ]);

        $this->applicantWorkExperienceService->update($id, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Success',
        ], 200);
    }

    public function destroy($id)
    {
        $this->applicantWorkExperienceService->delete($id);

        return response()->json([], 204);
    }

}
