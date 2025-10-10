<?php

namespace Modules\Recruitment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Employee\App\Http\Resources\ChecklistTemplateItemResource;
use Modules\Recruitment\App\Repositories\ChecklistTemplateRepository;
use Modules\Recruitment\Http\Requests\ChecklistTemplateRequest;
use Modules\Recruitment\Transformers\ChecklistTemplateResource;

class ChecklistTemplateController extends Controller
{
    protected $repo;

    public function __construct(ChecklistTemplateRepository $repo) {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $checklists = $this->repo->paginate($request->all());

        return response()->json([
            'status' => true,
            'data'   => [
                'checklist_templates'      => $checklists,
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChecklistTemplateRequest $request)
    {
        $request->validated();
        try {
            $checklist = $this->repo->create($request->all());

            return response()->json([
                'status' => true,
                'data'   => [
                    'checklist_template' => new ChecklistTemplateResource($checklist),
                ],
                'message' => 'Successfully saved'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $checklist = $this->repo->get($id);

        return response()->json([
            'status' => true,
            'data'   => [
                'checklist_template' => new ChecklistTemplateResource($checklist),
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChecklistTemplateRequest $request, $id)
    {
        $request->validated();
        try {
            $checklist = $this->repo->update($id, $request->all());

            return response()->json([
                'status' => true,
                'data'   => [
                    'checklist_template' => new ChecklistTemplateResource($checklist),
                ],
                'message' => 'Successfully updated'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->repo->delete($id);
            return response()->json([
                'status'  => true,
                'message' => "Successfully deleted",
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function bulkDelete(Request $request) {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:checklist_templates,id'
        ]);
        try {
            $this->repo->bulkDelete($request->ids);
            return response()->json(['success' => true], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json(['success' => false], Response::HTTP_OK);
        }
    }

    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_attachment_required' => 'required|boolean',
        ]);

        try {
            $item = $this->repo->updateItem($id, $request->all());

            return response()->json([
                'status' => true,
                'data'   => [
                    'checklist_template_item' => new ChecklistTemplateItemResource($item),
                ],
                'message' => 'Successfully updated'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyItem(string $id)
    {
        try {
            $this->repo->deleteItem($id);
            return response()->json([
                'status'  => true,
                'message' => "Successfully deleted",
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
