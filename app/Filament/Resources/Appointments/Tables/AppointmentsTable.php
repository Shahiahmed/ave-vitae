<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\VisitStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label(__('clinic.appointment.scheduled_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.name_kk')
                    ->label(__('clinic.appointment.patient'))
                    ->description(fn ($record): ?string => $record->patient?->phone)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name_ru')
                    ->label(__('clinic.appointment.department'))
                    ->sortable(),
                TextColumn::make('doctor.name')
                    ->label(__('clinic.appointment.doctor'))
                    ->placeholder(__('clinic.appointment.doctor_any')),
                TextColumn::make('visit_status')
                    ->label(__('clinic.appointment.visit_status'))
                    ->badge(),
                TextColumn::make('treatment_status')
                    ->label(__('clinic.appointment.treatment_status'))
                    ->badge()
                    ->placeholder('—'),
            ])
            ->defaultSort('scheduled_at')
            ->emptyStateHeading(__('clinic.empty.appointments'))
            ->filters([
                Filter::make('upcoming')
                    ->label(__('clinic.appointment.filter_upcoming'))
                    ->query(fn (Builder $query): Builder => $query->where('scheduled_at', '>=', now()->startOfDay()))
                    ->default(),
                SelectFilter::make('department_id')
                    ->label(__('clinic.appointment.department'))
                    ->relationship('department', 'name_ru'),
                SelectFilter::make('doctor_id')
                    ->label(__('clinic.appointment.doctor'))
                    ->relationship('doctor', 'name'),
                SelectFilter::make('visit_status')
                    ->label(__('clinic.appointment.visit_status'))
                    ->options(collect(VisitStatus::cases())
                        ->mapWithKeys(fn (VisitStatus $s) => [$s->value => $s->getLabel()])
                        ->all()),
                Filter::make('period')
                    ->schema([
                        DatePicker::make('from')
                            ->label(__('clinic.appointment.filter_from'))
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                        DatePicker::make('to')
                            ->label(__('clinic.appointment.filter_to'))
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('scheduled_at', '>=', \Illuminate\Support\Carbon::parse($date)->format('Y-m-d')))
                        ->when($data['to'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('scheduled_at', '<=', \Illuminate\Support\Carbon::parse($date)->format('Y-m-d')))),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
