<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite does not support ALTER COLUMN for enum.
        // We use string columns instead to stay compatible.
        // For MySQL, we would use DB::statement to change enum.

        // Update users role to only admin/mahasiswa
        DB::table('users')
            ->whereNotIn('role', ['admin', 'mahasiswa'])
            ->update(['role' => 'mahasiswa']);

        // Update bookings status: map old intermediate statuses to 'approved'
        DB::table('bookings')
            ->whereIn('status', ['approved_dosen', 'approved_laboran'])
            ->update(['status' => 'approved']);
    }

    public function down(): void
    {
        // No reverse needed for data migration
    }
};
