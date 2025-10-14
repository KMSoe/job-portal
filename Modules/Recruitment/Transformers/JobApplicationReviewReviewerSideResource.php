<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationReviewReviewerSideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'reviewer_id'          => $this->reviewer_id,
            'jobPosting'           => $this->application?->jobPosting,
            'score'                => $this->score,
            'comment'              => $this->comment,
            'status'               => $this->status,
            'edit_action'          => $this->status == 'done' ? false : true,
            'application'          => $this->application,
            'resume'               => $this->application->resume,
            'extractedData'        => $this->application->extractedData?->extract_data,
            'supportive_documents' => $this->application->supportiveDocuments,
            'applicant'            => new ApplicantResource($this->application?->applicant),
        ];
    }
}
