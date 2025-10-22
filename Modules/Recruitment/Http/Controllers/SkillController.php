<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Recruitment\App\Exports\SkillExport;
use Modules\Recruitment\App\Imports\SkillImport;
use Modules\Recruitment\App\Services\SkillService;
use Modules\Recruitment\Http\Requests\StoreSkillRequest;
use Modules\Recruitment\Http\Requests\UpdateSkillRequest;

class SkillController extends Controller
{
    private $service;

    public function __construct(SkillService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $skills = $this->service->findByParams($request);

        if ($request->export) {
            $format = strtolower($request->format) ?? 'excel';

            switch ($format) {
                case 'excel':
                    return Excel::download(new SkillExport($skills), 'skills.xlsx');
                    break;
                case 'csv':
                    return Excel::download(new SkillExport($skills), 'skills.csv');
                    break;
                default:
                    return Excel::download(new SkillExport($skills), 'skills.xlsx');
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'skills' => $skills,
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
        $skill = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'skill' => $skill,
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreSkillRequest $request)
    {
        $skill = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'skill' => $skill,
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(UpdateSkillRequest $request, $id)
    {
        $skill = $this->service->update($id, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'skill' => $skill,
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
        $file = public_path('sample_import_data/skills.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $import = new SkillImport($this->service);
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
