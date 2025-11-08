# RT Net - Implementation Plan

## 📋 Step-by-Step Implementation Guide

### Phase 1: Database & Models (Migrations)

#### 1.1 Residents Table (`Data Warga`)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_residents_table.php
Schema::create('residents', function (Blueprint $table) {
    $table->id();
    $table->string('nik')->unique(); // Nomor Induk Keluarga
    $table->string('name');
    $table->string('no_kk')->nullable(); // Nomor Kartu Keluarga
    $table->string('address');
    $table->string('phone_number');
    $table->enum('status', ['tetap', 'kontrak']); // Tetap/Kontrak
    $table->boolean('is_head_of_family')->default(false); // Kepala Keluarga
    $table->string('photo_path')->nullable();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamps();
});
```

#### 1.2 Finances Table (`Keuangan RT`)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_finances_table.php
Schema::create('finances', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['pemasukan', 'pengeluaran']);
    $table->enum('category', ['iuran', 'donasi', 'kegiatan', 'perbaikan', 'kebersihan', 'lainnya']);
    $table->string('description');
    $table->decimal('amount', 12, 2);
    $table->date('transaction_date');
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.3 Financial Reports Table
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_financial_reports_table.php
Schema::create('financial_reports', function (Blueprint $table) {
    $table->id();
    $table->integer('month');
    $table->integer('year');
    $table->decimal('opening_balance', 12, 2);
    $table->decimal('total_income', 12, 2);
    $table->decimal('total_expense', 12, 2);
    $table->decimal('closing_balance', 12, 2);
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.4 Digital Letters Table (`Surat Digital`)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_digital_letters_table.php
Schema::create('digital_letters', function (Blueprint $table) {
    $table->id();
    $table->string('letter_type'); // domisili, pengantar, usaha, dll
    $table->string('letter_number')->unique();
    $table->foreignId('resident_id')->constrained()->cascadeOnDelete();
    $table->text('letter_content');
    $table->string('signature_path')->nullable();
    $table->date('issued_date');
    $table->date('valid_until')->nullable();
    $table->string('pdf_path')->nullable();
    $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.5 Announcements & Events Table (`Pengumuman & Agenda`)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_announcements_table.php
Schema::create('announcements', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->dateTime('published_at');
    $table->dateTime('expires_at')->nullable();
    $table->boolean('is_published')->default(true);
    $table->boolean('send_whatsapp')->default(false);
    $table->boolean('send_email')->default(false);
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.6 Events Table
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_events_table.php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->dateTime('event_date');
    $table->string('location')->nullable();
    $table->string('organizer')->nullable();
    $table->boolean('send_notification')->default(true);
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.7 Documents Table (`Upload dokumen`)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_documents_table.php
Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('file_path');
    $table->string('file_type'); // notulen, foto, laporan, etc
    $table->date('document_date');
    $table->text('description')->nullable();
    $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

#### 1.8 Enhanced Users Table (Add roles)
```php
// database/migrations/xxxx_xx_xx_xxxxxx_modify_users_table.php
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin_rt', 'ketua_rt', 'bendahara', 'warga'])->default('warga');
    $table->boolean('is_active')->default(true);
    $table->string('address')->nullable();
});
```

---

### Phase 2: Eloquent Models

#### 2.1 Models to Create:
- `Resident` - with relationships to User
- `Finance` - with relationships to User
- `FinancialReport` - monthly reports
- `DigitalLetter` - generated PDFs
- `Announcement` - notifications
- `Event` - calendar events
- `Document` - file storage

#### 2.2 Model Relationships:
- User hasMany Residents, Finances, Announcements, Events, Documents
- Resident hasMany DigitalLetters
- FinancialReport belongsTo User (created_by)

---

### Phase 3: Filament Resources

#### 3.1 Admin Resources:
- `UserResource` - manage roles & permissions
- `ResidentResource` - CRUD + bulk import/export
- `FinanceResource` - income/expense tracking
- `FinancialReportResource` - monthly reports with analytics
- `DigitalLetterResource` - letter generation & management
- `AnnouncementResource` - CRUD with notification options
- `EventResource` - calendar management
- `DocumentResource` - file management

#### 3.2 Dashboard Widgets:
- Total Residents card
- Monthly Income/Expense chart
- Cash Balance card
- Upcoming Events card
- Outstanding Payments statistics

---

### Phase 4: Features Implementation

#### 4.1 Import/Export Excel
- Use `maatwebsite/excel` package
- Bulk resident data import
- Financial report export

#### 4.2 PDF Generation
- Use `dompdf/dompdf` or `barryvdh/laravel-dompdf`
- Dynamic letter templates with resident data
- RT/RW signature embedding

#### 4.3 Authentication & Authorization
- Multi-role login in Filament
- Policy-based resource access
- Role-based menu visibility

#### 4.4 Notifications (Optional)
- WhatsApp integration (WAHA/Fonnte)
- Email notifications for announcements

---

### Phase 5: Frontend & Testing

#### 5.1 Seeder Data
- Sample residents
- Sample finances
- Sample announcements

#### 5.2 Tests
- Unit tests for models
- Feature tests for resources

---

## 📁 Directory Structure After Implementation

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── ResidentResource.php
│   │   ├── FinanceResource.php
│   │   ├── FinancialReportResource.php
│   │   ├── DigitalLetterResource.php
│   │   ├── AnnouncementResource.php
│   │   ├── EventResource.php
│   │   ├── DocumentResource.php
│   │   └── UserResource.php
│   └── Widgets/
│       ├── StatsOverviewWidget.php
│       ├── CashBalanceChart.php
│       └── UpcomingEventsWidget.php
├── Models/
│   ├── Resident.php
│   ├── Finance.php
│   ├── FinancialReport.php
│   ├── DigitalLetter.php
│   ├── Announcement.php
│   ├── Event.php
│   └── Document.php
└── Http/
    └── Controllers/ (optional API)

database/
├── migrations/
│   ├── create_residents_table.php
│   ├── create_finances_table.php
│   ├── create_financial_reports_table.php
│   ├── create_digital_letters_table.php
│   ├── create_announcements_table.php
│   ├── create_events_table.php
│   ├── create_documents_table.php
│   └── modify_users_table.php
└── seeders/
    ├── ResidentSeeder.php
    ├── FinanceSeeder.php
    ├── AnnouncementSeeder.php
    └── DatabaseSeeder.php

resources/
├── views/
│   ├── filament/
│   │   └── (Filament auto-generates)
│   └── templates/
│       └── letters/ (letter PDF templates)
└── css/
    └── app.css

public/
└── img/
    └── sign/
        └── rt/ (RT/RW signatures)
```

