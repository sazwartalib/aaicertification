<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CertificateStatus: string implements HasColor, HasLabel
{
    case Valid = 'valid';
    case Revoked = 'revoked';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Valid => 'success',
            self::Revoked => 'danger',
        };
    }
}
