<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Recruitment\App\Services\JobOfferService;
use Modules\Recruitment\Entities\JobApplicationReviewer;
use Modules\Recruitment\Http\Requests\JobOfferFormRequest;
use Modules\Recruitment\Transformers\JobApplicationReviewReviewerSideResource;

class JobOfferController extends Controller
{
    private $service;

    public function __construct(JobOfferService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        
    }

    public function pageData()
    {
        
    }

    public function show($id)
    {
       
    }

    public function store(JobOfferFormRequest $request)
    {
        $this->service->store($request->toArray());
        
        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Success',
        ], 200);
    }
}
