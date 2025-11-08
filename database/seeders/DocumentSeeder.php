<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin and ketua users for document uploads
        $adminUser = User::where('role', 'admin_rt')->first();
        $ketuaUser = User::where('role', 'ketua_rt')->first();

        if (! $ketuaUser) {
            $ketuaUser = $adminUser;
        }

        // 1. Notulen Rapat RT
        Document::create([
            'title' => 'Notulen Rapat RT Oktober 2025',
            'file_type' => 'notulen',
            'file_path' => 'documents/notulen-rapat-oktober-2025.pdf',
            'description' => 'Notulen rapat bulanan RT 03 yang dilaksanakan pada 15 Oktober 2025. '.
                'Membahas laporan keuangan, rencana kegiatan, dan iuran keamanan.',
            'document_date' => now()->subDays(20),
            'uploaded_by' => $ketuaUser->id,
            'created_at' => now()->subDays(20),
            'updated_at' => now()->subDays(20),
        ]);

        // 2. Laporan Kegiatan
        Document::create([
            'title' => 'Laporan Kegiatan HUT RI ke-80',
            'file_type' => 'laporan',
            'file_path' => 'documents/laporan-hutri-80.pdf',
            'description' => 'Laporan lengkap pelaksanaan kegiatan peringatan HUT RI ke-80 RT 03. '.
                'Termasuk susunan acara, daftar pemenang lomba, dan dokumentasi kegiatan.',
            'uploaded_by' => $adminUser->id,
            'document_date' => now()->subDays(20),
            'created_at' => now()->subMonths(3),
            'updated_at' => now()->subMonths(3),
        ]);

        // 3. Foto Kegiatan - Kerja Bakti
        Document::create([
            'title' => 'Dokumentasi Kerja Bakti September 2025',
            'file_type' => 'foto',
            'document_date' => now()->subDays(20),
            'file_path' => 'documents/foto-kerja-bakti-sept-2025.zip',
            'description' => 'Kumpulan foto kegiatan kerja bakti bersih-bersih lingkungan RT 03. '.
                'Berisi 25 foto kegiatan dari mulai pembersihan selokan hingga penataan taman.',
            'uploaded_by' => $adminUser->id,
            'created_at' => now()->subDays(45),
            'updated_at' => now()->subDays(45),
        ]);

        // 4. Surat Edaran
        Document::create([
            'title' => 'Surat Edaran Iuran Keamanan 2025',
            'file_type' => 'surat',
            'document_date' => now()->subDays(20),
            'file_path' => 'documents/surat-edaran-iuran-keamanan-2025.pdf',
            'description' => 'Surat edaran tentang kebijakan iuran keamanan RT 03 tahun 2025. '.
                'Nominal iuran Rp 50.000/bulan dibayarkan setiap awal bulan.',
            'uploaded_by' => $ketuaUser->id,
            'created_at' => now()->subMonths(1),
            'updated_at' => now()->subMonths(1),
        ]);

        // 5. Foto Kegiatan - Posyandu
        Document::create([
            'title' => 'Foto Posyandu Oktober 2025',
            'file_type' => 'foto',
            'document_date' => now()->subDays(20),
            'file_path' => 'documents/foto-posyandu-okt-2025.zip',
            'description' => 'Dokumentasi kegiatan Posyandu balita dan lansia bulan Oktober 2025. '.
                'Dihadiri 18 balita dan 12 lansia.',
            'uploaded_by' => $adminUser->id,
            'created_at' => now()->subDays(15),
            'updated_at' => now()->subDays(15),
        ]);

        // 6. Notulen Rapat Koordinasi
        Document::create([
            'title' => 'Notulen Rapat Koordinasi RW',
            'file_type' => 'notulen',
            'document_date' => now()->subDays(20),
            'file_path' => 'documents/notulen-rapat-koordinasi-rw.pdf',
            'description' => 'Notulen rapat koordinasi tingkat RW yang dihadiri oleh seluruh Ketua RT. '.
                'Membahas program kerja tingkat RW dan koordinasi kegiatan antar RT.',
            'uploaded_by' => $ketuaUser->id,
            'created_at' => now()->subDays(30),
            'updated_at' => now()->subDays(30),
        ]);

        $this->command->info('Documents seeded successfully!');
    }
}
