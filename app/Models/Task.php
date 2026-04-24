<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int             $id
 * @property string          $name
 * @property int             $priority
 * @property int|null        $project_id
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property-read Project|null $project
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'project_id',
    ];

    protected $casts = [
        'priority'   => 'integer',
        'project_id' => 'integer',
    ];

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    /**
     * The project this task belongs to (nullable).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // -----------------------------------------------------------------------
    // Query Scopes
    // -----------------------------------------------------------------------

    /**
     * Filter tasks by project. When $projectId is null the scope is a no-op,
     * so controllers do not need conditional logic around it.
     */
    public function scopeForProject(Builder $query, ?int $projectId): Builder
    {
        return $query->when($projectId, fn (Builder $q) => $q->where('project_id', $projectId));
    }

    /**
     * Order tasks by priority ascending (1 = highest).
     */
    public function scopeOrderedByPriority(Builder $query): Builder
    {
        return $query->orderBy('priority');
    }
}
