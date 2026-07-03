<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_ru')
                    ->label(__('clinic.department.name_ru'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_kk')
                    ->label(__('clinic.department.name_kk'))
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label(__('clinic.department.is_active'))
                    ->default(true),
            ]);
    }
}
