# Digital Letter PDF Generation - Panduan Penggunaan

## Fitur yang Diimplementasikan

Sistem generate PDF untuk Surat Digital telah berhasil diimplementasikan dengan fitur-fitur berikut:

### 1. **Generate PDF**
- Admin RT dan Ketua RT dapat generate PDF untuk setiap surat digital
- PDF otomatis tersimpan di storage dengan path yang tercatat di database
- Button "Generate PDF" tersedia di tabel Digital Letter (hanya untuk Admin/Ketua)

### 2. **Preview PDF**
- Semua user dapat preview PDF surat digital yang berhak mereka lihat
- Preview dibuka di tab baru browser
- Button "Preview PDF" tersedia untuk semua surat

### 3. **Download PDF**
- Semua user dapat download PDF surat digital yang berhak mereka lihat
- File didownload dengan nama yang sesuai: `surat-{type}-{number}.pdf`
- Button "Download PDF" tersedia untuk semua surat

### 4. **Kontrol Akses**
- **Warga**: Hanya bisa preview/download surat digital mereka sendiri
- **Admin RT, Ketua RT, Bendahara**: Bisa preview/download semua surat digital

## Konfigurasi

Tambahkan konfigurasi berikut di file `.env` untuk informasi RT yang akan muncul di PDF:

```env
RT_NUMBER=001
RW_NUMBER=001
RT_ADDRESS="Jl. Contoh No. 123, Jakarta Selatan"
RT_PHONE=021-12345678
RT_CITY=Jakarta
```

## Template PDF

Template PDF mencakup:
- Header RT/RW dengan logo dan alamat
- Nomor surat dan informasi perihal
- Data lengkap warga (Nama, NIK, KK, Alamat, No. HP)
- Isi surat dari field `letter_content`
- Tanda tangan digital (jika ada file signature)
- Footer dengan tanggal terbit dan masa berlaku

## File-File yang Dibuat/Dimodifikasi

### File Baru:
1. `app/Services/DigitalLetterPdfService.php` - Service untuk handle generate PDF
2. `resources/views/pdf/digital-letter.blade.php` - Template PDF surat digital
3. `tests/Feature/DigitalLetterPdfTest.php` - Test untuk fitur PDF

### File yang Dimodifikasi:
1. `app/Filament/Resources/DigitalLetterResource.php` - Tambah action buttons untuk PDF
2. `routes/web.php` - Routes untuk preview dan download PDF
3. `config/app.php` - Konfigurasi informasi RT/RW
4. `.env.example` - Contoh konfigurasi RT/RW

### Package yang Diinstal:
- `barryvdh/laravel-dompdf` v3.1.1 - Library untuk generate PDF

## Cara Menggunakan

### Di Filament Admin Panel:

1. **Generate PDF Baru**:
   - Buka halaman Digital Letter
   - Klik tombol "Generate PDF" (⊕) pada baris surat yang diinginkan
   - Sistem akan generate dan menyimpan PDF
   - Notifikasi sukses akan muncul

2. **Preview PDF**:
   - Klik tombol "Preview PDF" (👁) pada baris surat
   - PDF akan terbuka di tab baru browser

3. **Download PDF**:
   - Klik tombol "Download PDF" (⬇) pada baris surat
   - File PDF akan terdownload otomatis

### Secara Programmatic:

```php
use App\Services\DigitalLetterPdfService;
use App\Models\DigitalLetter;

$pdfService = new DigitalLetterPdfService();
$letter = DigitalLetter::find(1);

// Generate dan simpan PDF
$pdfPath = $pdfService->generate($letter);

// Download PDF
return $pdfService->download($letter);

// Stream/Preview PDF
return $pdfService->stream($letter);

// Delete PDF
$pdfService->delete($letter);
```

## Testing

Semua fitur telah ditest dengan 7 test cases:

```bash
php artisan test --filter=DigitalLetterPdfTest
```

**Test Coverage:**
- ✅ Generate PDF dan simpan ke storage
- ✅ Generate filename yang benar
- ✅ Admin bisa preview PDF
- ✅ Admin bisa download PDF
- ✅ Warga bisa preview surat mereka sendiri
- ✅ Warga tidak bisa preview surat orang lain
- ✅ Delete PDF dari storage

## Storage

PDF disimpan di: `storage/app/public/letters/pdf/`

Pastikan storage link sudah dibuat:
```bash
php artisan storage:link
```

## Troubleshooting

### Font Issues
Jika ada masalah dengan font di PDF, edit `config/dompdf.php`:

```php
'font_dir' => storage_path('fonts/'),
'font_cache' => storage_path('fonts/'),
```

### Memory Issues
Jika generate PDF gagal karena memory limit, tingkatkan di `config/dompdf.php`:

```php
'enable_remote' => false,
'options' => [
    'isRemoteEnabled' => false,
    'isHtml5ParserEnabled' => true,
],
```

## Keamanan

- ✅ Authorization menggunakan Policy (ResidentPolicy & DigitalLetterPolicy)
- ✅ Warga hanya bisa akses PDF surat mereka sendiri
- ✅ Semua routes dilindungi dengan middleware `auth`
- ✅ File PDF tersimpan dengan nama yang secure

## Total Test Results

**22 tests berhasil dengan 37 assertions:**
- Digital Letter Access Control: 6 tests
- Digital Letter Management: 8 tests
- Digital Letter PDF Generation: 7 tests
- Digital Letter Model: 1 test

Status: ✅ **SEMUA FITUR BERFUNGSI DENGAN BAIK**
