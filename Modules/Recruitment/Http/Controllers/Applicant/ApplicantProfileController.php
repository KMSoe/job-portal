<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Enums\JobPostingSalaryTypes;
use Modules\Recruitment\App\Enums\JobTypes;
use Modules\Recruitment\App\Enums\WorkArrangementTypes;
use Modules\Recruitment\Entities\EducationLevel;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Transformers\ApplicantProfileResource;
use Modules\Storage\App\Classes\LocalStorage;

class ApplicantProfileController extends Controller
{
    private $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

     public function getPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'experience_levels' => ExperienceLevel::all(),
                'job_functions'     => JobFunction::all(),
                'education_levels'   => EducationLevel::all(),
                'job_types'         => JobTypes::values(),
                'work_arrangements' => WorkArrangementTypes::values(),
                'salary_types'      => JobPostingSalaryTypes::values(),

            ],
            'message' => 'success',
        ], 200);
    }

    public function index(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        $applicant->load([
            'defaultResume',
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

    public function update(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        $validatedData = $request->validate([
            'name'                => 'sometimes|string|max:255',
            'job_title'           => 'sometimes|nullable|string|max:255',
            'phone_dial_code'     => 'sometimes|nullable|string|max:10',
            'phone_no'            => 'sometimes|nullable|string|max:20',
            'open_to_work'        => 'sometimes|boolean',
            'experience_level_id' => 'sometimes|nullable|exists:experience_levels,id',
            'job_function_id'     => 'sometimes|nullable|exists:job_functions,id',
            'salary_currency_id'  => 'sometimes|nullable|exists:currencies,id',
            'expected_salary'     => 'sometimes|nullable|numeric',
        ]);

        $applicant->update($validatedData);

        return response()->json([
            'status'  => true,
            'data'    => [
                // 'applicant' => new ApplicantProfileResource($applicant),
            ],
            'message' => 'Updated successfully',
        ], 200);
    }

    public function uploadPhoto(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        if ($applicant->photo != null) {
            $this->storage->delete($applicant->photo);
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
