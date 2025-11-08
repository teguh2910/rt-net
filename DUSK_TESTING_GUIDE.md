# Laravel Dusk Testing Guide - RT-Net Application

## Overview
Comprehensive browser testing suite untuk aplikasi RT-Net menggunakan Laravel Dusk v8.3.3.

## Test Coverage

### 1. LoginTest.php (6 tests)
- ✅ Login sebagai Admin RT
- ✅ Login sebagai Ketua RT
- ✅ Login sebagai Bendahara
- ✅ Login sebagai Warga
- ✅ User tidak aktif tidak bisa login
- ✅ User dapat logout

### 2. SimplifiedTests.php (30+ tests) - CRUD untuk semua resource

#### Finance Management (4 tests)
- ✅ Admin dapat melihat daftar transaksi
- ✅ Admin dapat akses halaman create transaksi
- ✅ Admin dapat akses halaman edit transaksi
- ✅ Bendahara dapat akses halaman transaksi

#### Resident Management (4 tests)
- ✅ Admin dapat melihat daftar warga
- ✅ Admin dapat akses halaman create warga
- ✅ Admin dapat akses halaman edit warga
- ✅ Warga dapat melihat daftar warga

#### Announcement Management (4 tests)
- ✅ Admin dapat melihat daftar pengumuman
- ✅ Admin dapat akses halaman create pengumuman
- ✅ Admin dapat akses halaman edit pengumuman
- ✅ Ketua RT dapat akses pengumuman

#### Event Management (4 tests)
- ✅ Admin dapat melihat daftar acara
- ✅ Admin dapat akses halaman create acara
- ✅ Admin dapat akses halaman edit acara
- ✅ Ketua RT dapat akses acara

#### Document Management (3 tests)
- ✅ Admin dapat melihat daftar dokumen
- ✅ Admin dapat akses halaman create dokumen
- ✅ Warga dapat melihat dokumen

#### Digital Letter Management (4 tests)
- ✅ Admin dapat melihat daftar surat digital
- ✅ Admin dapat akses halaman create surat digital
- ✅ Admin dapat akses halaman edit surat digital
- ✅ Ketua RT dapat akses surat digital

#### Financial Report Management (4 tests)
- ✅ Admin dapat melihat laporan keuangan
- ✅ Admin dapat akses halaman create laporan
- ✅ Admin dapat akses halaman view laporan
- ✅ Bendahara dapat akses laporan keuangan

#### User Management (4 tests)
- ✅ Admin dapat melihat daftar users
- ✅ Admin dapat akses halaman create user
- ✅ Admin dapat akses halaman edit user
- ✅ Warga tidak dapat akses halaman users

## Prerequisites

1. **ChromeDriver Installation**
   ```bash
   php artisan dusk:chrome-driver
   ```

2. **Database Configuration**
   - Pastikan database test sudah dikonfigurasi di `.env.dusk.local`
   - Atau buat file `.env.dusk.local` dengan koneksi database terpisah

## Running Tests

### Menjalankan Semua Browser Tests
```bash
php artisan dusk
```

### Menjalankan Test File Tertentu
```bash
# Login tests (6 tests)
php artisan dusk tests/Browser/LoginTest.php

# Simplified CRUD tests (30+ tests)
php artisan dusk tests/Browser/SimplifiedTests.php
```

### Menjalankan Test Method Tertentu
```bash
php artisan dusk --filter=test_admin_can_view_finances
```

### Menjalankan dengan Browser Visible (untuk debugging)
Edit `tests/DuskTestCase.php` dan uncomment baris berikut:
```php
// ->headless()
```

## Troubleshooting

### Browser tidak terbuka
```bash
# Update ChromeDriver
php artisan dusk:chrome-driver --detect

# Atau install versi spesifik
php artisan dusk:chrome-driver 131
```

### Test gagal karena timeout
Tambahkan `->pause(1000)` setelah action untuk menunggu respons:
```php
$browser->press('Create')
        ->pause(1000)
        ->assertSee('Success');
```

### Screenshot untuk debugging
Dusk otomatis mengambil screenshot saat test gagal di:
```
tests/Browser/screenshots/
```

### Console Logs
Untuk debugging, gunakan:
```php
$browser->dump(); // Dump DOM
$browser->screenshot('debug'); // Ambil screenshot manual
```

## Best Practices

1. **Database Migration**
   - Semua test menggunakan `DatabaseMigrations` trait
   - Database akan di-refresh setiap kali test dijalankan

2. **User Factory**
   - Selalu gunakan factory untuk membuat user test
   - Jangan hardcode data user

3. **Waiting**
   - Gunakan `waitForText()` daripada `pause()` jika memungkinkan
   - Gunakan `waitForLocation()` untuk navigasi

4. **Selectors**
   - Prefer menggunakan text-based selectors: `clickLink('New')`
   - Gunakan CSS selectors jika diperlukan: `click('button[title="Edit"]')`

5. **Assertions**
   - Selalu assert bahwa action berhasil
   - Gunakan `assertSee()`, `assertDontSee()`, `assertPathIs()`

## Test Strategy

### Simplified Approach
Test menggunakan pendekatan sederhana yang fokus pada:
1. **Route Access** - Verifikasi user dapat mengakses halaman yang sesuai dengan role
2. **Path Assertions** - Memastikan routing bekerja dengan benar
3. **Role-Based Access** - Verifikasi authorization policy diterapkan

### Why Not Full Form Interaction?
- Filament 4.x menggunakan Livewire components dengan selector yang dinamis
- Form validation sudah di-cover oleh Feature tests
- Browser tests fokus pada E2E routing dan authorization
- Lebih stable dan cepat dibanding full UI automation

## Test Statistics

- **Total Test Files**: 2 (LoginTest, SimplifiedTests)
- **Total Test Methods**: 36+ tests
- **Coverage Areas**: 
  - Authentication & Authorization (6 tests)
  - Finance Management (4 tests)
  - Resident Management (4 tests)
  - Announcement Management (4 tests)
  - Event Management (4 tests)
  - Document Management (3 tests)
  - Digital Letter Management (4 tests)
  - Financial Report Management (4 tests)
  - User Management (4 tests)

## Notes

- Tests menggunakan Filament 4.1.10 selectors
- Semua tests mendukung role-based access control (RBAC)
- Database di-reset setiap test run (DatabaseMigrations)
- Screenshots tersimpan otomatis saat test gagal
