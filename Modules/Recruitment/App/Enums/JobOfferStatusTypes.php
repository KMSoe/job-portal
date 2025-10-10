<?php
namespace Modules\Recruitment\App\Enums;

enum JobOfferStatusTypes: string {
    case DRAFT          = 'draft';
    case DONE           = 'done';
    case SENT           = 'sent';
    case OFFER_ACCEPTED = 'Offer Accepted';
    case OFFER_DECLINED = 'Offer Declined';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
