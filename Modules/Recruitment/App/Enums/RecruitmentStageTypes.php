<?php
namespace Modules\Recruitment\App\Enums;

enum RecruitmentStageTypes: string {
    case SUBMITTED                   = 'Submitted';
    case RECEIVED                    = 'Received';
    case SCREENING_REVIEW            = 'Screening/Review';
    case SHORTLISTING                = 'Shortlisting';
    case ASSESSMENT_TESTING          = 'Assessment/Testing';
    case INTERVIEW                   = 'Interview(s)';
    case EVALUATION_SELECTION        = 'Evaluation/Selection';
    case REFERENCE_BACKGROUND_CHECKS = 'Reference and Background Checks';
    case OFFER                       = 'Offer';
    case OFFER_ACCEPTED              = 'Offer Accepted';
    case ONBOARDING                  = 'onboarding';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
