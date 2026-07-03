<?php

namespace App\Models;

use App\Enums\PatientCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_kk',
        'name_zh',
        'iin',
        'phone',
        'birth_date',
        'city',
        'category',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'category' => PatientCategory::class,
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
