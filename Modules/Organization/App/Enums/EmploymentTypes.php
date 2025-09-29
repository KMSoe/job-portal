<?php
namespace Modules\Employee\App\Enums;

enum EmploymentTypes: string {
    case PERMANENT  = 'permanent';
    case CONTRACT   = 'contract';
    case INTERNSHIP = 'internship';
    case PROBATION  = 'probation';
    case RESIGNED   = 'resigned';
    case TERMINATED = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::PERMANENT => 'Permanent',
            self::CONTRACT => 'Contract',
            self::INTERNSHIP => 'Internship',
            self::PROBATION => 'Probation',
            self::RESIGNED => 'Resigned',
            self::TERMINATED => 'Terminated',
        };
    }

    public static function toArray(): array
    {
        return array_map(
            fn(self $enum) => [
                'value' => $enum->value,
                'label' => $enum->label(),
            ],
            self::cases()
        );
    }
}
