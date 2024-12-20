<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'birth_date',
        'emergency_contact',
        'emergency_phone',
        'health_conditions',
        'plan_id',
        'plan_start_date',
        'plan_end_date',
        'monthly_fee',
        'payment_day',
        'active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'plan_start_date' => 'date',
        'plan_end_date' => 'date',
        'active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
}
