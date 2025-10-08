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
            'jobPosting'           => $this->application?->jobPosting,
            'score'                => $this->score,
            'comment'              => $this->comment,
            'application'          => $this->application,
            'resume'               => $this->application->resume,
            'supportive_documents' => $this->application->supportiveDocuments,
            'applicant'            => new ApplicantResource($this->application?->applicant),
        ];
    }
}
