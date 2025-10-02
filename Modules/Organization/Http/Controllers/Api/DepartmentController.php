<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CRM\App\Exports\DepartmentExport;
use Modules\Organization\App\Services\DepartmentService;
use Modules\Organization\Entities\Department;
use Modules\Organization\Http\Requests\StoreDepartmentRequest;
use Modules\Organization\Http\Requests\UpdateDepartmentRequest;
use Modules\Organization\Transformers\DepartmentResource;

class DepartmentController extends Controller
{
    private $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $departments = $this->service->findByParams($request);

        if ($request->export) {
            $format = strtolower($request->format) ?? 'excel';

            switch ($format) {
                case 'excel':
                    return Excel::download(new DepartmentExport($departments), 'departments.xlsx');
                    break;
                case 'csv':
                    return Excel::download(new DepartmentExport($departments), 'departments.csv');
                    break;
                default:
                    return Excel::download(new DepartmentExport($departments), 'departments.xlsx');
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'departments' => $departments,
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
        $department = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'department' => new DepartmentResource($department),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreDepartmentRequest $request)
    {

        $department = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'department' => new DepartmentResource($department),
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

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department = $this->service->update($department, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'department' => new DepartmentResource($department),
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
        $file = public_path('sample_import_data/departments.xlsx');

        return response()->download($file);
    }
}
