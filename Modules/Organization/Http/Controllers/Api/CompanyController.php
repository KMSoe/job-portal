<?php
namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\CRM\App\Exports\CompanyExport;
use Modules\CRM\App\Imports\CompanyImport;
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
        if ($request->hasFile('logo')) {
            // Store the file in the 'public' disk under the 'company_logos' folder
            $data['logo'] = $this->storage->store('company_logos', $request->file('logo'));
        } else {
            $data['logo'] = null;
        }

        $company = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'company' => new CompanyResource($company),
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

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        if ($request->hasFile('logo')) {

            if ($company->logo) {
                $this->storage->delete($company->logo);
            }

            $data['logo'] = $this->storage->store('company_logos', $request->file('logo'));

        } else {
            // If no new logo is uploaded, retain the existing logo path
            $data['logo'] = $company->logo;
        }

        $company = $this->service->update($company, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'company' => new CompanyResource($company),
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
        $file = public_path('sample_import_data/companies.xlsx');

        return response()->download($file);
    }
}