---

## 🚀 Installation Commands (Order)

```bash
# 1. Install Filament
composer require filament/filament

# 2. Run Filament setup
php artisan filament:install --panels

# 3. Create migrations
php artisan make:migration create_residents_table
php artisan make:migration create_finances_table
# ... (continue for all tables)

# 4. Create models with factories
php artisan make:model Resident -mf
php artisan make:model Finance -mf
# ... (continue for all models)

# 5. Create Filament resources
php artisan make:filament-resource Resident
php artisan make:filament-resource Finance
# ... (continue for all resources)

# 6. Run migrations
php artisan migrate

# 7. Create seeders
php artisan make:seeder ResidentSeeder
php artisan make:seeder FinanceSeeder
# ... (continue for all seeders)

# 8. Run seeders
php artisan db:seed

# 9. Create admin user (via Filament command)
php artisan make:filament-user
```

---

## 📝 Key Features Checklist

- [ ] Filament admin panel installed
- [ ] All migrations created
- [ ] All models created with relationships
- [ ] All Filament resources created
- [ ] User roles and permissions configured
- [ ] Excel import/export working
- [ ] PDF letter generation working
- [ ] Dashboard widgets displaying data
- [ ] Sample seeders populated
- [ ] Tests passing
- [ ] Documentation complete

---

## 💾 Database Schema Summary

| Table | Columns | Purpose |
|-------|---------|---------|
| residents | id, nik, name, no_kk, address, phone, status, photo, user_id | Resident data storage |
| finances | id, type, category, amount, date, user_id | Income/expense tracking |
| financial_reports | id, month, year, balance, income, expense | Monthly financial summary |
| digital_letters | id, type, letter_number, resident_id, content, pdf_path | Generated letter PDFs |
| announcements | id, title, content, published_at, send_whatsapp | Community announcements |
| events | id, name, description, event_date, location | Calendar events |
| documents | id, title, file_path, type, description, uploaded_by | File storage |
| users | id, name, email, role, address, is_active | User accounts with roles |

