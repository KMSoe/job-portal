<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Entities\Applicant;

class ApplicantResumeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'applicant_id'   => 'required|exists:applicants,id',
            'resume_name' => 'required|string|max:255',
            'size' => 'required|numeric',
        ]);

        $applicant = auth()->guard('applicant')->user();
        $applicant = Applicant::find($applicant->id);

        $applicant->resumes()->create([
            'resume_name' => $request->resume_name,
            'size' => $request->size,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Resume uploaded successfully!',
        ], 200);
    }

    public function destroy($id)
    {
        $applicant = auth()->guard('applicant')->user();
        $applicant = Applicant::find($applicant->id);

        $resume = $applicant->resumes()->where('id', $id)->first();

        if (!$resume) {
            return response()->json([
                'status'  => false,
                'message' => 'Resume not found!',
            ], 404);
        }

        $resume->delete();

        return response()->json([], 204);
    }
}
