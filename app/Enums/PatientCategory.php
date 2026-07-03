<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PatientCategory: string implements HasColor, HasLabel
{
    case Regular = 'regular';
    case Vip = 'vip';

    public function getLabel(): string
    {
        return __('enums.patient_category.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Regular => 'gray',
            self::Vip => 'danger',
        };
    }
}
