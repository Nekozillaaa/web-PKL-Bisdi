<?php

namespace Database\Seeders;

use App\Models\LabDocument;
use App\Models\LabItem;
use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nim_nip' => '1000000001',
        ]);

        User::create([
            'name' => 'Andi Mahasiswa',
            'email' => 'mahasiswa@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2023000001',
        ]);

        $lab = Laboratory::create([
            'name' => 'Lab Bisnis Digital',
            'description' => 'Laboratorium Bisnis Digital dilengkapi dengan perangkat komputer, proyektor, dan fasilitas pendukung pembelajaran bisnis berbasis teknologi.',
            'capacity' => 30,
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Proyektor Epson EB-X500',
            'code' => 'PRJ-LBD-001',
            'description' => 'Proyektor resolusi XGA 3600 Lumens lengkap dengan kabel HDMI dan remote.',
            'total_quantity' => 2,
            'available_quantity' => 2,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Laptop ASUS VivoBook',
            'code' => 'LPT-LBD-001',
            'description' => 'Laptop Intel Core i5 RAM 8GB SSD 256GB untuk presentasi dan praktikum.',
            'total_quantity' => 10,
            'available_quantity' => 10,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab->id,
            'name' => 'Wireless Presenter Pointer Laser',
            'code' => 'PTR-LBD-001',
            'description' => 'Pointer presenter wireless 2.4GHz USB receiver.',
            'total_quantity' => 5,
            'available_quantity' => 5,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabDocument::create([
            'title' => 'SOP & Panduan Penggunaan Lab Bisnis Digital',
            'filename' => 'sop_lab_bisnis_digital.pdf',
            'path' => 'documents/sop_lab_bisnis_digital.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 1024,
            'is_active' => true,
        ]);
    }
}
