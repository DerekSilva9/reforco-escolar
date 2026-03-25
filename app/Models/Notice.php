<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['title', 'body', 'pinned', 'starts_at', 'ends_at', 'published_at', 'created_by'])]
class Notice extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'pinned' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeVisibleOnDashboard(Builder $query): Builder
    {
        $now = now();

        return $query
            ->whereNotNull('published_at')
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }
}

