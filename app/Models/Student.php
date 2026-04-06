<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'birth_date', 'responsavel_id', 'parent_name', 'phone', 'team_id', 'fee', 'due_day', 'active', 'notes', 'class_start_time', 'class_end_time', 'school_year', 'school'])]
class Student extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'fee' => 'decimal:2',
            'active' => 'boolean',
            'class_start_time' => 'datetime:H:i',
            'class_end_time' => 'datetime:H:i',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Eager load common relationships to prevent N+1 queries
     */
    public function scopeWithCommonRelations(Builder $query): Builder
    {
        return $query->with([
            'team:id,name,time,user_id',
            'responsavel:id,name,phone',
        ]);
    }
}
