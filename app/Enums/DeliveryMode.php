<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryMode: string implements HasLabel
{
    case Online = 'online';
    case InPerson = 'in_person';
    case Hybrid = 'hybrid';

    public function getLabel(): string
    {
        return match ($this) {
            self::Online => 'Online (Live Virtual)',
            self::InPerson => 'In-Person',
            self::Hybrid => 'Hybrid',
        };
    }
}
