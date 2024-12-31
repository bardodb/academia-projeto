<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'qr_code',
        'steps',
        'distance',
        'calories',
        'heart_rate_avg',
        'weight',
        'notes',
        'status',
        'check_out_time',
        'created_at',
    ];

    protected $casts = [
        'steps' => 'integer',
        'distance' => 'float',
        'calories' => 'float',
        'heart_rate_avg' => 'float',
        'weight' => 'float',
        'created_at' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getFormattedDistanceAttribute()
    {
        return $this->distance ? number_format($this->distance / 1000, 2) . ' km' : null;
    }

    public function getDurationAttribute()
    {
        if (!$this->check_out_time) {
            return null;
        }
        
        $start = $this->created_at;
        $end = $this->check_out_time;
        
        return $start->diffInMinutes($end) . ' minutes';
    }
}
