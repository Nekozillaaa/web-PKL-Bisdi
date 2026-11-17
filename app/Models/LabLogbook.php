<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nim',
        'purpose',
        'entry_at',
    ];

    protected $casts = [
        'entry_at' => 'datetime',
    ];
}
