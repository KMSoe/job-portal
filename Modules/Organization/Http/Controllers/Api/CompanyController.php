<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Organization\App\Exports\CompanyExport;
use Modules\Organization\App\Imports\CompanyImport;
use Modules\Organization\App\Services\CompanyService;
use Modules\Organization\Entities\Company;
use Modules\Organization\Http\Requests\StoreCompanyRequest;
use Modules\Organization\Http\Requests\UpdateCompanyRequest;
use Modules\Organization\Transformers\CompanyResource;
use Modules\Storage\App\Classes\LocalStorage;

class CompanyController extends Controller
{
    private $service;
    private $storage;

    public function __construct(CompanyService $service, LocalStorage $storage)
    {
        $this->service = $service;
        $this->storage = $storage;
    }

    public function index(Request $request)
    {
        $companies = $this->service->findByParams($request);

        if ($request->export) {
            $format = strtolower($request->format) ?? 'excel';

            switch ($format) {
                case 'excel':
                    return Excel::download(new CompanyExport($companies), 'companies.xlsx');
                    break;
                case 'csv':
                    return Excel::download(new CompanyExport($companies), 'companies.csv');
                    break;
                default:
                    return Excel::download(new CompanyExport($companies), 'companies.xlsx');
                    break;
            }
        }

        return response()->json([
            'status'  => true,
            'data'    => [
                'companies' => $companies,
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
        $company = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'company' => new CompanyResource($company),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            // Store the file in the 'public' disk under the 'company_logos' folder
            $data['logo'] = $this->storage->store('company_logos', $request->file('logo'));
        } else {
            $data['logo'] = null;
        }

        $company = $this->service->store($data);

        return response()->json([
            'status'  => true,
            'data'    => [
                'company' => new CompanyResource($company),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {

            if ($company->logo) {
                $this->storage->delete($company->logo);
            }

            $data['logo'] = $this->storage->store('company_logos', $request->file('logo'));

        } else {
            // If no new logo is uploaded, retain the existing logo path
            $data['logo'] = $company->logo;
        }

        $company = $this->service->update($company, $data);

        return response()->json([
            'status'  => true,
            'data'    => [
                // 'company' => new CompanyResource($company),
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
        $file = public_path('sample_import_data/companies.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ], [
            "file" => "The file is required with excel(xlsx) or csv format",
        ]);

        $import = new CompanyImport($this->service);
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
