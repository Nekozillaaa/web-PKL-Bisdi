<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public User $updatedBy,
        public ?string $notes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            'pending' => 'Menunggu',
        ];

        $label = $statusLabels[$this->booking->status] ?? $this->booking->status;

        return [
            'booking_id' => $this->booking->id,
            'title' => 'Pembaruan Status Peminjaman',
            'message' => 'Peminjaman '.$this->booking->laboratory->name.' telah diperbarui menjadi "'.$label.'" oleh '.$this->updatedBy->name.'.',
            'status' => $this->booking->status,
            'status_label' => $label,
            'updated_by' => $this->updatedBy->name,
            'notes' => $this->notes,
            'icon' => match ($this->booking->status) {
                'approved', 'completed' => 'fa-circle-check text-green-400',
                'rejected' => 'fa-circle-xmark text-red-400',
                default => 'fa-clock text-yellow-400',
            },
            'url' => route('bookings.show', $this->booking->id),
        ];
    }
}
