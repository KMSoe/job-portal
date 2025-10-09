<?php
namespace Modules\Recruitment\App\Services;

use Modules\Recruitment\App\Repositories\OfferLetterTemplateRepository;

class OfferLetterTemplateService
{

    private OfferLetterTemplateRepository $offerLetterTemplateRepostitory;

    public function __construct(OfferLetterTemplateRepository $offerLetterTemplateRepostitory)
    {
        $this->offerLetterTemplateRepostitory = $offerLetterTemplateRepostitory;
    }

    public function findByParams($request)
    {
        return $this->offerLetterTemplateRepostitory->findByParams($request);
    }

    public function findById($id)
    {
        return $this->offerLetterTemplateRepostitory->findById($id);
    }

    public function store($data)
    {
        return $this->offerLetterTemplateRepostitory->store($data);
    }

    public function update($jobPostingTemplate, $data)
    {
        return $this->offerLetterTemplateRepostitory->update($jobPostingTemplate, $data);
    }

    public function delete($id)
    {
        return $this->offerLetterTemplateRepostitory->delete($id);
    }
}
