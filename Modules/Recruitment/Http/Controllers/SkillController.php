<?php

namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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
        $skill = $this->service->store($request);

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
        $skill = $this->service->update($id, $request);

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
}
