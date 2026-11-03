<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratory_id',
        'name',
        'code',
        'description',
        'total_quantity',
        'available_quantity',
        'condition',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
