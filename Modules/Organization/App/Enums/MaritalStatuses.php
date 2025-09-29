<?php

namespace Modules\Employee\App\Enums;

enum MaritalStatuses: string
{
    case SINGLE           = 'single';
    case MARRIED          = 'married';
    case WIDOWED           = 'widowed';
    case DIVORCED         = 'divorced';

    public function label(): string
    {
        return match($this) {
            self::SINGLE    => 'Single',
            self::MARRIED   => 'Married',
            self::WIDOWED    => 'Widowed',
            self::DIVORCED  => 'Divorced',
        };
    }

    public static function toArray(): array
    {
        return array_map(
            fn (self $enum) => [
                'value' => $enum->value,
                'label' => $enum->label(),
            ],
            self::cases()
        );
    }

}
