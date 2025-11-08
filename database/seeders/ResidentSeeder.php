<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $residentsData = [
            [
                'nik' => '3201234567890001',
                'name' => 'Bambang Sutrisno',
                'no_kk' => '3201234567890001',
                'address' => 'Jl. Merdeka No. 1, RT 03 RW 05',
                'phone_number' => '082123456789',
                'status' => 'tetap',
                'is_head_of_family' => true,
            ],
            [
                'nik' => '3201234567890002',
                'name' => 'Siti Nurhaliza',
                'no_kk' => '3201234567890001',
                'address' => 'Jl. Merdeka No. 1, RT 03 RW 05',
                'phone_number' => '082123456790',
                'status' => 'tetap',
                'is_head_of_family' => false,
            ],
            [
                'nik' => '3201234567890003',
                'name' => 'Ahmad Wijaya',
                'no_kk' => '3201234567890003',
                'address' => 'Jl. Merdeka No. 3, RT 03 RW 05',
                'phone_number' => '082987654321',
                'status' => 'tetap',
                'is_head_of_family' => true,
            ],
            [
                'nik' => '3201234567890004',
                'name' => 'Rini Handayani',
                'no_kk' => '3201234567890004',
                'address' => 'Jl. Gatot Subroto No. 5, RT 03 RW 05',
                'phone_number' => '085543210987',
                'status' => 'kontrak',
                'is_head_of_family' => true,
            ],
            [
                'nik' => '3201234567890005',
                'name' => 'Hendra Kusuma',
                'no_kk' => '3201234567890005',
                'address' => 'Jl. Gatot Subroto No. 7, RT 03 RW 05',
                'phone_number' => '081234567890',
                'status' => 'tetap',
                'is_head_of_family' => true,
            ],
        ];

        foreach ($residentsData as $resident) {
            Resident::create($resident);
        }
    }
}
