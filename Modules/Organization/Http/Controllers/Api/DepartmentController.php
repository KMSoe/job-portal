<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Organization\App\Exports\DepartmentExport;
use Modules\Organization\App\Imports\DepartmentImport;
use Modules\Organization\App\Services\DepartmentService;
use Modules\Organization\Entities\Company;
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

    public function getPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'companies' => Company::all(),
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

    public function update(UpdateDepartmentRequest $request, $id)
    {
        $department = $this->service->update($id, $request->toArray());

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

    public function downloadSampleExcelFile()
    {
        $file = public_path('sample_import_data/departments.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $import = new DepartmentImport($this->service);
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->isNotEmpty()) {
            $field_messages = [];

            foreach ($failures as $failure) {
                $row       = $failure->row();
                $attribute = $failure->attribute();
                $messages  = $failure->errors();
                $value     = $failure->values()[$attribute] ?? '[unknown]';

                foreach ($messages as $msg) {
                    $key = $msg;
                    if (! isset($field_messages[$attribute][$key])) {
                        $field_messages[$attribute][$key] = [];
                    }
                    $field_messages[$attribute][$key][] = "$value of row $row";
                }
            }

            $error_messages = [];

            foreach ($field_messages as $attribute => $message_group) {
                foreach ($message_group as $base_message => $entries) {
                    $entries = array_unique($entries);

                    if (count($entries) > 1) {
                        $last   = array_pop($entries);
                        $joined = implode(', ', $entries) . ' and ' . $last;
                    } else {
                        $joined = $entries[0];
                    }

                    $error_messages[$attribute][] = "[$joined] — $base_message";
                }
            }

            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $error_messages,
            ], 422);
        }

        return response()->json([
            'status'  => true,
            'message' => "Successfully imported",
        ], 200);
    }
}
