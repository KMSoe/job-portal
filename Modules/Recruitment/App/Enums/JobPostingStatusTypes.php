<?php
namespace Modules\Recruitment\App\Enums;

enum JobPostingStatusTypes: string {
    case DRAFT            = 'Draft';
    case PENDING_APPROVAL = 'Pending_Approval';
    case PUBLISHED        = 'Published';
    case ARCHIVED         = 'Archived';
    case CLOSED           = 'Closed';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
