<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role as RoleEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('clinic.user.label'))
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->label(__('clinic.user.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('clinic.user.email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('clinic.user.password'))
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        Select::make('roles')
                            ->label(__('clinic.user.roles'))
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(
                                fn (Role $record): string => RoleEnum::tryFrom($record->name)?->getLabel() ?? $record->name,
                            ),
                    ]),

                Section::make(__('clinic.user.department'))
                    ->description(__('enums.role.doctor'))
                    ->columns(2)
                    ->components([
                        Select::make('department_id')
                            ->label(__('clinic.user.department'))
                            ->relationship('department', 'name_ru')
                            ->searchable()
                            ->preload(),
                        TextInput::make('name_kk')
                            ->label(__('clinic.user.name_kk'))
                            ->maxLength(255),
                        TextInput::make('name_zh')
                            ->label(__('clinic.user.name_zh'))
                            ->maxLength(255),
                    ]),
            ]);
    }
}
