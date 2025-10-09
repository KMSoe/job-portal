<?php
namespace Modules\Recruitment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Organization\Entities\Company;
use Modules\Recruitment\App\Services\OfferLetterTemplateService;
use Modules\Recruitment\Entities\OfferLetterTemplate;
use Modules\Recruitment\Http\Requests\OfferLetterTemplateRequest;
use Modules\Recruitment\Transformers\offerLetterTemplateResource;

class OfferLetterTemplateController extends Controller
{
    private $service;

    public function __construct(OfferLetterTemplateService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $offer_letter_templates = $this->service->findByParams($request);

        return response()->json([
            'status'  => true,
            'data'    => [
                'offer_letter_templates' => $offer_letter_templates,
            ],
            'message' => 'success',
        ], 200);
    }

    public function getPageData()
    {
        return response()->json([
            'status'  => true,
            'data'    => [
                'companies' => Company::all(),
                'codes'     => [
                    '{{job_title}}',
                    '{{applicant_name}}',
                    '{{basic_salary}}',
                    '{{company_name}}',
                    '{{department}}',
                    '{{designation}}',
                ],
            ],
            'message' => 'success',
        ], 200);
    }

    public function show($id)
    {
        $offer_letter_template = $this->service->findById($id);

        return response()->json([
            'status'  => true,
            'data'    => [
                'offer_letter_template' => new OfferLetterTemplateResource($offer_letter_template),
            ],
            'message' => 'success',
        ], 200);
    }

    public function store(OfferLetterTemplateRequest $request)
    {
        $offer_letter_template = $this->service->store($request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [
                'offer_letter_template' => new OfferLetterTemplateResource($offer_letter_template),
            ],
            'message' => 'Successfully saved',
        ], 201);
    }

    public function update(OfferLetterTemplateRequest $request, $id)
    {
        $offerLetterTemplate = OfferLetterTemplate::findOrFail($id);

        $this->service->update($offerLetterTemplate, $request->toArray());

        return response()->json([
            'status'  => true,
            'data'    => [

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
