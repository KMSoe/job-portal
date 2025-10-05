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
use Modules\Organization\App\Services\DesignationService;
use Modules\Organization\Http\Requests\StoreDesignationRequest;

class DesignationImport implements WithHeadingRow, SkipsEmptyRows, ToCollection, SkipsOnFailure
{
    use SkipsFailures;
    protected $service;

    public function __construct(DesignationService $service) {
        $this->service = $service;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
            $rules = (new StoreDesignationRequest())->rules(); 
            $data = $row->toArray();

            $data['name'] = $row['name'] ?? null;
            $data['description'] = $row['description'] ?? null;

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
