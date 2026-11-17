<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\LabDocument;
use App\Models\LabItem;
use App\Models\Laboratory;
use App\Models\User;
use App\Notifications\BookingStatusUpdatedNotification;
use App\Notifications\NewBookingRequestNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UsabilityTestingAllRolesTest extends TestCase
{
    use RefreshDatabase;

    protected Laboratory $lab;

    protected User $admin;

    protected User $student1;

    protected User $student2;

    protected LabItem $projector;

    protected LabItem $laptop;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->lab = Laboratory::create([
            'name' => 'Lab Bisnis Digital',
            'description' => 'Laboratorium Bisnis Digital',
            'capacity' => 30,
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@univ.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'nim_nip' => '1000000001',
        ]);

        $this->student1 = User::create([
            'name' => 'Budi Mahasiswa',
            'email' => 'budi@univ.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2023000001',
        ]);

        $this->student2 = User::create([
            'name' => 'Siti Mahasiswa',
            'email' => 'siti@univ.ac.id',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2023000002',
        ]);

        $this->projector = LabItem::create([
            'laboratory_id' => $this->lab->id,
            'name' => 'Proyektor Epson',
            'code' => 'PRJ-001',
            'total_quantity' => 2,
            'available_quantity' => 2,
            'condition' => 'good',
            'is_active' => true,
        ]);

        $this->laptop = LabItem::create([
            'laboratory_id' => $this->lab->id,
            'name' => 'Laptop ASUS',
            'code' => 'LPT-001',
            'total_quantity' => 5,
            'available_quantity' => 5,
            'condition' => 'good',
            'is_active' => true,
        ]);
    }

    /** 1. GUEST & HOMEPAGE LOGBOOK TESTING */
    public function test_guest_can_view_homepage_and_submit_logbook(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Lab Bisnis Digital');
        $response->assertSee('Absensi Masuk Lab');

        // Submit absensi
        $postResponse = $this->post(route('logbook.store'), [
            'name' => 'Ahmad Pengunjung',
            'nim' => '2023112233',
            'purpose' => 'Riset Skripsi E-Commerce',
        ]);

        $postResponse->assertSessionHas('logbook_success');
        $this->assertDatabaseHas('lab_logbooks', [
            'name' => 'Ahmad Pengunjung',
            'nim' => '2023112233',
            'purpose' => 'Riset Skripsi E-Commerce',
        ]);
    }

    /** 2. PUBLIC DOCUMENT DOWNLOAD AND EMBED VIEW TESTING */
    public function test_public_can_view_and_download_documents(): void
    {
        $file = UploadedFile::fake()->create('sop_lab.pdf', 500, 'application/pdf');
        $path = $file->storeAs('documents', 'sop_lab.pdf', 'public');

        $doc = LabDocument::create([
            'title' => 'SOP Peminjaman Lab Bisnis Digital',
            'filename' => 'sop_lab.pdf',
            'path' => $path,
            'mime_type' => 'application/pdf',
            'file_size' => 500000,
            'is_active' => true,
        ]);

        // Test download
        $downloadResponse = $this->get(route('documents.download', $doc->id));
        $downloadResponse->assertStatus(200);

        // Test inline view for embed
        $viewResponse = $this->get(route('documents.view', $doc->id));
        $viewResponse->assertStatus(200);
        $viewResponse->assertHeader('Content-Type', 'application/pdf');
    }

    /** 3. MAHASISWA ROLE: CREATE BOOKING WITH DEVICES & ATTACHMENT */
    public function test_mahasiswa_can_create_booking_with_devices_and_attachment(): void
    {
        Notification::fake();

        $attachment = UploadedFile::fake()->create('surat_pengantar.pdf', 300, 'application/pdf');

        $startTime = Carbon::tomorrow()->setTime(10, 0, 0)->format('Y-m-d H:i:s');
        $endTime = Carbon::tomorrow()->setTime(12, 0, 0)->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->student1)->post(route('bookings.store'), [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => 'Praktikum Bisnis Digital & Presentasi Kelompok',
            'items' => [
                $this->projector->id => 1,
                $this->laptop->id => 2,
            ],
            'attachment' => $attachment,
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->student1->id,
            'laboratory_id' => $this->lab->id,
            'purpose' => 'Praktikum Bisnis Digital & Presentasi Kelompok',
            'status' => 'pending',
            'attachment_name' => 'surat_pengantar.pdf',
        ]);

        $booking = Booking::where('user_id', $this->student1->id)->first();
        $this->assertNotNull($booking->attachment_path);

        // Verify booking_items
        $this->assertDatabaseHas('booking_items', [
            'booking_id' => $booking->id,
            'lab_item_id' => $this->projector->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('booking_items', [
            'booking_id' => $booking->id,
            'lab_item_id' => $this->laptop->id,
            'quantity' => 2,
        ]);

        // Notification sent to admin
        Notification::assertSentTo($this->admin, NewBookingRequestNotification::class);
    }

    /** 4. INVALID INPUT: OVERLAPPING LAB SCHEDULE */
    public function test_booking_fails_with_invalid_input_when_lab_is_already_booked(): void
    {
        $startTime = Carbon::tomorrow()->setTime(14, 0, 0);
        $endTime = Carbon::tomorrow()->setTime(16, 0, 0);

        // Booking 1 created
        Booking::create([
            'user_id' => $this->student1->id,
            'laboratory_id' => $this->lab->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => 'Sesi 1',
            'status' => 'pending',
        ]);

        // Student 2 attempts to book overlapping time
        $response = $this->actingAs($this->student2)->post(route('bookings.store'), [
            'start_time' => $startTime->copy()->addHour()->format('Y-m-d H:i:s'),
            'end_time' => $endTime->copy()->addHour()->format('Y-m-d H:i:s'),
            'purpose' => 'Sesi 2 Tabrakan',
        ]);

        $response->assertSessionHasErrors('error');
        $error = session('errors')->first('error');
        $this->assertStringContainsString('Input Tidak Valid: Jadwal peminjaman bentrok', $error);
    }

    /** 5. INVALID INPUT: DEVICE CAPACITY EXCEEDED */
    public function test_booking_fails_with_invalid_input_when_device_is_full_or_insufficient(): void
    {
        $startTime = Carbon::tomorrow()->setTime(8, 0, 0)->format('Y-m-d H:i:s');
        $endTime = Carbon::tomorrow()->setTime(10, 0, 0)->format('Y-m-d H:i:s');

        // Projector only has 2 total units, student requests 5
        $response = $this->actingAs($this->student1)->post(route('bookings.store'), [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'purpose' => 'Minta proyektor banyak',
            'items' => [
                $this->projector->id => 5,
            ],
        ]);

        $response->assertSessionHasErrors('error');
        $error = session('errors')->first('error');
        $this->assertStringContainsString('melebihi kapasitas total', $error);
    }

    /** 6. MAHASISWA ROLE: ACCESS CONTROL & VIEW DETAIL */
    public function test_mahasiswa_can_view_own_booking_but_forbidden_from_others(): void
    {
        $booking1 = Booking::create([
            'user_id' => $this->student1->id,
            'laboratory_id' => $this->lab->id,
            'start_time' => Carbon::tomorrow()->setTime(9, 0, 0),
            'end_time' => Carbon::tomorrow()->setTime(11, 0, 0),
            'purpose' => 'Kegiatan Budi',
            'status' => 'pending',
        ]);

        // Student 1 can view own booking
        $response1 = $this->actingAs($this->student1)->get(route('bookings.show', $booking1->id));
        $response1->assertStatus(200);
        $response1->assertSee('Kegiatan Budi');

        // Student 2 CANNOT view student 1's booking
        $response2 = $this->actingAs($this->student2)->get(route('bookings.show', $booking1->id));
        $response2->assertStatus(403);

        // Student 1 CANNOT access admin area
        $adminResponse = $this->actingAs($this->student1)->get(route('admin.items.index'));
        $adminResponse->assertStatus(403);
    }

    /** 7. ADMIN ROLE: FILTER DASHBOARD & APPROVE BOOKING */
    public function test_admin_can_filter_dashboard_and_approve_booking(): void
    {
        Notification::fake();

        $booking = Booking::create([
            'user_id' => $this->student1->id,
            'laboratory_id' => $this->lab->id,
            'start_time' => Carbon::tomorrow()->setTime(13, 0, 0),
            'end_time' => Carbon::tomorrow()->setTime(15, 0, 0),
            'purpose' => 'Praktikum Analisis Data',
            'status' => 'pending',
        ]);

        // Admin checks dashboard with filter
        $filterResponse = $this->actingAs($this->admin)->get(route('dashboard', [
            'status' => 'pending',
            'search' => 'Budi',
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Praktikum Analisis Data');

        // Admin approves booking
        $approveResponse = $this->actingAs($this->admin)->patch(route('bookings.status', $booking->id), [
            'status' => 'approved',
            'notes' => 'Disetujui oleh Administrator.',
        ]);

        $approveResponse->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);

        // Student receives notification
        Notification::assertSentTo($this->student1, BookingStatusUpdatedNotification::class);
    }

    /** 8. NOTIFICATION REDIRECT TO BOOKING DETAIL */
    public function test_clicking_notification_redirects_to_booking_detail(): void
    {
        $booking = Booking::create([
            'user_id' => $this->student1->id,
            'laboratory_id' => $this->lab->id,
            'start_time' => Carbon::tomorrow()->setTime(15, 0, 0),
            'end_time' => Carbon::tomorrow()->setTime(17, 0, 0),
            'purpose' => 'Peminjaman Test Notif',
            'status' => 'approved',
        ]);

        $notification = new BookingStatusUpdatedNotification($booking, $this->admin, 'Disetujui');
        $this->student1->notify($notification);

        $dbNotification = $this->student1->notifications()->first();
        $this->assertNotNull($dbNotification);

        // Click notification link in browser (GET request)
        $response = $this->actingAs($this->student1)->get(route('notifications.read', $dbNotification->id));
        $response->assertRedirect(route('bookings.show', $booking->id));

        // Marked as read
        $this->assertNotNull($dbNotification->fresh()->read_at);
    }

    /** 9. ADMIN CRUD INVENTORY LAB (WITHOUT LAB DROPDOWN) */
    public function test_admin_can_crud_lab_item_auto_assigned_to_single_lab(): void
    {
        // Store
        $createResponse = $this->actingAs($this->admin)->post(route('admin.items.store'), [
            'name' => 'Mikrofon Wireless Podcast',
            'code' => 'MIC-001',
            'condition' => 'good',
            'total_quantity' => 4,
            'available_quantity' => 4,
            'description' => 'Mikrofon studio untuk konten digital',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseHas('lab_items', [
            'code' => 'MIC-001',
            'laboratory_id' => $this->lab->id,
        ]);

        $item = LabItem::where('code', 'MIC-001')->first();

        // Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.items.update', $item->id), [
            'name' => 'Mikrofon Wireless Podcast Pro',
            'code' => 'MIC-001',
            'condition' => 'good',
            'total_quantity' => 6,
            'available_quantity' => 6,
            'description' => 'Updated mic',
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseHas('lab_items', [
            'id' => $item->id,
            'total_quantity' => 6,
        ]);

        // Destroy
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.items.destroy', $item->id));
        $deleteResponse->assertRedirect(route('admin.items.index'));
        $this->assertDatabaseMissing('lab_items', ['id' => $item->id]);
    }

    /** 10. ADMIN DOCUMENT MANAGEMENT */
    public function test_admin_can_upload_and_delete_documents(): void
    {
        $file = UploadedFile::fake()->create('formulir_izin.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $uploadResponse = $this->actingAs($this->admin)->post(route('admin.documents.store'), [
            'title' => 'Formulir Izin Laboratorium',
            'file' => $file,
        ]);

        $uploadResponse->assertSessionHas('success');
        $this->assertDatabaseHas('lab_documents', [
            'title' => 'Formulir Izin Laboratorium',
        ]);

        $doc = LabDocument::where('title', 'Formulir Izin Laboratorium')->first();

        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.documents.destroy', $doc->id));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('lab_documents', ['id' => $doc->id]);
    }
}
