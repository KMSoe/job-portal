<?php
namespace Modules\Recruitment\App\Enums;

enum JobApplicationStatusTypes: string {
    case MONTHLY = 'Monthly';
    case WEEKLY  = 'Weekly';
    case DAILY   = 'Daily';
    case HOURLY  = 'Hourly';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

// 'received',
// 'under_review',
// 'interview',
// 'technical_test',
// 'offer_extended',
// 'hired',
// 'rejected',
// 'withdrawn',
