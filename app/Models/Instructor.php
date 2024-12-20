<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'specialties',
        'schedule',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'specialties' => 'array',
        'schedule' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
