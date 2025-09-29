<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostingTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                                                 => 'required|string|max:255|unique:job_posting_templates,name',
            'description'                                          => 'nullable|string',
            'company_id'                                           => 'nullable|exists:companies,id',
            'is_active'                                            => 'sometimes|boolean',

            // --- Template Data Validation (Nested JSON) ---

            // General Job Description
            'template_data.job_title'                              => 'required|string|max:255',
            'template_data.summary'                                => 'nullable|string', // The "An Excellent Opportunity for..." text

            // Open To
            'template_data.open_to'                                => 'nullable|in:Male,Female,Male/Female',

            // Role and Responsibilities
            'template_data.roles_and_responsibilities'             => 'nullable|string|max:500',

            // Job Requirements
            'template_data.job_requirements'                       => 'required|string|max:500',

            // What We Can Offer
            'template_data.what_we_can_offer_include'              => 'required|boolean',

            // Benefits
            'template_data.what_we_can_offer.benefits'             => 'nullable|required_if:template_data.what_we_can_offer_include,true|string',

            // Highlights
            'template_data.what_we_can_offer.highlights'           => 'nullable|required_if:template_data.what_we_can_offer_include,true|string',

            // Career Opportunities
            'template_data.what_we_can_offer.career_opportunities' => 'nullable|required_if:template_data.what_we_can_offer_include,true|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
