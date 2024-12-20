<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'check_in_time',
        'check_out_time',
        'qr_code'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
