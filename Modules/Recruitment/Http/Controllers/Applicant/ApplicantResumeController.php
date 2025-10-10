<?php
namespace Modules\Recruitment\Http\Controllers\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\Resume;
use Modules\Storage\App\Classes\LocalStorage;
use Modules\Storage\App\Interfaces\StorageInterface;

class ApplicantResumeController extends Controller
{
    private StorageInterface $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $applicant = auth()->guard('applicant')->user();

        $resumes = Resume::where('applicant_id', $applicant->id)
            ->orderByDesc('is_default')
            ->orderByDesc('uploaded_at')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'status'  => true,
            'data'    => [
                'resumes' => $resumes,
            ],
            'message' => '',
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'resume_name' => 'required|string|max:255',
            'file'        => [
                'required',
                'file',
                'mimes:pdf',
            ],
            'is_default'  => 'required|boolean',
        ]);

        $applicant = auth()->guard('applicant')->user();

        $uploadedFile = $request->file('file');

        $filePath = $this->storage->store('resumes', $uploadedFile);

        $resume = Resume::create([
            'applicant_id' => $applicant->id,
            'resume_name'  => $request->resume_name,
            'file_path'    => $filePath,
            'size'         => $uploadedFile->getSize(),
            'uploaded_at'  => now(),
            'is_default'   => $request->is_default,
        ]);

        if ($request->is_default == true) {
            Resume::where('applicant_id', $applicant->id)->whereNot('id', $resume->id)->update([
                'is_default' => false,
            ]);
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'resume' => $resume,
            ],
            'message' => 'Resume uploaded successfully!',
        ], 201);
    }

    public function setDefault($id)
    {
        $applicant = auth()->guard('applicant')->user();

        Resume::where('applicant_id', $applicant->id)->where('id', $id)->update([
            'is_default' => true,
        ]);

        Resume::where('applicant_id', $applicant->id)->whereNot('id', $id)->update([
            'is_default' => false,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success!',
        ], 201);
    }

    public function destroy($id)
    {
        $applicant = auth()->guard('applicant')->user();
        $applicant = Applicant::find($applicant->id);

        $resume = $applicant->resumes()->where('id', $id)->first();

        if (! $resume) {
            return response()->json([
                'status'  => false,
                'message' => 'Resume not found!',
            ], 404);
        }

        $resume->delete();

        return response()->json([], 204);
    }
}
