<?php
namespace Modules\Recruitment\App\Enums;

enum WorkArrangementTypes: string {
    case REMOTE  = 'Remote';
    case HYBRID  = 'Hybrid';
    case ON_SITE = 'On-Site';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
