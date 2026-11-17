<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\LabItem;
use App\Models\Laboratory;
use App\Models\User;
use App\Notifications\BookingStatusUpdatedNotification;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminLabItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_lab_items(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)->get(route('admin.items.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_and_view_lab_items(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lab = Laboratory::create([
            'name' => 'Lab Fisika',
            'description' => 'Lab Fisika Dasar',
            'capacity' => 20,
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Mikroskop Binokuler',
            'code' => 'MKB-001',
            'description' => 'Pembesaran 1000x',
            'total_quantity' => 5,
            'available_quantity' => 5,
            'condition' => 'good',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.items.index'));
        $response->assertStatus(200);
        $response->assertSee('Mikroskop Binokuler');
        $response->assertSee('MKB-001');
    }

    public function test_admin_can_create_lab_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lab = Laboratory::create([
            'name' => 'Lab Elektronika',
            'description' => 'Lab Elektronika',
            'capacity' => 25,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.items.store'), [
            'laboratory_id' => $lab->id,
            'name' => 'Multimeter Digital',
            'code' => 'MLT-001',
            'description' => 'Auto-ranging multimeter',
            'total_quantity' => 10,
            'available_quantity' => 10,
            'condition' => 'good',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseHas('lab_items', [
            'name' => 'Multimeter Digital',
            'code' => 'MLT-001',
        ]);
    }

    public function test_admin_can_update_lab_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lab = Laboratory::create([
            'name' => 'Lab Kimia',
            'description' => 'Lab Kimia',
            'capacity' => 20,
            'is_active' => true,
        ]);

        $item = LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Centrifuge',
            'code' => 'CTF-001',
            'description' => 'Deskripsi lama',
            'total_quantity' => 2,
            'available_quantity' => 2,
            'condition' => 'good',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.items.update', $item->id), [
            'laboratory_id' => $lab->id,
            'name' => 'Centrifuge High Speed',
            'code' => 'CTF-001',
            'description' => 'Deskripsi baru',
            'total_quantity' => 3,
            'available_quantity' => 3,
            'condition' => 'good',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseHas('lab_items', [
            'id' => $item->id,
            'name' => 'Centrifuge High Speed',
            'total_quantity' => 3,
        ]);
    }

    public function test_admin_can_delete_lab_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lab = Laboratory::create([
            'name' => 'Lab Komputasi',
            'description' => 'Lab Komputasi',
            'capacity' => 30,
            'is_active' => true,
        ]);

        $item = LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Keyboard Rusak',
            'code' => 'KBD-999',
            'total_quantity' => 1,
            'available_quantity' => 0,
            'condition' => 'damaged',
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.items.destroy', $item->id));
        $response->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseMissing('lab_items', ['id' => $item->id]);
    }

    public function test_admin_can_update_booking_status_from_approved_laboran_to_completed(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $lab = Laboratory::create([
            'name' => 'Lab Jaringan',
            'description' => 'Lab Jaringan',
            'capacity' => 20,
            'is_active' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $student->id,
            'laboratory_id' => $lab->id,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHours(2),
            'purpose' => 'Uji Jaringan',
            'status' => 'approved_laboran',
        ]);

        $response = $this->actingAs($admin)->patch(route('bookings.status', $booking->id), [
            'status' => 'completed',
            'notes' => 'Telah selesai digunakan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed',
        ]);

        Notification::assertSentTo($student, BookingStatusUpdatedNotification::class);
    }

    public function test_booking_creation_notifies_admin(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'mahasiswa']);
        $admin = User::factory()->create(['role' => 'admin']);

        $lab = Laboratory::create([
            'name' => 'Lab Bisnis Digital',
            'description' => 'Lab Bisnis Digital',
            'capacity' => 25,
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->post(route('bookings.store'), [
            'start_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_time' => now()->addDays(2)->addHours(2)->format('Y-m-d H:i:s'),
            'purpose' => 'Praktikum Bisnis Digital',
        ]);

        $response->assertRedirect(route('dashboard'));

        Notification::assertSentTo($admin, NewBookingRequestNotification::class);
    }
}
