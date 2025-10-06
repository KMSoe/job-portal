<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Organization\App\Exports\DesignationExport;
use Modules\Organization\App\Imports\DesignationImport;
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

        $designation = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'designation' => new DesignationResource($designation),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(UpdateDesignationRequest $request, $id)
    {
        $designation = $this->service->update($id, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                // 'designation' => new DesignationResource($designation),
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
        $file = public_path('sample_import_data/designations.xlsx');

        return response()->download($file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $import = new DesignationImport($this->service);
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
