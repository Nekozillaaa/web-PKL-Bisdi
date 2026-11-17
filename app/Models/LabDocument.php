<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'filename',
        'path',
        'mime_type',
        'file_size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
    ];
}
