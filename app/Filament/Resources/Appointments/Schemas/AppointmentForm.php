<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\Role;
use App\Enums\TreatmentStatus;
use App\Enums\VisitStatus;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('clinic.appointment.section_main'))
                    ->columns(2)
                    ->components([
                        Select::make('patient_id')
                            ->label(__('clinic.appointment.patient'))
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Patient::query()
                                ->where('name_kk', 'like', "%{$search}%")
                                ->orWhere('name_zh', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%")
                                ->orWhere('iin', 'like', "%{$search}%")
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn (Patient $p): array => [$p->id => "{$p->name_kk} · {$p->phone}"])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $patient = Patient::find($value);

                                return $patient ? "{$patient->name_kk} · {$patient->phone}" : null;
                            })
                            ->createOptionForm([
                                TextInput::make('name_kk')
                                    ->label(__('clinic.patient.name_kk'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('name_zh')
                                    ->label(__('clinic.patient.name_zh'))
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label(__('clinic.patient.phone'))
                                    ->tel()
                                    ->telRegex('/^[\d\s()+\-]+$/')
                                    ->mask('+7 (999) 999-9999')
                                    ->required(),
                                TextInput::make('iin')
                                    ->label(__('clinic.patient.iin'))
                                    ->mask('999999999999')
                                    ->rule('digits:12')
                                    ->unique('patients', 'iin'),
                            ])
                            ->createOptionUsing(fn (array $data): int => Patient::create($data)->id)
                            ->columnSpanFull(),

                        Select::make('department_id')
                            ->label(__('clinic.appointment.department'))
                            ->options(fn (): array => Department::query()
                                ->where('is_active', true)
                                ->pluck('name_ru', 'id')
                                ->all())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('doctor_id', null)),

                        Select::make('doctor_id')
                            ->label(__('clinic.appointment.doctor'))
                            ->placeholder(__('clinic.appointment.doctor_any'))
                            ->searchable()
                            ->options(fn (Get $get): array => filled($get('department_id'))
                                ? User::query()
                                    ->role(Role::Doctor->value)
                                    ->where('department_id', $get('department_id'))
                                    ->pluck('name', 'id')
                                    ->all()
                                : []),

                        DateTimePicker::make('scheduled_at')
                            ->label(__('clinic.appointment.scheduled_at'))
                            ->native(false)
                            ->seconds(false)
                            ->minutesStep(30)
                            ->displayFormat('d.m.Y H:i')
                            ->required()
                            ->minDate(fn (string $operation) => $operation === 'create' ? now() : null),

                        Select::make('visit_status')
                            ->label(__('clinic.appointment.visit_status'))
                            ->options(collect(VisitStatus::cases())
                                ->mapWithKeys(fn (VisitStatus $s) => [$s->value => $s->getLabel()])
                                ->all())
                            ->default(VisitStatus::Waiting->value)
                            ->required()
                            ->visible(fn (string $operation): bool => $operation === 'edit'),

                        Hidden::make('created_by')
                            ->default(fn () => Auth::id()),
                    ]),

                Section::make(__('clinic.appointment.section_notes'))
                    ->columns(2)
                    ->components([
                        Select::make('treatment_status')
                            ->label(__('clinic.appointment.treatment_status'))
                            ->options(collect(TreatmentStatus::cases())
                                ->mapWithKeys(fn (TreatmentStatus $s) => [$s->value => $s->getLabel()])
                                ->all())
                            ->live()
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                        TextInput::make('treatment_amount')
                            ->label(__('clinic.appointment.treatment_amount'))
                            ->numeric()
                            ->minValue(0)
                            ->suffix('₸')
                            ->visible(fn (string $operation, Get $get): bool => $operation === 'edit'
                                && $get('treatment_status') === TreatmentStatus::Treated->value),
                        Textarea::make('notes_kk')
                            ->label(__('clinic.appointment.notes_kk'))
                            ->rows(3),
                        Textarea::make('notes_zh')
                            ->label(__('clinic.appointment.notes_zh'))
                            ->rows(3),
                    ]),
            ]);
    }
}
