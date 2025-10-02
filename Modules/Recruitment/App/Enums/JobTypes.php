<?php
namespace Modules\Recruitment\App\Enums;

enum JobTypes: string {
    case FULL_TIME   = 'Full-Time';
    case PART_TIME   = 'Part-Time';
    case CONTRACT    = 'Contract';
    case INTERNSHIP  = 'Internship';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
