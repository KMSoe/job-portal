<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Recruitment\App\Exports\JobFunctionExport;
use Modules\Recruitment\App\Imports\JobFunctionImport;
use Modules\Recruitment\App\Services\JobFunctionService;
use Modules\Recruitment\Http\Requests\JobFunctionRequest;

class JobFunctionController extends Controller
{
    private $service;

    public function __construct(JobFunctionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $job_functions = $this->service->findByParams($request);

        if ($request->export) {
            $format = strtolower($request->format) ?? 'excel';

            switch ($format) {
                case 'excel':
                    return Excel::download(new JobFunctionExport($job_functions), 'job_functions.xlsx');
                    break;
                case 'csv':
                    return Excel::download(new JobFunctionExport($job_functions), 'job_functions.csv');
                    break;
                default:
                    return Excel::download(new JobFunctionExport($job_functions), 'job_functions.xlsx');
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_functions' => $job_functions,
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
        $job_function = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_function' => $job_function,
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(JobFunctionRequest $request)
    {
        $job_function = $this->service->store($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_function' => $job_function,
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(JobFunctionRequest $request, $id)
    {
        $job_function = $this->service->update($id, $request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'job_function' => $job_function,
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
        $file = public_path('sample_import_data/job_functions.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $import = new JobFunctionImport($this->service);
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
