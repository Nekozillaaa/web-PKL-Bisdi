<?php

namespace Database\Seeders;

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
            'name' => 'Budi Laboran',
            'email' => 'laboran@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => 'laboran',
            'nim_nip' => '1000000002',
        ]);

        User::create([
            'name' => 'Dr. Siti Dosen',
            'email' => 'dosen@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '1000000003',
        ]);

        User::create([
            'name' => 'Andi Mahasiswa',
            'email' => 'mahasiswa@univ.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '2023000001',
        ]);

        $lab1 = Laboratory::create([
            'name' => 'Lab Komputer Dasar',
            'description' => 'Laboratorium dilengkapi dengan 30 PC Intel Core i5 untuk praktikum dasar pemrograman.',
            'capacity' => 30,
            'is_active' => true,
        ]);

        $lab2 = Laboratory::create([
            'name' => 'Lab Jaringan & Keamanan',
            'description' => 'Laboratorium Cisco Router, Switch, dan server untuk simulasi jaringan.',
            'capacity' => 25,
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab1->id,
            'name' => 'Proyektor Epson EB-X500',
            'code' => 'PRJ-LKD-001',
            'description' => 'Proyektor resolusi XGA 3600 Lumens lengkap dengan kabel HDMI dan remote.',
            'total_quantity' => 2,
            'available_quantity' => 2,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab1->id,
            'name' => 'Wireless Presenter Pointer Laser',
            'code' => 'PTR-LKD-002',
            'description' => 'Pointer presenter wireless 2.4GHz USB receiver.',
            'total_quantity' => 5,
            'available_quantity' => 4,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab2->id,
            'name' => 'Cisco Catalyst Switch 2960',
            'code' => 'SWT-LJK-001',
            'description' => 'Switch 24-port Gigabit Ethernet untuk praktikum VLAN & Routing.',
            'total_quantity' => 6,
            'available_quantity' => 5,
            'condition' => 'good',
            'is_active' => true,
        ]);

        LabItem::create([
            'laboratory_id' => $lab2->id,
            'name' => 'Crimping Tool & LAN Tester Kit',
            'code' => 'TLK-LJK-002',
            'description' => 'Tang crimping RJ45/RJ11 beserta cable tester baterai 9V.',
            'total_quantity' => 10,
            'available_quantity' => 8,
            'condition' => 'good',
            'is_active' => true,
        ]);
    }
}
