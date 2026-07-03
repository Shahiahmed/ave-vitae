<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Enums\PatientCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('clinic.patient.label'))
                    ->columns(2)
                    ->components([
                        TextInput::make('name_kk')
                            ->label(__('clinic.patient.name_kk'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_zh')
                            ->label(__('clinic.patient.name_zh'))
                            ->maxLength(255),
                        TextInput::make('iin')
                            ->label(__('clinic.patient.iin'))
                            ->mask('999999999999')
                            ->rule('digits:12')
                            ->unique(ignoreRecord: true)
                            ->maxLength(12),
                        TextInput::make('phone')
                            ->label(__('clinic.patient.phone'))
                            ->tel()
                            ->mask('+7 (999) 999-9999')
                            ->placeholder('+7 (___) ___-____')
                            ->required(),
                        DatePicker::make('birth_date')
                            ->label(__('clinic.patient.birth_date'))
                            ->native(false)
                            ->displayFormat('d.m.Y')
                            ->maxDate(now()),
                        TextInput::make('city')
                            ->label(__('clinic.patient.city'))
                            ->maxLength(255),
                        Select::make('category')
                            ->label(__('clinic.patient.category'))
                            ->options(collect(PatientCategory::cases())
                                ->mapWithKeys(fn (PatientCategory $c) => [$c->value => $c->getLabel()])
                                ->all())
                            ->default(PatientCategory::Regular->value)
                            ->required(),
                        Textarea::make('notes')
                            ->label(__('clinic.patient.notes'))
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
            ]);
    }
}
