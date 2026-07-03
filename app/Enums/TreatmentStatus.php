<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TreatmentStatus: string implements HasColor, HasLabel
{
    case Treated = 'treated';
    case NotTreated = 'not_treated';

    public function getLabel(): string
    {
        return __('enums.treatment_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Treated => 'success',
            self::NotTreated => 'danger',
        };
    }
}
