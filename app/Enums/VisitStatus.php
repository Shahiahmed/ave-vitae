<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VisitStatus: string implements HasColor, HasLabel
{
    case Waiting = 'waiting';
    case Arrived = 'arrived';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case NoShow = 'no_show';

    public function getLabel(): string
    {
        return __('enums.visit_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Waiting => 'gray',
            self::Arrived => 'info',
            self::InProgress => 'warning',
            self::Completed => 'success',
            self::NoShow => 'danger',
        };
    }

    /**
     * Допустимые переходы статуса визита.
     *
     * @return array<int, self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Waiting => [self::Arrived, self::NoShow],
            self::Arrived => [self::InProgress],
            self::InProgress => [self::Completed],
            self::Completed, self::NoShow => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
