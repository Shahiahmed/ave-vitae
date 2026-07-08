<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Enums\TreatmentStatus;
use App\Enums\VisitStatus;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
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

class MyAppointments extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.my-appointments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('clinic.my.title');
    }

    public function getTitle(): string
    {
        return __('clinic.my.title');
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole(Role::Doctor->value) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()
                    ->where('doctor_id', Auth::id())
                    ->with(['patient', 'department'])
            )
            ->defaultSort('scheduled_at')
            ->emptyStateHeading(__('clinic.empty.my'))
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->filters([
                Filter::make('upcoming')
                    ->label(__('clinic.my.filter_upcoming'))
                    ->query(fn (Builder $query): Builder => $query->where('scheduled_at', '>=', now()->startOfDay()))
                    ->default(),
            ])
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label(__('clinic.appointment.scheduled_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('patient.name_kk')
                    ->label(__('clinic.appointment.patient'))
                    ->description(fn ($record): ?string => $record->patient?->name_zh)
                    ->searchable(),
                TextColumn::make('department.name_ru')
                    ->label(__('clinic.appointment.department')),
                TextColumn::make('visit_status')
                    ->label(__('clinic.appointment.visit_status'))
                    ->badge(),
                TextColumn::make('treatment_status')
                    ->label(__('clinic.appointment.treatment_status'))
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('treatment_amount')
                    ->label(__('clinic.appointment.treatment_amount'))
                    ->numeric(decimalPlaces: 0)
                    ->suffix(' ₸')
                    ->placeholder('—'),
            ])
            ->recordActions([
                Action::make('complete')
                    ->label(__('clinic.my.complete'))
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('success')
                    ->visible(fn (Appointment $record): bool => $record->visit_status === VisitStatus::InProgress
                        && Auth::user()->can('complete', $record))
                    ->schema([
                        Select::make('treatment_status')
                            ->label(__('clinic.appointment.treatment_status'))
                            ->options(collect(TreatmentStatus::cases())
                                ->mapWithKeys(fn (TreatmentStatus $s) => [$s->value => $s->getLabel()])
                                ->all())
                            ->required()
                            ->live(),
                        TextInput::make('treatment_amount')
                            ->label(__('clinic.appointment.treatment_amount'))
                            ->numeric()
                            ->minValue(0)
                            ->suffix('₸')
                            ->visible(fn (Get $get): bool => $get('treatment_status') === TreatmentStatus::Treated->value)
                            ->required(fn (Get $get): bool => $get('treatment_status') === TreatmentStatus::Treated->value),
                        Textarea::make('notes_kk')
                            ->label(__('clinic.appointment.notes_kk'))
                            ->rows(3),
                        Textarea::make('notes_zh')
                            ->label(__('clinic.appointment.notes_zh'))
                            ->rows(3),
                    ])
                    ->action(function (Appointment $record, array $data): void {
                        $record->complete(
                            TreatmentStatus::from($data['treatment_status']),
                            $data['notes_kk'] ?? null,
                            $data['notes_zh'] ?? null,
                            isset($data['treatment_amount']) ? (float) $data['treatment_amount'] : null,
                        );

                        Notification::make()
                            ->title(__('clinic.my.completed_notice'))
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
