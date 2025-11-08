<?php

namespace Database\Seeders;

use App\Models\Finance;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin_rt')->first() ?? User::where('role', 'bendahara')->first();

        if (! $admin) {
            return;
        }

        $finances = [
            // Pemasukan November
            [
                'type' => 'pemasukan',
                'category' => 'iuran',
                'description' => 'Iuran bulanan November - Bambang Sutrisno',
                'amount' => 50000,
                'transaction_date' => now()->subDays(10),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pemasukan',
                'category' => 'iuran',
                'description' => 'Iuran bulanan November - Ahmad Wijaya',
                'amount' => 50000,
                'transaction_date' => now()->subDays(9),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pemasukan',
                'category' => 'iuran',
                'description' => 'Iuran bulanan November - Rini Handayani',
                'amount' => 50000,
                'transaction_date' => now()->subDays(8),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pemasukan',
                'category' => 'donasi',
                'description' => 'Donasi untuk kegiatan bersih-bersih',
                'amount' => 200000,
                'transaction_date' => now()->subDays(7),
                'user_id' => $admin->id,
            ],

            // Pengeluaran November
            [
                'type' => 'pengeluaran',
                'category' => 'kebersihan',
                'description' => 'Biaya kebersihan jalan dan TPS',
                'amount' => 100000,
                'transaction_date' => now()->subDays(6),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pengeluaran',
                'category' => 'perbaikan',
                'description' => 'Perbaikan jalan retak di depan rumah Bambang',
                'amount' => 150000,
                'transaction_date' => now()->subDays(5),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pengeluaran',
                'category' => 'kegiatan',
                'description' => 'Konsumsi pengajian rutin November',
                'amount' => 75000,
                'transaction_date' => now()->subDays(4),
                'user_id' => $admin->id,
            ],
            [
                'type' => 'pengeluaran',
                'category' => 'lainnya',
                'description' => 'Biaya administrasi dan fotokopi dokumen',
                'amount' => 25000,
                'transaction_date' => now()->subDays(3),
                'user_id' => $admin->id,
            ],
        ];

        foreach ($finances as $finance) {
            Finance::create($finance);
        }
    }
}
