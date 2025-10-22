<?php
namespace Modules\Recruitment\App\Enums;

enum JobPostingStatusTypes: string {
    case DRAFT     = 'Draft';
    case PUBLISHED = 'Published';
    case CLOSED    = 'Closed';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
