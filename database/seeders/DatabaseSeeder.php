<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users with different roles
        User::create([
            'name' => 'Admin RT',
            'email' => 'admin@rtnet.local',
            'password' => Hash::make('password'),
            'role' => 'admin_rt',
            'is_active' => true,
            'address' => 'Jl. Merdeka No. 1, RT 03 RW 05',
        ]);

        User::create([
            'name' => 'Ketua RT',
            'email' => 'ketua@rtnet.local',
            'password' => Hash::make('password'),
            'role' => 'ketua_rt',
            'is_active' => true,
            'address' => 'Jl. Gatot Subroto No. 5, RT 03 RW 05',
        ]);

        User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@rtnet.local',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'is_active' => true,
            'address' => 'Jl. Gatot Subroto No. 7, RT 03 RW 05',
        ]);

        User::create([
            'name' => 'Warga Demo',
            'email' => 'warga@rtnet.local',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'is_active' => true,
            'address' => 'Jl. Merdeka No. 3, RT 03 RW 05',
        ]);

        // Run additional seeders
        $this->call([
            ResidentSeeder::class,
            FinanceSeeder::class,
            FinancialReportSeeder::class,
            AnnouncementSeeder::class,
            DigitalLetterSeeder::class,
            EventSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
