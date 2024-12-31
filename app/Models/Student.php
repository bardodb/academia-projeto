<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instructor_id',
        'plan_id',
        'phone',
        'birth_date',
        'emergency_contact',
        'emergency_phone',
        'health_conditions',
        'plan_start_date',
        'plan_end_date',
        'monthly_fee',
        'payment_day',
        'active',
        'google_token',
        'google_refresh_token',
        'google_fit_data',
        'last_sync_at',
        'google_connected'
    ];

    protected $casts = [
        'google_fit_data' => 'array',
        'last_sync_at' => 'datetime',
        'google_connected' => 'boolean',
        'birth_date' => 'date',
        'plan_start_date' => 'date',
        'plan_end_date' => 'date',
        'active' => 'boolean'
    ];

    /**
     * Get the user that owns the student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instructor assigned to this student.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the plan that the student is subscribed to.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the workouts assigned to this student.
     */
    public function workouts(): BelongsToMany
    {
        return $this->belongsToMany(Workout::class, 'member_workouts')
            ->withPivot('completed_at', 'notes', 'assigned_at')
            ->withTimestamps();
    }

    /**
     * Get the student's check-ins.
     */
    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    /**
     * Get the student's payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestCheckIn()
    {
        return $this->hasOne(CheckIn::class)->latest();
    }

    public function todayCheckIn()
    {
        return $this->hasOne(CheckIn::class)->whereDate('created_at', today());
    }
}
