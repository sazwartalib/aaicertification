<?php

namespace App\Enums;

enum DeliveryMode: string
{
    case Online = 'online';
    case InPerson = 'in_person';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Online => 'Online (Live Virtual)',
            self::InPerson => 'In-Person',
            self::Hybrid => 'Hybrid',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $case): array => ['value' => $case->value, 'label' => $case->label()],
            self::cases(),
        );
    }
}
