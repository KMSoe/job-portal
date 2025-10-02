<?php

namespace Modules\Organization\App\Enums;

enum GenderTypes: string {
    case MALE            = 'male';
    case FEMALE          = 'female';
    case PREFERNOTTOTELL = 'prefer_not_to_tell';

    public function label(): string
    {
        return match($this) {
            self::MALE               => 'Male',
            self::FEMALE             => 'Female',
            self::PREFERNOTTOTELL    => 'Prefer not to tell',
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
