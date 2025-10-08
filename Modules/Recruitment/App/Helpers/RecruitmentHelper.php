<?php
namespace Modules\Recruitment\App\Helpers;

use Modules\Recruitment\App\Enums\RecruitmentStageTypes;

class RecruitmentHelper
{
    public static function getJobApplicationActions($current_status)
    {
        $markAsReceived_action     = false;
        $review_action             = false;
        $assign_review_action      = false;
        $shortlist_action          = false;
        $accessment_testing_action = false;
        $interview_action          = false;
        $evaluation_action         = false;
        $reference_check_action    = false;
        $offer_action              = false;
        $onboard_check_action      = false;

        if ($current_status == RecruitmentStageTypes::SUBMITTED->value) {
            $markAsReceived_action = true;
        } else if ($current_status == RecruitmentStageTypes::RECEIVED->value) {
            $review_action = true;
        } else if ($current_status == RecruitmentStageTypes::SCREENING_REVIEW->value) {
            $assign_review_action = true;
        } else if ($current_status == RecruitmentStageTypes::SHORTLISTING->value) {
            $accessment_testing_action = true;
            $interview_action          = true;
        } else if ($current_status == RecruitmentStageTypes::INTERVIEW->value) {
            $evaluation_action = true;
        } else if ($current_status == RecruitmentStageTypes::EVALUATION_SELECTION->value) {
            $reference_check_action = true;
            $offer_action           = true;
        }

        return [
            'markAsReceived_action'     => $markAsReceived_action,
            'review_action'             => $review_action,
            'assign_review_action'      => $assign_review_action,
            'shortlist_action'          => $shortlist_action,
            'accessment_testing_action' => $accessment_testing_action,
            'interview_action'          => $interview_action,
            'evaluation_action'         => $evaluation_action,
            'reference_check_action'    => $reference_check_action,
            'offer_action'              => $offer_action,
            'onboard_check_action'      => $onboard_check_action,
        ];
    }
}
