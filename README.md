# 🏘️ RT Net - Sistem Manajemen Komunitas RT/RW

**RT Net** adalah aplikasi web modern untuk membantu administrator RT/RW (lingkungan tetangga) mengelola data warga, keuangan, surat digital, dan pengumuman komunitas secara efisien.

## ✨ Fitur Utama

### 1. **Data Warga (Residents Management)**
- 📝 Kelola data warga lengkap (NIK, nama, alamat, kontak, status, foto)
- 📤 Import/export data dari Excel
- 🔍 Pencarian dan filter data
- 📊 Statistik penghuni

### 2. **Keuangan RT (Finance Management)**
- 💰 Pencatatan transaksi (pemasukan & pengeluaran)
- 📅 Filter berdasarkan periode
- 📊 Laporan keuangan bulanan dengan grafik
- 💹 Tracking saldo kas

### 3. **Surat Digital (Digital Letters)**
- 📄 Generate surat keterangan otomatis (domisili, pengantar, usaha, dll)
- 🖊️ Tanda tangan digital Ketua RT/RW
- 📜 Template surat yang dapat disesuaikan
- 🔗 Nomor surat otomatis

### 4. **Pengumuman & Agenda**
- 📢 CRUD pengumuman komunitas
- 📅 Manajemen acara/kegiatan
- 🔔 Notifikasi email & WhatsApp
- ⏰ Jadwal publikasi dan kadaluarsa

### 5. **Manajemen Dokumen**
- 📁 Upload dokumen (notulen, foto, laporan)
- 🏷️ Kategorisasi dokumen
- 📥 Download mudah

### 6. **User Management & Roles**
- 👥 Multi-role: Admin RT, Ketua RT, Bendahara, Warga
- 🔐 Login berbasis role
- 📋 Kontrol akses per fitur

### 7. **Dashboard Ringkasan**
- 📊 Statistik total warga & keuangan
- 📈 Grafik trend keuangan
- 🎯 Acara mendatang
- 🔔 Pengumuman terbaru

## 🛠️ Tech Stack

- **Backend:** Laravel 12
- **Admin Panel:** Filament 3
- **Frontend:** Livewire, AlpineJS, TailwindCSS
- **Database:** MySQL/SQLite
- **PDF:** DomPDF

## 📦 Instalasi Cepat

```bash
# Clone & setup
git clone <repo-url> && cd rt-net
composer install
npm install

# Konfigurasi
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed
npm run build

# Run
php artisan serve
```

Akses: `http://localhost:8000/admin`

## 🔑 Kredensial Default

| Role | Email | Password |
|------|-------|----------|
| Admin RT | admin@rtnet.local | password |
| Ketua RT | ketua@rtnet.local | password |
| Bendahara | bendahara@rtnet.local | password |
| Warga | warga@rtnet.local | password |

⚠️ **Ubah password di production!**

## 📚 Dokumentasi

- [IMPLEMENTATION_PLAN.md](./IMPLEMENTATION_PLAN.md) - Rencana teknis lengkap
- [Laravel 12](https://laravel.com/docs/12.x)
- [Filament 3](https://filamentadmin.com/docs)

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan fork dan buat pull request.

## 📄 Lisensi

MIT License - lihat file [LICENSE](LICENSE)
