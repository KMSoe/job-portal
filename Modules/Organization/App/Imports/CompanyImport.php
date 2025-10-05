<?php

namespace Modules\Organization\App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Modules\Organization\App\Services\CompanyService;
use Modules\Organization\Http\Requests\StoreCompanyRequest;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;

class CompanyImport implements WithHeadingRow, SkipsEmptyRows, ToCollection, SkipsOnFailure
{
    use SkipsFailures;
    protected $service;

    public function __construct(CompanyService $service) {
        $this->service = $service;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
            $rules = (new StoreCompanyRequest())->rules(); 
            $data = $row->toArray();

            $data['logo'] = $row['logo'] ?? null;
            $data['name'] = $row['name'] ?? null;
            $data['registration_name'] = $row['registration_name'] ?? null;
            $data['registration_no'] = $row['registration_no'] ?? null;
            $data['founded_at'] = $row['founded_at'] ?? null;
            $data['phone_dial_code'] = $row['phone_dial_code'] ?? null;
            $data['phone_no'] = $row['phone_no'] ?? null;
            $data['secondary_phone_dial_code'] = $row['secondary_phone_dial_code'] ?? null;
            $data['secondary_phone_no'] = $row['secondary_phone_no'] ?? null;
            $data['email'] = $row['email'] ?? null;
            $data['secondary_email'] = $row['secondary_email'] ?? null;
            $data['country_id'] = Country::where('name', $row['country'])->first()->id ?? null;
            $data['city_id'] = City::where('name', $row['city'])->first()->id ?? null;
            $data['address'] = $row['address'] ?? null;

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) 
            {
                  foreach ($validator->errors()->messages() as $field => $messages) 
                  {
                    $this->onFailure(new Failure(
                        $index + 2,
                        $field,      
                        $messages,
                        $row->toArray()
                    ));
                  }
                continue;
            }

            $this->service->store($data);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize():int
    {
        return 100;
    }    
}
