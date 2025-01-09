<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    /**
     * Get the students that are assigned this workout.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'member_workouts')
            ->withPivot('completed_at', 'notes', 'assigned_at')
            ->withTimestamps();
    }

    /**
     * Get the exercises for this workout.
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Get the instructor who created this workout.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 