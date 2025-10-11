<?php

namespace Modules\Organization\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Organization\App\Enums\GenderTypes;
use Modules\Organization\App\Enums\MaritalStatuses;
use Modules\Organization\App\Services\EmployeeService;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Modules\Organization\Http\Requests\StoreEmployeeRequest;
use Modules\Organization\Http\Requests\UpdateEmployeeRequest;
use Modules\Organization\Transformers\EmployeeResource;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    private $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        $employees = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'employees' => $employees,
            ],
            'message' => 'success',
        ], 200);
    }

    public function formData()
    {
        return response()->json([
            'status' => true,
            'data'   => [
                'departments'        => Department::select(['id', 'name'])->get(),
                'designations'       => Designation::select(['id', 'name'])->get(),
                'employment_types'   => EmploymentTypes::toArray(),
                'gender'             => GenderTypes::toArray(),
                'marital_statuses'   => MaritalStatuses::toArray(),
            ],
        ], 200);
    }

    public function show($id)
    {
        $employee = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'employee' => new EmployeeResource($employee),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $employee = $this->service->store($validatedData);
            return response()->json([
                'status'  => true,
                'data'    => [
                    'employee' => new EmployeeResource($employee),
                ],
                'message' => 'Successfully saved',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $validatedData = $request->validated();

        try {
            $employee = $this->service->update($id, $validatedData);

            return response()->json([
                'status'  => true,
                'data'    => [
                    'employee' => new EmployeeResource($employee),
                ],
                'message' => 'Successfully updated',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([], 204);
    }

    public function getChecklistItems($employee_id, Request $request)
    {
        $items = $this->service->getChecklistItems($employee_id, $request->all());

        return response()->json([
            'status' => true,
            'data'   => [
                'checklist_items'  => $items,
            ],
        ], 200);
    }

    public function updateChecklistStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:not_started,in_progress,completed',
        ]);

        $this->service->updateChecklistStatus($id, $request->only('status'));

        return response()->json([
            'status'  => true,
            'message' => 'Checklist item status updated successfully',
        ], 200);
    }
}
