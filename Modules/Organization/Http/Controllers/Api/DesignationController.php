<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CRM\App\Exports\DesignationExport;
use Modules\Organization\App\Services\DesignationService;
use Modules\Organization\Entities\Department;
use Modules\Organization\Http\Requests\StoreDesignationRequest;
use Modules\Organization\Http\Requests\UpdateDesignationRequest;
use Modules\Organization\Transformers\DesignationResource;

class DesignationController extends Controller
{
    private $service;

    public function __construct(DesignationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $designations = $this->service->findByParams($request);

        if ($request->export) {
            $format = strtolower($request->format) ?? 'excel';

            switch ($format) {
                case 'excel':
                    return Excel::download(new DesignationExport($designations), 'designations.xlsx');
                    break;
                case 'csv':
                    return Excel::download(new DesignationExport($designations), 'designations.csv');
                    break;
                default:
                    return Excel::download(new DesignationExport($designations), 'designations.xlsx');
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'designations' => $designations,
            ],
            'message' => 'success',
        ], 200);
    }

    public function pageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [

            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        $designation = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'designation' => new DesignationResource($designation),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreDesignationRequest $request)
    {

        $designation = $this->service->store($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'designation' => new DesignationResource($designation),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $user = auth()->user();

        Excel::import(new CompanyImport($user), $request->file('file'));

        return response()->json([
            'status'  => true,
            'message' => "Successfully imported",
        ], 200);
    }

    public function update(UpdateDesignationRequest $request, Department $designation)
    {
        $designation = $this->service->update($designation, $request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'designation' => new DesignationResource($designation),
            ],
            'message' => 'Successfully updated',
        ], 200);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }

    public function downloadSampleExcel()
    {
        $file = public_path('sample_import_data/designations.xlsx');

        return response()->download($file);
    }
}
