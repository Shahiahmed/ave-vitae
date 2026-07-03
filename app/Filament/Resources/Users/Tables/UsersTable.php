<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Role as RoleEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('clinic.user.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('clinic.user.email'))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label(__('clinic.user.roles'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => RoleEnum::tryFrom($state)?->getLabel() ?? $state),
                TextColumn::make('department.name_ru')
                    ->label(__('clinic.user.department'))
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
