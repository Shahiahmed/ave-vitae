<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Enums\VisitStatus;
use App\Filament\Widgets\TodayAppointmentsStats;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class TodayAppointments extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.today-appointments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('clinic.nav.registry');
    }

    public static function getNavigationLabel(): string
    {
        return __('clinic.today.title');
    }

    public function getTitle(): string
    {
        return __('clinic.today.title');
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasAnyRole([
            Role::Admin->value,
            Role::Operator->value,
            Role::Reception->value,
        ]) ?? false;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TodayAppointmentsStats::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Appointment::query()->with(['patient', 'department', 'doctor']))
            ->defaultSort('scheduled_at')
            ->emptyStateHeading(__('clinic.empty.today'))
            ->emptyStateIcon('heroicon-o-calendar')
            ->poll('20s')
            ->filters([
                Filter::make('date')
                    ->schema([
                        DatePicker::make('date')
                            ->label(__('clinic.today.date'))
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['date'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('scheduled_at', $date)))
                    ->indicateUsing(function (array $data): ?string {
                        if (blank($data['date'] ?? null)) {
                            return null;
                        }

                        return __('clinic.today.date').': '.\Illuminate\Support\Carbon::parse($data['date'])->format('d.m.Y');
                    }),
            ])
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label(__('clinic.today.time'))
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('patient.name_kk')
                    ->label(__('clinic.appointment.patient'))
                    ->description(fn ($record): ?string => $record->patient?->name_zh)
                    ->searchable(),
                TextColumn::make('patient.phone')
                    ->label(__('clinic.patient.phone'))
                    ->searchable(),
                TextColumn::make('department.name_ru')
                    ->label(__('clinic.appointment.department')),
                TextColumn::make('doctor.name')
                    ->label(__('clinic.appointment.doctor'))
                    ->placeholder(__('clinic.appointment.doctor_any')),
                TextColumn::make('visit_status')
                    ->label(__('clinic.appointment.visit_status'))
                    ->badge(),
            ])
            ->recordActions([
                Action::make('arrived')
                    ->label(__('enums.visit_status.arrived'))
                    ->icon('heroicon-m-check')
                    ->color('info')
                    ->visible(fn (Appointment $record): bool => $record->visit_status === VisitStatus::Waiting
                        && Auth::user()->can('updateVisitStatus', $record))
                    ->action(fn (Appointment $record) => $record->markArrived()),
                Action::make('in_progress')
                    ->label(__('enums.visit_status.in_progress'))
                    ->icon('heroicon-m-play')
                    ->color('warning')
                    ->visible(fn (Appointment $record): bool => $record->visit_status === VisitStatus::Arrived
                        && Auth::user()->can('updateVisitStatus', $record))
                    ->action(fn (Appointment $record) => $record->markInProgress()),
                Action::make('no_show')
                    ->label(__('enums.visit_status.no_show'))
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Appointment $record): bool => $record->visit_status === VisitStatus::Waiting
                        && Auth::user()->can('updateVisitStatus', $record))
                    ->action(fn (Appointment $record) => $record->markNoShow()),
            ]);
    }
}
