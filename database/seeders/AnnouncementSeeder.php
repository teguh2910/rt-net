<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin_rt')->first() ?? User::where('role', 'ketua_rt')->first();

        if (! $admin) {
            return;
        }

        $announcements = [
            [
                'title' => 'Pengumuman Pengajian Rutin November',
                'content' => "Kepada seluruh warga RT 03 RW 05,\n\nDengan hormat kami ingin mengumumkan bahwa pengajian rutin akan diadakan pada:\n\n**Hari:** Malam Jumat\n**Tanggal:** 15 November 2024\n**Waktu:** Pukul 19.00 - 21.00 WIB\n**Tempat:** Rumah Bapak Bambang Sutrisno\n\nMohon kehadiran seluruh warga dan keluarganya. Hatur nuhun.",
                'published_at' => now()->subDays(5),
                'expires_at' => now()->addDays(7),
                'is_published' => true,
                'send_email' => true,
                'send_whatsapp' => true,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Jadwal Kerja Bakti Bersih-Bersih Lingkungan',
                'content' => "Assalamu'alaikum warga tercinta,\n\nKami mengajak seluruh warga untuk bersama-sama melakukan kerja bakti membersihkan lingkungan RT 03 RW 05:\n\n**Hari:** Minggu Pagi\n**Tanggal:** 17 November 2024\n**Waktu:** Pukul 07.00 - 10.00 WIB\n**Lokasi:** Area sekitar RT dan TPS\n\nMohon membawa alat-alat kebersihan (sapu, cangkul, ember, dll). Sediakan minuman ringan untuk peserta.",
                'published_at' => now()->subDays(3),
                'expires_at' => now()->addDays(5),
                'is_published' => true,
                'send_email' => false,
                'send_whatsapp' => true,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Pemberitahuan Perbaikan Jalan',
                'content' => "Pemberitahuan kepada warga,\n\nTerkait adanya kerusakan jalan di depan rumah Bapak Bambang Sutrisno, kami akan melakukan perbaikan segera.\n\n**Estimasi waktu:** 3-5 hari kerja\n\nMohon maklum atas ketidaknyamanan sementara ini. Untuk keperluan darurat, silakan menggunakan jalan alternatif.",
                'published_at' => now()->subDays(2),
                'expires_at' => now()->addDays(10),
                'is_published' => true,
                'send_email' => true,
                'send_whatsapp' => false,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
