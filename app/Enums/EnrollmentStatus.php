<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending = 'pending';
    case Contacted = 'contacted';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    /**
     * Badge colour tone used by the admin UI.
     */
    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Contacted => 'info',
            self::Confirmed => 'success',
            self::Cancelled => 'danger',
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
