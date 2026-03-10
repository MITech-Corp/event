<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_identifier',
        'employee_name',
        'employee_position',
        'employee_office',
        'checkin_time',
    ];

    protected $casts = [
        'checkin_time' => 'datetime',
    ];
}

