<?php
namespace Modules\Recruitment\Http\Controllers\Api\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ApplicantSkillController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'skill_ids'   => 'required|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $applicant = auth()->guard('applicant')->user();

        $skillIds = $request->skill_ids;

        $applicant->skills()->sync($skillIds);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'Skills updated successfully!',
        ], 200);
    }

}
