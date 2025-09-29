<?php
namespace Modules\Recruitment\Http\Controllers\Api\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Transformers\ApplicantProfileResource;
use Modules\Storage\App\Classes\LocalStorage;

class ApplicantProfileController extends Controller
{
    private $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        $applicant->load([
            'skills',
            'salaryCurrency',
            'experienceLevel',
            'jobFunction',
            'workExperiences' => function ($query) {
                $query->with([
                    'jobFunction',
                    'experienceLevel',
                    'country',
                ])
                    ->orderByDesc('to_date');
            },
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [
                'applicant' => new ApplicantProfileResource($applicant),
            ],
            'message' => '',
        ], 200);
    }

    public function uploadPhoto(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        if ($applicant->photo) {
            $this->storage->delete($applicant->logo);
        }

        $applicant->photo = $this->storage->store('applicant_photos', $request->file('photo'));
        $applicant->save();

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Success',
        ], 200);
    }

}
