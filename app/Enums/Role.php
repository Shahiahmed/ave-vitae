<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasLabel
{
    case Admin = 'admin';
    case Operator = 'operator';
    case Reception = 'reception';
    case Doctor = 'doctor';

    public function getLabel(): string
    {
        return __('enums.role.'.$this->value);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
