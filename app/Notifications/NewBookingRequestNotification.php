<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $startTime = \Carbon\Carbon::parse($this->booking->start_time)->format('d M Y, H:i');

        return [
            'booking_id' => $this->booking->id,
            'title' => 'Permintaan Peminjaman Baru',
            'message' => $this->booking->user->name.' mengajukan peminjaman '.$this->booking->laboratory->name.' untuk '.$startTime.'.',
            'status' => 'pending',
            'applicant' => $this->booking->user->name,
            'laboratory' => $this->booking->laboratory->name,
            'icon' => 'fa-bell text-brand-orange',
            'url' => route('dashboard'),
        ];
    }
}
