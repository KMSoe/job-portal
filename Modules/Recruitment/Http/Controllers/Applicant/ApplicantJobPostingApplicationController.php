<?php
namespace Modules\Recruitment\Http\Controllers\Api\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ApplicantJobPostingApplicationController extends Controller
{
    private $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $departments = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'departments' => $departments,
            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        // $departments = $this->service->findByParams($request);

        // return response()->json([
        //     'status'  => true,
        //     'data'    => [
        //         'departments' => $departments,
        //     ],
        //     'message' => 'success',
        // ], 200);
    }

   

}
