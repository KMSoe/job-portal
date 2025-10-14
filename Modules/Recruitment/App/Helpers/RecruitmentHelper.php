<?php
namespace Modules\Recruitment\App\Helpers;

use Modules\Recruitment\App\Enums\RecruitmentStageTypes;

class RecruitmentHelper
{
    public static function getJobApplicationActions($job_application, $current_status)
    {
        $extract_data_action        = false;
        $markAsReceived_action     = false;
        $review_action             = false;
        $assign_review_action      = false;
        $shortlist_action          = false;
        $accessment_testing_action = false;
        $interview_action          = false;
        $create_interview_action   = false;
        $evaluation_action         = false;
        $reference_check_action    = false;
        $update_to_offer_action    = false;
        $create_offer_action       = false;
        $view_offer_action         = false;
        $onboard_check_action      = false;
        $create_employee           = false;

        if ($job_application->extractedData == null) {
            $extract_data_action = true;
        }

        if ($current_status == RecruitmentStageTypes::SUBMITTED->value) {
            $markAsReceived_action = true;
        } else if ($current_status == RecruitmentStageTypes::RECEIVED->value) {
            $review_action = true;
        } else if ($current_status == RecruitmentStageTypes::SCREENING_REVIEW->value) {
            $assign_review_action = true;
            $shortlist_action     = true;
        } else if ($current_status == RecruitmentStageTypes::SHORTLISTING->value) {
            $accessment_testing_action = true;
            $interview_action          = true;
        } else if ($current_status == RecruitmentStageTypes::ASSESSMENT_TESTING->value) {
            $interview_action = true;
        } else if ($current_status == RecruitmentStageTypes::INTERVIEW->value) {
            $create_interview_action = true;
            $evaluation_action       = true;
        } else if ($current_status == RecruitmentStageTypes::EVALUATION_SELECTION->value) {
            $reference_check_action = true;
            $update_to_offer_action = true;
        } else if ($current_status == RecruitmentStageTypes::OFFER->value && $job_application->jobOffer == null) {
            $create_offer_action = true;
        } else if ($current_status == RecruitmentStageTypes::OFFER->value && $job_application->jobOffer != null) {
            $view_offer_action = true;
        } else if ($current_status == RecruitmentStageTypes::OFFER_ACCEPTED->value) {
            $onboard_check_action = true;
        } else if ($current_status == RecruitmentStageTypes::ONBOARDING->value) {
            $create_employee = true;
        }

        return [
            'extract_data_action'        => $extract_data_action,
            'markAsReceived_action'     => $markAsReceived_action,
            'review_action'             => $review_action,
            'assign_review_action'      => $assign_review_action,
            'shortlist_action'          => $shortlist_action,
            'accessment_testing_action' => $accessment_testing_action,
            'interview_action'          => $interview_action,
            'create_interview_action'   => $create_interview_action,
            'evaluation_action'         => $evaluation_action,
            'reference_check_action'    => $reference_check_action,
            'update_to_offer_action'    => $update_to_offer_action,
            'create_offer_action'       => $create_offer_action,
            'view_offer_action'         => $view_offer_action,
            'onboard_check_action'      => $onboard_check_action,
            'create_employee'           => $create_employee,
        ];
    }
}
