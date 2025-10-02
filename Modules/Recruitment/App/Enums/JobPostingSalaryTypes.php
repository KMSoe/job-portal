<?php
namespace Modules\Recruitment\App\Enums;

enum JobPostingSalaryTypes: string {
    case RANGE      = 'Range';
    case UP_TO      = 'Up_To';
    case AROUND     = 'Around';
    case FIXED      = 'Fixed';
    case NEGOTIABLE = 'Negotiable';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
