<?php

namespace Modules\CRM\App\Imports;

use App\Modules\Countries\Country;
use Modules\Organization\App\Repositories\CompanyRepository;
use Modules\Organization\App\Services\CompanyService;
use Modules\Project\App\Models\Currency;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\CRM\App\Models\Industry;
use Illuminate\Support\Str;

class DesignationImport implements WithStartRow, ToCollection, WithHeadingRow, WithChunkReading, WithValidation
{
    private $user;
    private $service;

    public function __construct($user)
    {
        $this->user = $user;
        $this->service = new CompanyService(new CompanyRepository());
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|unique:companies,name',
            // 'industry'              => 'required|exists:industries,name',
            'industry'              => 'required|string',
            'brand_value'           => 'required|numeric|min:0',
            'number_of_employees'   => 'required|numeric|min:0',
            // 'country'               => 'required|exists:countries,name',
            'country'               => 'required|string',
            'city'                  => 'required|string',
            'address'               => 'nullable',
            'email'                 => 'required|email',
            'hotline_prefix'        => 'required',
            'hotline_number'        => 'required',
            'account_revenue'       => 'nullable|numeric',
            'annual_revenue'        => 'nullable|numeric',
            'first_project_created' => 'nullable',
            'domain_name'           => 'nullable',
            'description'           => 'nullable',
        ];

    }

    // public function customValidationMessages()
    // {
    //     return [

    //     ];
    // }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $industry                 = Industry::where('name', trim($row["industry"] ?? ''))->select('id', 'name')->first();
            $country                  = Country::where('name', trim($row["country"] ?? ''))->select('id', 'name')->first();
            $account_revenue_currency = Currency::where('currency_code', $row['account_revenue_currency'] ?? '')->first();
            $annual_revenue_currency  = Currency::where('currency_code', $row['annual_revenue_currency'] ?? '')->first();

            if(!$industry && (trim($row["industry"] ?? '')) != '') {
                $industry = Industry::create([
                    'name'        => $row["industry"],
                    'slug'        => Str::slug($row["industry"]),
                    'created_by'  => $this->user->id,
                ]);
            }

            if(!$country && ($row["country"] ?? '') != '') {
                $country = Country::create([
                    'name'        => $row["country"]
                ]);
            }

            $data = [
                "name"                        => $row["name"],
                "industry_id"                 => $industry->id ?? 0,
                "brand_value"                 => $row["brand_value"],
                "number_of_employees"         => $row["number_of_employees"],
                "country_id"                  => $country->id ?? 0,
                "city"                        => $row["city"],
                "address"                     => $row["address"],
                "email"                       => $row["email"],
                "hotline_prefix"              => $row["hotline_prefix"],
                "hotline_number"              => $row["hotline_number"],
                "account_revenue_currency_id" => $account_revenue_currency->id ?? 0,
                "account_revenue"             => $row["account_revenue"],
                "annual_revenue_currency_id"  => $annual_revenue_currency->id ?? 0,
                "annual_revenue"              => $row["annual_revenue"],
                "first_project_created"       => $row["first_project_created"],
                "domain_name"                 => $row["domain_name"],
                "description"                 => $row["description"],
                "profile_photo"               => null,
            ];

           $this->service->store($data);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
