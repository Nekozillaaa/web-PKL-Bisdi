<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'lab_item_id',
        'quantity',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function labItem()
    {
        return $this->belongsTo(LabItem::class);
    }
}
