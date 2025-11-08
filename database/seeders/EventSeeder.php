<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get ketua_rt user for event creation
        $ketuaUser = User::where('role', 'ketua_rt')->first();

        if (! $ketuaUser) {
            $ketuaUser = User::where('role', 'admin_rt')->first();
        }

        // Get admin user for some events
        $adminUser = User::where('role', 'admin_rt')->first();

        // 1. Upcoming: Rapat RT Bulanan
        Event::create([
            'name' => 'Rapat RT Bulanan November 2025',
            'description' => "**Agenda Rapat:**\n\n".
                "1. Laporan keuangan bulan Oktober\n".
                "2. Rencana kegiatan bulan Desember\n".
                "3. Pembahasan iuran keamanan\n".
                "4. Lain-lain\n\n".
                '**Catatan:** Diharapkan kehadiran seluruh warga. Yang berhalangan hadir mohon konfirmasi ke Ketua RT.',
            'event_date' => now()->addDays(10)->setTime(19, 0),
            'location' => 'Balai RT 03 RW 05',
            'organizer' => 'Pengurus RT 03',
            'send_notification' => true,
            'created_by' => $ketuaUser->id,
        ]);

        // 2. Upcoming: Kerja Bakti
        Event::create([
            'name' => 'Kerja Bakti Bersih-Bersih Lingkungan',
            'description' => "**Kegiatan:**\n\n".
                "- Pembersihan selokan\n".
                "- Pengecatan pagar RT\n".
                "- Penataan taman\n\n".
                "**Perlengkapan yang dibawa:**\n".
                "- Sapu lidi\n".
                "- Cangkul\n".
                "- Sarung tangan\n\n".
                'Akan disediakan konsumsi dan snack untuk seluruh peserta.',
            'event_date' => now()->addDays(5)->setTime(7, 0),
            'location' => 'Depan Balai RT, berkumpul pukul 07.00',
            'organizer' => 'Karang Taruna RT 03',
            'send_notification' => true,
            'created_by' => $adminUser->id,
        ]);

        // 3. Past Event: Peringatan 17 Agustus
        Event::create([
            'name' => 'Peringatan HUT RI ke-80',
            'description' => "**Rangkaian Acara:**\n\n".
                "- 06.00 - Upacara Bendera\n".
                "- 09.00 - Lomba anak-anak (balap karung, makan kerupuk, tarik tambang)\n".
                "- 13.00 - Lomba dewasa (voli, futsal)\n".
                "- 16.00 - Pengumuman pemenang dan pembagian hadiah\n\n".
                'Terima kasih atas partisipasi seluruh warga!',
            'event_date' => now()->subMonths(3)->setTime(6, 0),
            'location' => 'Lapangan RT 03 RW 05',
            'organizer' => 'Panitia HUT RI RT 03',
            'send_notification' => false,
            'created_by' => $ketuaUser->id,
        ]);

        // 4. Upcoming: Pengajian Rutin
        Event::create([
            'name' => 'Pengajian Rutin Ibu-Ibu',
            'description' => "**Pengajian Bulanan:**\n\n".
                "Pengajian rutin ibu-ibu RT 03 dengan ustadzah Dr. Siti Maryam.\n\n".
                "**Tema:** Akhlak dalam Keluarga\n\n".
                'Diharapkan kehadiran ibu-ibu sekalian. Akan ada arisan setelah pengajian.',
            'event_date' => now()->addWeeks(2)->setTime(13, 0),
            'location' => 'Rumah Ibu Ketua RT',
            'organizer' => 'Ibu-ibu PKK RT 03',
            'send_notification' => true,
            'created_by' => $ketuaUser->id,
        ]);

        // 5. Upcoming: Posyandu
        Event::create([
            'name' => 'Posyandu Balita & Lansia',
            'description' => "**Layanan Posyandu:**\n\n".
                "- Penimbangan bayi & balita\n".
                "- Imunisasi\n".
                "- Pemberian vitamin\n".
                "- Pemeriksaan kesehatan lansia\n".
                "- Cek tensi darah gratis\n\n".
                'Bawa buku KIA/KMS untuk balita.',
            'event_date' => now()->addDays(15)->setTime(8, 0),
            'location' => 'Balai RT 03 RW 05',
            'organizer' => 'Kader Posyandu RT 03',
            'send_notification' => true,
            'created_by' => $adminUser->id,
        ]);

        $this->command->info('Events seeded successfully!');
    }
}
