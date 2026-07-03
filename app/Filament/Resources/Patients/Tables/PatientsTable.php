<?php

namespace App\Filament\Resources\Patients\Tables;

use App\Enums\PatientCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_kk')
                    ->label(__('clinic.patient.name_kk'))
                    ->description(fn ($record): ?string => $record->name_zh)
                    ->searchable(['name_kk', 'name_zh'])
                    ->sortable(),
                TextColumn::make('phone')
                    ->label(__('clinic.patient.phone'))
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                TextColumn::make('iin')
                    ->label(__('clinic.patient.iin'))
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('birth_date')
                    ->label(__('clinic.patient.birth_date'))
                    ->date('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('clinic.patient.city'))
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('category')
                    ->label(__('clinic.patient.category'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('clinic.patient.registered_at'))
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('clinic.patient.category'))
                    ->options(collect(PatientCategory::cases())
                        ->mapWithKeys(fn (PatientCategory $c) => [$c->value => $c->getLabel()])
                        ->all()),
                TrashedFilter::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('clinic.empty.patients'))
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
