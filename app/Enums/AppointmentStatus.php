<?php

declare(strict_types=1);

namespace App\Enums;

enum AppointmentStatus: string
{
    case Booked = 'booked';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Booked => 'Booked',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }
}
