<?php

namespace Database\Seeders;

use App\Models\Finance;
use App\Models\FinancialReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class FinancialReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get bendahara user for report creation
        $bendaharaUser = User::where('role', 'bendahara')->first();

        if (! $bendaharaUser) {
            $bendaharaUser = User::where('role', 'admin_rt')->first();
        }

        if (! $bendaharaUser) {
            $this->command->warn('No bendahara or admin_rt user found. Skipping FinancialReportSeeder.');

            return;
        }

        // Check if there are finance records
        if (Finance::count() === 0) {
            $this->command->warn('No finance records found. Run FinanceSeeder first.');

            return;
        }

        // Report 1: Current Month (November 2025)
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $currentIncome = Finance::where('type', 'pemasukan')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $currentExpense = Finance::where('type', 'pengeluaran')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        $openingBalance = 5000000; // Saldo awal November
        $closingBalance = $openingBalance + $currentIncome - $currentExpense;

        FinancialReport::create([
            'month' => now()->format('m'),
            'year' => now()->format('Y'),
            'opening_balance' => $openingBalance,
            'total_income' => $currentIncome,
            'total_expense' => $currentExpense,
            'closing_balance' => $closingBalance,
            'notes' => "Laporan keuangan RT 03 bulan November 2025:\n\n".
                '- Iuran bulanan terkumpul: Rp '.number_format($currentIncome - 200000, 0, ',', '.')."\n".
                "- Donasi kegiatan: Rp 200.000\n".
                "- Pengeluaran terbesar: perbaikan jalan\n".
                '- Saldo kas meningkat dari bulan sebelumnya',
            'created_by' => $bendaharaUser->id,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Report 2: Last Month (October 2025)
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        FinancialReport::create([
            'month' => now()->subMonth()->format('m'),
            'year' => now()->subMonth()->format('Y'),
            'opening_balance' => 4500000,
            'total_income' => 700000,
            'total_expense' => 200000,
            'closing_balance' => 5000000, // This becomes opening balance for November
            'notes' => "Laporan keuangan RT 03 bulan Oktober 2025:\n\n".
                "- Iuran bulanan: 14 warga x Rp 50.000 = Rp 700.000\n".
                "- Pengeluaran rutin: kebersihan dan administrasi\n".
                "- Surplus bulan ini: Rp 500.000\n".
                '- Kondisi keuangan: SEHAT',
            'created_by' => $bendaharaUser->id,
            'created_at' => now()->subMonth()->endOfMonth(),
            'updated_at' => now()->subMonth()->endOfMonth(),
        ]);

        // Report 3: Two Months Ago (September 2025)
        FinancialReport::create([
            'month' => now()->subMonths(2)->format('m'),
            'year' => now()->subMonths(2)->format('Y'),
            'opening_balance' => 4200000,
            'total_income' => 850000,
            'total_expense' => 550000,
            'closing_balance' => 4500000,
            'notes' => "Laporan keuangan RT 03 bulan September 2025:\n\n".
                "- Iuran bulanan: Rp 650.000\n".
                "- Donasi kegiatan HUT RI: Rp 200.000\n".
                "- Pengeluaran: lomba 17 Agustus dan hadiah\n".
                '- Kegiatan berjalan lancar dengan surplus Rp 300.000',
            'created_by' => $bendaharaUser->id,
            'created_at' => now()->subMonths(2)->endOfMonth(),
            'updated_at' => now()->subMonths(2)->endOfMonth(),
        ]);

        $this->command->info('Financial reports seeded successfully!');
    }
}
