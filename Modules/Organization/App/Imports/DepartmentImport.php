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
use Modules\Organization\App\Services\DepartmentService;
use Modules\Organization\Entities\Company;
use Modules\Organization\Http\Requests\StoreDepartmentRequest;

class DepartmentImport implements WithHeadingRow, SkipsEmptyRows, ToCollection, SkipsOnFailure
{
    use SkipsFailures;
    protected $service;

    public function __construct(DepartmentService $service) {
        $this->service = $service;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
            $rules = (new StoreDepartmentRequest())->rules(); 
            $data = $row->toArray();

            $data['company_id'] = Company::where('name', $row['company'])->first()->id ?? null;
            $data['name'] = $row['name'] ?? null;
            $data['description'] = $row['description'] ?? null;
            $data['is_active'] = $row['is_active'] == 'yes' ? 1 : 0;

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
