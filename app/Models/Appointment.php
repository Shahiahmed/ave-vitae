<?php

namespace App\Models;

use App\Enums\TreatmentStatus;
use App\Enums\VisitStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeException;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'department_id',
        'doctor_id',
        'scheduled_at',
        'visit_status',
        'treatment_status',
        'notes_kk',
        'notes_zh',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'visit_status' => VisitStatus::class,
            'treatment_status' => TreatmentStatus::class,
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Перевести визит в новый статус с проверкой допустимости перехода.
     */
    public function transitionTo(VisitStatus $target): void
    {
        if (! $this->visit_status->canTransitionTo($target)) {
            throw new RuntimeException(
                "Недопустимый переход статуса: {$this->visit_status->value} → {$target->value}"
            );
        }

        $this->visit_status = $target;
        $this->save();
    }

    public function markArrived(): void
    {
        $this->transitionTo(VisitStatus::Arrived);
    }

    public function markInProgress(): void
    {
        $this->transitionTo(VisitStatus::InProgress);
    }

    public function markNoShow(): void
    {
        $this->transitionTo(VisitStatus::NoShow);
    }

    public function complete(TreatmentStatus $treatmentStatus, ?string $notesKk = null, ?string $notesZh = null): void
    {
        if (! $this->visit_status->canTransitionTo(VisitStatus::Completed)) {
            throw new RuntimeException(
                "Нельзя завершить приём из статуса {$this->visit_status->value}"
            );
        }

        $this->visit_status = VisitStatus::Completed;
        $this->treatment_status = $treatmentStatus;
        $this->notes_kk = $notesKk;
        $this->notes_zh = $notesZh;
        $this->save();
    }
}
