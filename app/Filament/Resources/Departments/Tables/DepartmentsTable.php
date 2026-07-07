<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_ru')
                    ->label(__('clinic.department.name_ru'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name_kk')
                    ->label(__('clinic.department.name_kk'))
                    ->searchable(),
                TextColumn::make('name_zh')
                    ->label(__('clinic.department.name_zh'))
                    ->placeholder('—')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('clinic.department.is_active'))
                    ->boolean(),
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
