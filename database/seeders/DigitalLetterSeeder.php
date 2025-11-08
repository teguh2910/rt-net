<?php

namespace Database\Seeders;

use App\Models\DigitalLetter;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;

class DigitalLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get ketua_rt user (authorized to issue letters)
        $ketuaUser = User::where('role', 'ketua_rt')->first();

        if (! $ketuaUser) {
            $ketuaUser = User::where('role', 'admin_rt')->first();
        }

        // Get some residents for letters
        $residents = Resident::limit(3)->get();

        if ($residents->isEmpty()) {
            $this->command->warn('No residents found. Run ResidentSeeder first.');

            return;
        }

        // 1. Surat Keterangan Domisili
        DigitalLetter::create([
            'letter_type' => 'domisili',
            'letter_number' => '001/SKD/RT03/'.now()->format('m/Y'),
            'resident_id' => $residents[0]->id,
            'letter_content' => "Yang bertanda tangan di bawah ini, Ketua RT 03 RW 05 Kelurahan Sukamaju, menerangkan bahwa:\n\n".
                "Nama: {$residents[0]->name}\n".
                "NIK: {$residents[0]->nik}\n".
                "Alamat: {$residents[0]->address}\n\n".
                'Adalah benar penduduk RT 03 RW 05 dan berdomisili di alamat tersebut di atas sejak tahun 2018. '.
                'Surat keterangan ini dibuat untuk keperluan administrasi.',
            'issued_date' => now()->subDays(5),
            'valid_until' => now()->addMonths(3),
            'issued_by' => $ketuaUser->id,
        ]);

        // 2. Surat Pengantar
        if (isset($residents[1])) {
            DigitalLetter::create([
                'letter_type' => 'pengantar',
                'letter_number' => '002/SP/RT03/'.now()->format('m/Y'),
                'resident_id' => $residents[1]->id,
                'letter_content' => "Yang bertanda tangan di bawah ini, Ketua RT 03 RW 05 Kelurahan Sukamaju, dengan ini menerangkan bahwa:\n\n".
                    "Nama: {$residents[1]->name}\n".
                    "NIK: {$residents[1]->nik}\n".
                    "Alamat: {$residents[1]->address}\n\n".
                    'Adalah benar warga kami yang bermaksud untuk mengurus pembuatan KTP. '.
                    'Demikian surat pengantar ini dibuat untuk dipergunakan sebagaimana mestinya.',
                'issued_date' => now()->subDays(3),
                'valid_until' => now()->addMonth(),
                'issued_by' => $ketuaUser->id,
            ]);
        }

        // 3. Surat Keterangan Usaha
        if (isset($residents[2])) {
            DigitalLetter::create([
                'letter_type' => 'usaha',
                'letter_number' => '003/SKU/RT03/'.now()->format('m/Y'),
                'resident_id' => $residents[2]->id,
                'letter_content' => "Yang bertanda tangan di bawah ini, Ketua RT 03 RW 05 Kelurahan Sukamaju, menerangkan bahwa:\n\n".
                    "Nama: {$residents[2]->name}\n".
                    "NIK: {$residents[2]->nik}\n".
                    "Alamat: {$residents[2]->address}\n".
                    "Jenis Usaha: Warung Sembako\n\n".
                    'Adalah benar warga kami yang menjalankan usaha warung sembako di alamat tersebut di atas sejak tahun 2020. '.
                    'Usaha tersebut dijalankan dengan baik dan tidak mengganggu ketertiban lingkungan.',
                'issued_date' => now()->subWeek(),
                'valid_until' => now()->addMonths(6),
                'issued_by' => $ketuaUser->id,
            ]);
        }

        $this->command->info('Digital letters seeded successfully!');
    }
}
