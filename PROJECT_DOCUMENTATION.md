# Project Documentation: NeoLedger

> Sistem Manajemen Arus Kas (Cashflow Management System) untuk Perusahaan

---

## Daftar Isi

1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Tech Stack](#2-tech-stack)
3. [Alur Bisnis](#3-alur-bisnis)
4. [Fitur-Fitur Utama](#4-fitur-fitur-utama)
5. [Skema Database (ERD)](#5-skema-database-erd)
6. [API Routes & Endpoints](#6-api-routes--endpoints)
7. [Struktur Folder Proyek](#7-struktur-folder-proyek)
8. [UI/UX Halaman Utama](#8-uiux-halaman-utama)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [Deployment Architecture](#10-deployment-architecture)

---

## 1. Ringkasan Proyek

**NeoLedger** adalah aplikasi web untuk mencatat, memantau, dan menganalisis arus kas perusahaan secara real-time. Aplikasi ini dirancang untuk memberikan transparansi keuangan melalui fitur pencatatan transaksi, sistem approval berjenjang, budgeting, dan laporan analitik visual.

**Target Pengguna:**
- Admin Finance — input dan kelola transaksi harian
- Manager / Approver — review dan approve transaksi di atas threshold
- Viewer — melihat laporan dan dashboard (read-only)
- Super Admin — kelola user, role, dan konfigurasi sistem

---

## 2. Tech Stack

| Layer        | Teknologi                          |
|--------------|-------------------------------------|
| **Backend**  | Laravel 12 (PHP 8.3+)              |
| **Frontend** | Blade Templates + TailwindCSS v4   |
| **Database** | MySQL 8.0+ / PostgreSQL 15+        |
| **Auth**     | Laravel Breeze (Blade stack)        |
| **Charts**   | Chart.js / ApexCharts               |
| **PDF/Export**| DomPDF / Laravel Excel (Maatwebsite)|
| **File Storage** | Laravel Storage (local/S3)      |
| **Queue**    | Laravel Queue (database/redis)      |
| **Scheduler**| Laravel Task Scheduling (cron)      |
| **Testing**  | PHPUnit + Laravel Dusk (browser)    |

### Development Tools
- **Package Manager:** Composer (PHP), NPM (JS/CSS)
- **Build Tool:** Vite (bundled with Laravel 12)
- **Code Style:** Laravel Pint
- **Version Control:** Git

---

## 3. Alur Bisnis (Business Flow)

### A. Alur Pemasukan & Pengeluaran

```
[User Input Transaksi]
        │
        ▼
[Validasi Sistem]──────────── Gagal ──► [Notifikasi Error]
        │
        ▼ (Valid)
[Cek Threshold Amount]
        │
   ┌────┴────┐
   ▼         ▼
[Di bawah]  [Di atas threshold]
   │              │
   ▼              ▼
[Auto-Approve]  [Kirim ke Approver]
   │              │
   │         ┌────┴────┐
   │         ▼         ▼
   │    [Approved]  [Rejected]
   │         │         │
   │         ▼         ▼
   │    [Posting]  [Notif ke User + Bisa Re-submit]
   │         │
   └────┬────┘
        ▼
[Update Saldo Akun]
        │
        ▼
[Catat di General Ledger]
        │
        ▼
[Update Budget (jika expense)]
        │
        ▼
[Tulis Audit Log]
```

**Detail Setiap Tahap:**

1. **Input Transaksi:** User mengisi form dengan field wajib: nominal, kategori, akun (Bank/Kas), tanggal, deskripsi, dan upload bukti (invoice/struk).
2. **Validasi Sistem:**
   - Cek saldo cukup (untuk pengeluaran)
   - Cek duplikasi referensi
   - Validasi format file attachment (jpg, png, pdf, max 5MB)
3. **Threshold Check:** Sistem membandingkan nominal dengan `approval_settings.threshold_amount`.
4. **Approval Flow:**
   - Transaksi **di bawah** threshold → status langsung `approved`
   - Transaksi **di atas** threshold → status `pending`, notifikasi dikirim ke user dengan role `approver`
5. **Rejection Flow:** Approver bisa menolak dengan catatan alasan. User asal bisa mengedit dan re-submit.
6. **Posting:** Setelah approved, saldo akun diperbarui dan dicatat di general ledger.

### B. Alur Anggaran (Budgeting)

```
[Admin Set Budget]
        │
        ▼
[Budget Aktif untuk Periode]
        │
        ▼
[Transaksi Expense Masuk] ──► [Kurangi Sisa Budget]
        │
   ┌────┴────────┐
   ▼              ▼
[≥ 80%]       [= 100%]
   │              │
   ▼              ▼
[Warning Alert] [Critical Alert + Block Optional]
```

1. **Perencanaan:** Admin/Manager menetapkan plafon anggaran per kategori per periode (misal: "Infrastruktur Server" = Rp 50.000.000 untuk Februari 2026).
2. **Monitoring:** Setiap transaksi expense otomatis mengurangi kuota budget terkait.
3. **Alerting:**
   - **80%** tercapai → Notifikasi warning (kuning)
   - **100%** tercapai → Notifikasi critical (merah), opsional: blokir transaksi baru di kategori tersebut

### C. Alur Transfer Antar Akun

```
[User Pilih Akun Asal & Tujuan]
        │
        ▼
[Input Nominal Transfer]
        │
        ▼
[Validasi Saldo Akun Asal]
        │
        ▼
[Buat 2 Record Transaksi]
  ├─ Expense dari Akun Asal
  └─ Income ke Akun Tujuan
        │
        ▼
[Update Saldo Kedua Akun]
```

### D. Alur Recurring Transactions

```
[Admin Setup Recurring]
        │
        ▼
[Scheduler Cek Harian]
        │
        ▼
[next_run_date = today?]
   │           │
   Yes         No → Skip
   │
   ▼
[Auto-Create Transaksi]
        │
        ▼
[Update next_run_date]
```

1. **Setup:** Admin mendefinisikan transaksi berulang (misal: Gaji Bulanan, Langganan AWS).
2. **Eksekusi:** Laravel Scheduler menjalankan job harian untuk mengecek recurring yang perlu diproses.
3. **Auto-create:** Transaksi dibuat otomatis, mengikuti alur approval yang sama.

### E. Alur User Management

1. **Super Admin** membuat akun user baru melalui halaman User Management.
2. **Assign Role:** Setiap user diberi satu role (Viewer, Editor, Approver, Admin).
3. **Permissions:** Setiap role memiliki set permission yang mengontrol akses ke fitur tertentu.

---

## 4. Fitur-Fitur Utama (Key Features)

### Core Management
| Fitur | Deskripsi |
|-------|-----------|
| Multi-Account Tracking | Kelola banyak rekening bank, e-wallet, dan kas kecil dalam satu dashboard |
| Transaction CRUD | Input, edit, hapus transaksi dengan validasi dan bukti pendukung |
| Automated Categorization | Pengelompokan otomatis berdasarkan tag/vendor |
| Recurring Transactions | Pencatatan otomatis transaksi rutin (gaji, langganan SaaS) |
| Inter-Account Transfer | Transfer dana antar akun dengan pencatatan otomatis |
| Approval Workflow | Sistem persetujuan berjenjang berdasarkan threshold nominal |

### Analytics & Reporting
| Fitur | Deskripsi |
|-------|-----------|
| Dashboard Overview | Ringkasan saldo, income vs expense, trend chart |
| Cashflow Forecasting | Prediksi arus kas berdasarkan data historis dan recurring |
| Monthly Summary | Laporan visual (Pie/Bar chart) — Burn Rate vs Revenue |
| Budget vs Actual | Perbandingan realisasi vs anggaran per kategori |
| Tax Ready Export | Ekspor ke CSV/Excel sesuai format pelaporan pajak |
| PDF Report | Generate laporan PDF untuk periode tertentu |

### Security & Enterprise Grade
| Fitur | Deskripsi |
|-------|-----------|
| Role-Based Access Control | 4 level: Viewer, Editor, Approver, Admin |
| Audit Trail | Log lengkap: siapa, kapan, dan perubahan apa |
| Encrypted Attachment | Enkripsi file bukti transaksi |
| Two-Factor Auth (Optional) | Keamanan tambahan untuk akun Admin/Approver |

### Notifications
| Fitur | Deskripsi |
|-------|-----------|
| In-App Notification | Notifikasi dalam aplikasi (bell icon) |
| Email Notification | Email otomatis untuk approval request dan budget alert |
| Real-time Badge | Badge counter untuk notifikasi yang belum dibaca |

---

## 5. Skema Database (ERD)

### Diagram Relasi

```
users ──────┐
  │         │
  │    audit_logs
  │
  ├── transactions ──── accounts
  │       │
  │       ├── categories ── budgets
  │       │
  │       └── transaction_attachments
  │
  ├── notifications
  │
  └── recurring_transactions ──── accounts
                │
                └── categories

roles ──── users
approval_settings ──── roles
```

### Tabel-Tabel

#### 1. `roles`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `name` | VARCHAR(50) | viewer, editor, approver, admin |
| `permissions` | JSON | Daftar permission, misal: `["transactions.create","transactions.approve"]` |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 2. `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `name` | VARCHAR(255) | |
| `email` | VARCHAR(255), UNIQUE | |
| `password` | VARCHAR(255), HASHED | |
| `role_id` | FK → `roles.id` | |
| `is_active` | BOOLEAN, DEFAULT true | Soft disable akun |
| `email_verified_at` | TIMESTAMP, NULLABLE | |
| `remember_token` | VARCHAR(100), NULLABLE | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 3. `accounts` (Bank/Cash Accounts)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `account_name` | VARCHAR(255) | Contoh: "Bank Mandiri PT Neo", "Petty Cash" |
| `account_type` | ENUM: `cash`, `bank`, `e-wallet`, `credit` | |
| `account_number` | VARCHAR(50), NULLABLE | Nomor rekening (opsional) |
| `current_balance` | DECIMAL(15,2), DEFAULT 0 | |
| `is_active` | BOOLEAN, DEFAULT true | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 4. `categories`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `name` | VARCHAR(255) | |
| `type` | ENUM: `income`, `expense` | |
| `icon` | VARCHAR(50), NULLABLE | Nama icon untuk UI |
| `color` | VARCHAR(7), NULLABLE | Hex color untuk chart, misal: `#4F46E5` |
| `is_active` | BOOLEAN, DEFAULT true | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 5. `transactions`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `user_id` | FK → `users.id` | User yang input |
| `account_id` | FK → `accounts.id` | |
| `category_id` | FK → `categories.id` | |
| `type` | ENUM: `income`, `expense`, `transfer` | Tipe transaksi eksplisit |
| `amount` | DECIMAL(15,2) | |
| `transaction_date` | DATE | |
| `description` | TEXT, NULLABLE | |
| `reference_number` | VARCHAR(100), NULLABLE, UNIQUE | Nomor referensi unik |
| `status` | ENUM: `pending`, `approved`, `rejected` | DEFAULT `pending` |
| `approved_by` | FK → `users.id`, NULLABLE | User yang approve |
| `approved_at` | TIMESTAMP, NULLABLE | |
| `rejection_note` | TEXT, NULLABLE | Alasan penolakan |
| `related_transaction_id` | FK → `transactions.id`, NULLABLE | Untuk transaksi transfer (pasangan) |
| `recurring_transaction_id` | FK → `recurring_transactions.id`, NULLABLE | Jika dibuat dari recurring |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 6. `transaction_attachments`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `transaction_id` | FK → `transactions.id`, ON DELETE CASCADE | |
| `file_path` | VARCHAR(500) | Path di storage |
| `file_name` | VARCHAR(255) | Nama file asli |
| `file_type` | VARCHAR(50) | MIME type |
| `file_size` | INT | Ukuran dalam bytes |
| `created_at` | TIMESTAMP | |

#### 7. `budgets`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `category_id` | FK → `categories.id` | |
| `limit_amount` | DECIMAL(15,2) | Plafon anggaran |
| `spent_amount` | DECIMAL(15,2), DEFAULT 0 | Total yang sudah terpakai |
| `period` | VARCHAR(7) | Format: "2026-02" (YYYY-MM) |
| `alert_sent_80` | BOOLEAN, DEFAULT false | Flag: alert 80% sudah dikirim |
| `alert_sent_100` | BOOLEAN, DEFAULT false | Flag: alert 100% sudah dikirim |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| | | **UNIQUE constraint:** `category_id` + `period` |

#### 8. `recurring_transactions`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `user_id` | FK → `users.id` | User yang setup |
| `account_id` | FK → `accounts.id` | |
| `category_id` | FK → `categories.id` | |
| `type` | ENUM: `income`, `expense` | |
| `amount` | DECIMAL(15,2) | |
| `description` | TEXT, NULLABLE | |
| `frequency` | ENUM: `daily`, `weekly`, `monthly`, `yearly` | |
| `start_date` | DATE | Tanggal mulai |
| `next_run_date` | DATE | Tanggal eksekusi berikutnya |
| `end_date` | DATE, NULLABLE | Tanggal berhenti (opsional) |
| `is_active` | BOOLEAN, DEFAULT true | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 9. `notifications`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `user_id` | FK → `users.id` | Penerima notifikasi |
| `type` | VARCHAR(50) | `approval_request`, `approval_result`, `budget_warning`, `budget_critical`, `recurring_created` |
| `title` | VARCHAR(255) | |
| `message` | TEXT | |
| `is_read` | BOOLEAN, DEFAULT false | |
| `reference_type` | VARCHAR(50), NULLABLE | Polymorphic: `transaction`, `budget` |
| `reference_id` | BIGINT, NULLABLE | ID dari tabel terkait |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 10. `approval_settings`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `transaction_type` | ENUM: `income`, `expense`, `transfer` | |
| `threshold_amount` | DECIMAL(15,2) | Nominal batas auto-approve |
| `approver_role_id` | FK → `roles.id` | Role yang berhak approve |
| `is_active` | BOOLEAN, DEFAULT true | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

#### 11. `audit_logs`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | PK, BIGINT, AUTO_INCREMENT | |
| `user_id` | FK → `users.id` | User yang melakukan aksi |
| `action` | VARCHAR(50) | `created`, `updated`, `deleted`, `approved`, `rejected` |
| `auditable_type` | VARCHAR(100) | Model class name (polymorphic) |
| `auditable_id` | BIGINT | ID dari record yang berubah |
| `old_values` | JSON, NULLABLE | Nilai sebelum perubahan |
| `new_values` | JSON, NULLABLE | Nilai setelah perubahan |
| `ip_address` | VARCHAR(45), NULLABLE | |
| `user_agent` | TEXT, NULLABLE | |
| `created_at` | TIMESTAMP | |

---

## 6. API Routes & Endpoints

> Semua route menggunakan **web middleware** (Blade-based, bukan API-only). Grouped by prefix.

### Auth Routes (Laravel Breeze)
| Method | URI | Controller | Keterangan |
|--------|-----|------------|------------|
| GET | `/login` | `AuthenticatedSessionController@create` | Halaman login |
| POST | `/login` | `AuthenticatedSessionController@store` | Proses login |
| POST | `/logout` | `AuthenticatedSessionController@destroy` | Logout |
| GET | `/forgot-password` | `PasswordResetLinkController@create` | Lupa password |
| POST | `/forgot-password` | `PasswordResetLinkController@store` | Kirim link reset |

### Dashboard
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/dashboard` | `DashboardController@index` | all | Dashboard utama |
| GET | `/dashboard/cashflow-data` | `DashboardController@cashflowData` | all | Data chart (AJAX) |

### Transactions
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/transactions` | `TransactionController@index` | all | List transaksi + filter |
| GET | `/transactions/create` | `TransactionController@create` | transactions.create | Form input |
| POST | `/transactions` | `TransactionController@store` | transactions.create | Simpan transaksi |
| GET | `/transactions/{id}` | `TransactionController@show` | all | Detail transaksi |
| GET | `/transactions/{id}/edit` | `TransactionController@edit` | transactions.edit | Form edit |
| PUT | `/transactions/{id}` | `TransactionController@update` | transactions.edit | Update |
| DELETE | `/transactions/{id}` | `TransactionController@destroy` | transactions.delete | Hapus |
| POST | `/transactions/{id}/approve` | `TransactionController@approve` | transactions.approve | Approve |
| POST | `/transactions/{id}/reject` | `TransactionController@reject` | transactions.approve | Reject |

### Transfers
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/transfers/create` | `TransferController@create` | transactions.create | Form transfer |
| POST | `/transfers` | `TransferController@store` | transactions.create | Proses transfer |

### Accounts
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/accounts` | `AccountController@index` | all | List akun |
| GET | `/accounts/create` | `AccountController@create` | accounts.manage | Form tambah |
| POST | `/accounts` | `AccountController@store` | accounts.manage | Simpan |
| GET | `/accounts/{id}/edit` | `AccountController@edit` | accounts.manage | Form edit |
| PUT | `/accounts/{id}` | `AccountController@update` | accounts.manage | Update |
| DELETE | `/accounts/{id}` | `AccountController@destroy` | accounts.manage | Hapus |

### Categories
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/categories` | `CategoryController@index` | all | List kategori |
| POST | `/categories` | `CategoryController@store` | categories.manage | Simpan |
| PUT | `/categories/{id}` | `CategoryController@update` | categories.manage | Update |
| DELETE | `/categories/{id}` | `CategoryController@destroy` | categories.manage | Hapus |

### Budgets
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/budgets` | `BudgetController@index` | budgets.view | List budget |
| POST | `/budgets` | `BudgetController@store` | budgets.manage | Simpan |
| PUT | `/budgets/{id}` | `BudgetController@update` | budgets.manage | Update |
| DELETE | `/budgets/{id}` | `BudgetController@destroy` | budgets.manage | Hapus |

### Recurring Transactions
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/recurring` | `RecurringController@index` | transactions.create | List recurring |
| GET | `/recurring/create` | `RecurringController@create` | transactions.create | Form tambah |
| POST | `/recurring` | `RecurringController@store` | transactions.create | Simpan |
| PUT | `/recurring/{id}` | `RecurringController@update` | transactions.create | Update |
| DELETE | `/recurring/{id}` | `RecurringController@destroy` | transactions.create | Hapus |

### Reports
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/reports` | `ReportController@index` | reports.view | Halaman laporan |
| GET | `/reports/monthly` | `ReportController@monthly` | reports.view | Laporan bulanan |
| GET | `/reports/budget-vs-actual` | `ReportController@budgetVsActual` | reports.view | Budget vs Realisasi |
| GET | `/reports/forecast` | `ReportController@forecast` | reports.view | Cashflow forecasting |
| GET | `/reports/export/{type}` | `ReportController@export` | reports.export | Export CSV/Excel/PDF |

### Notifications
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/notifications` | `NotificationController@index` | all | List notifikasi |
| POST | `/notifications/{id}/read` | `NotificationController@markAsRead` | all | Tandai sudah dibaca |
| POST | `/notifications/read-all` | `NotificationController@markAllAsRead` | all | Tandai semua dibaca |

### User Management (Admin Only)
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/users` | `UserController@index` | users.manage | List user |
| GET | `/users/create` | `UserController@create` | users.manage | Form tambah |
| POST | `/users` | `UserController@store` | users.manage | Simpan |
| GET | `/users/{id}/edit` | `UserController@edit` | users.manage | Form edit |
| PUT | `/users/{id}` | `UserController@update` | users.manage | Update |
| DELETE | `/users/{id}` | `UserController@destroy` | users.manage | Hapus (soft) |

### Settings (Admin Only)
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/settings` | `SettingController@index` | settings.manage | Halaman pengaturan |
| PUT | `/settings/approval` | `SettingController@updateApproval` | settings.manage | Update threshold |

### Audit Logs (Admin Only)
| Method | URI | Controller | Permission | Keterangan |
|--------|-----|------------|------------|------------|
| GET | `/audit-logs` | `AuditLogController@index` | audit.view | List audit trail |
| GET | `/audit-logs/{id}` | `AuditLogController@show` | audit.view | Detail perubahan |

---

## 7. Struktur Folder Proyek

```
neoledger/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── TransferController.php
│   │   │   ├── AccountController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── BudgetController.php
│   │   │   ├── RecurringController.php
│   │   │   ├── ReportController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── UserController.php
│   │   │   ├── SettingController.php
│   │   │   └── AuditLogController.php
│   │   ├── Middleware/
│   │   │   ├── CheckPermission.php
│   │   │   └── LogActivity.php
│   │   └── Requests/
│   │       ├── TransactionRequest.php
│   │       ├── TransferRequest.php
│   │       ├── AccountRequest.php
│   │       ├── BudgetRequest.php
│   │       └── UserRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Account.php
│   │   ├── Category.php
│   │   ├── Transaction.php
│   │   ├── TransactionAttachment.php
│   │   ├── Budget.php
│   │   ├── RecurringTransaction.php
│   │   ├── Notification.php
│   │   ├── ApprovalSetting.php
│   │   └── AuditLog.php
│   ├── Services/
│   │   ├── TransactionService.php       # Business logic transaksi
│   │   ├── ApprovalService.php          # Logic approval workflow
│   │   ├── BudgetService.php            # Budget tracking & alerting
│   │   ├── ForecastService.php          # Cashflow forecasting
│   │   ├── NotificationService.php      # Kirim notifikasi
│   │   └── ReportService.php            # Generate laporan
│   ├── Jobs/
│   │   ├── ProcessRecurringTransactions.php
│   │   ├── SendBudgetAlert.php
│   │   └── SendApprovalNotification.php
│   ├── Observers/
│   │   └── TransactionObserver.php      # Auto audit log
│   └── Exports/
│       ├── TransactionsExport.php
│       └── MonthlyReportExport.php
├── database/
│   ├── migrations/
│   │   ├── 0001_create_roles_table.php
│   │   ├── 0002_create_users_table.php
│   │   ├── 0003_create_accounts_table.php
│   │   ├── 0004_create_categories_table.php
│   │   ├── 0005_create_transactions_table.php
│   │   ├── 0006_create_transaction_attachments_table.php
│   │   ├── 0007_create_budgets_table.php
│   │   ├── 0008_create_recurring_transactions_table.php
│   │   ├── 0009_create_notifications_table.php
│   │   ├── 0010_create_approval_settings_table.php
│   │   └── 0011_create_audit_logs_table.php
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── ApprovalSettingSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php            # Layout utama (sidebar + topbar)
│       │   └── guest.blade.php          # Layout login/register
│       ├── components/
│       │   ├── sidebar.blade.php
│       │   ├── topbar.blade.php
│       │   ├── notification-bell.blade.php
│       │   ├── stat-card.blade.php
│       │   ├── chart-card.blade.php
│       │   ├── modal.blade.php
│       │   └── badge.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── transactions/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── show.blade.php
│       │   └── edit.blade.php
│       ├── transfers/
│       │   └── create.blade.php
│       ├── accounts/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── categories/
│       │   └── index.blade.php
│       ├── budgets/
│       │   └── index.blade.php
│       ├── recurring/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── reports/
│       │   ├── index.blade.php
│       │   ├── monthly.blade.php
│       │   ├── budget-vs-actual.blade.php
│       │   └── forecast.blade.php
│       ├── notifications/
│       │   └── index.blade.php
│       ├── users/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── settings/
│       │   └── index.blade.php
│       └── audit-logs/
│           ├── index.blade.php
│           └── show.blade.php
└── routes/
    └── web.php
```

---

## 8. UI/UX Halaman Utama

### Prinsip Desain
- **Dark/Light Mode** — toggle di topbar
- **Color Palette** — Indigo (#4F46E5) sebagai primary, Emerald (#10B981) untuk income, Rose (#F43F5E) untuk expense
- **Typography** — Inter (Google Fonts)
- **Layout** — Sidebar fixed kiri (collapsible) + content area kanan
- **Responsive** — Mobile-first, sidebar berubah jadi hamburger menu di mobile

### Halaman-Halaman

#### 8.1 Login Page
- Card form di tengah layar dengan gradient background
- Fields: Email, Password, Remember Me
- Link: Forgot Password

#### 8.2 Dashboard
- **Top Row:** 4 stat cards
  - Total Saldo (semua akun)
  - Income bulan ini
  - Expense bulan ini
  - Net Cashflow bulan ini
- **Middle Row:**
  - Line chart: Cashflow trend 6 bulan terakhir (income vs expense)
  - Donut chart: Expense breakdown by category
- **Bottom Row:**
  - Tabel: 10 transaksi terakhir (dengan status badge)
  - Card: Top 3 budget yang hampir limit

#### 8.3 Transactions List
- Filter bar: Tanggal range, Kategori, Akun, Status, Tipe (income/expense)
- Search: keyword di description
- Tabel: Date, Description, Category, Account, Amount (warna hijau/merah), Status badge, Actions
- Pagination
- Tombol: + New Transaction, Export

#### 8.4 Transaction Form (Create/Edit)
- Select: Account
- Select: Category (filtered by type)
- Radio: Income / Expense
- Input: Amount (format Rupiah)
- Datepicker: Transaction Date
- Textarea: Description
- File Upload: Bukti transaksi (drag & drop, multi-file)
- Button: Submit, Cancel

#### 8.5 Transaction Detail
- Semua info transaksi
- Preview attachment (image/PDF)
- Timeline: Created → Pending → Approved/Rejected (dengan nama user dan timestamp)
- Action buttons (jika pending): Approve, Reject (dengan catatan)

#### 8.6 Accounts Page
- Card grid: Setiap akun sebagai card dengan nama, tipe, dan saldo
- Modal form: Tambah/Edit akun

#### 8.7 Budget Page
- Tabel + progress bar per kategori per bulan
- Kolom: Kategori, Limit, Terpakai, Sisa, % (warna berubah sesuai level)
- Modal form: Set/Edit budget

#### 8.8 Reports Page
- Tab navigation: Monthly Summary, Budget vs Actual, Forecast
- Masing-masing tab memiliki chart + tabel data
- Tombol Export (CSV/Excel/PDF)

#### 8.9 Notifications Page
- List notifikasi dengan icon tipe, judul, waktu
- Unread styling (bold / background berbeda)
- Click untuk navigate ke resource terkait

#### 8.10 User Management (Admin)
- Tabel: Nama, Email, Role, Status, Actions
- Modal form: Tambah/Edit user

#### 8.11 Audit Logs (Admin)
- Tabel: Timestamp, User, Action, Table, Detail
- Click untuk expand: old_values vs new_values (diff view)
- Filter: User, Action, Date range

#### 8.12 Settings (Admin)
- Section: Approval Thresholds
  - Per tipe transaksi: threshold amount + approver role
- Section: General Settings (company name, timezone, currency)

---

## 9. Non-Functional Requirements

### Performance
| Metric | Target |
|--------|--------|
| Page Load Time | < 2 detik (first contentful paint) |
| Dashboard Data | < 1 detik (AJAX load) |
| Concurrent Users | Minimal 50 user simultan |
| Database Queries | Max 15 queries per page (eager loading) |

### Security
- CSRF protection pada semua form (Laravel built-in)
- XSS protection via Blade auto-escaping
- SQL Injection prevention via Eloquent ORM
- Rate limiting pada login endpoint (5 attempts / menit)
- File upload validation: tipe (jpg, png, pdf), ukuran (max 5MB)
- Session timeout: 120 menit inactivity
- HTTPS enforced di production

### Reliability
- Database backup otomatis harian
- Queue retry mechanism (3 attempts) untuk failed jobs
- Error logging via Laravel Log (daily rotation)

### Scalability
- Stateless application (session di database/redis)
- Queue-based processing untuk heavy tasks (report generation, email)
- Database indexing pada kolom yang sering di-query: `transaction_date`, `status`, `account_id`, `category_id`

---

## 10. Deployment Architecture

### Development Environment
```
Local Machine
├── PHP 8.3+ (XAMPP / Herd / Docker)
├── MySQL 8.0+
├── Node.js 20+ (untuk Vite + TailwindCSS)
├── Composer
└── Git
```

### Production Environment (VPS / Cloud)
```
┌─────────────────────────────────┐
│           Nginx                 │
│     (Reverse Proxy + SSL)       │
├─────────────────────────────────┤
│       Laravel Application       │
│    (PHP-FPM 8.3 + OPcache)     │
├───────────┬─────────────────────┤
│  MySQL    │  Redis (optional)   │
│  8.0+     │  Queue + Cache      │
├───────────┴─────────────────────┤
│     Laravel Scheduler (Cron)    │
│     Laravel Queue Worker        │
└─────────────────────────────────┘
```

### Deployment Steps
1. **Server Setup:** Install PHP 8.3, MySQL, Nginx, Composer, Node.js
2. **Clone Repository:** `git clone` ke server
3. **Install Dependencies:** `composer install --optimize-autoloader --no-dev`
4. **Build Assets:** `npm install && npm run build`
5. **Environment:** Copy dan konfigurasi `.env` (DB, Mail, Storage)
6. **Database:** `php artisan migrate --seed`
7. **Optimize:** `php artisan config:cache && php artisan route:cache && php artisan view:cache`
8. **Scheduler:** Tambahkan cron: `* * * * * php /path/artisan schedule:run >> /dev/null 2>&1`
9. **Queue Worker:** Jalankan via Supervisor: `php artisan queue:work`
10. **SSL:** Setup Let's Encrypt via Certbot

### CI/CD (Opsional)
- GitHub Actions untuk automated testing + deployment
- Auto-deploy ke production setelah push ke branch `main`

---

## Appendix: Permission Matrix

| Permission | Viewer | Editor | Approver | Admin |
|------------|--------|--------|----------|-------|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| View Transactions | ✅ | ✅ | ✅ | ✅ |
| Create/Edit Transactions | ❌ | ✅ | ✅ | ✅ |
| Delete Transactions | ❌ | ❌ | ❌ | ✅ |
| Approve/Reject Transactions | ❌ | ❌ | ✅ | ✅ |
| View Reports | ✅ | ✅ | ✅ | ✅ |
| Export Reports | ❌ | ✅ | ✅ | ✅ |
| Manage Accounts | ❌ | ❌ | ❌ | ✅ |
| Manage Categories | ❌ | ❌ | ❌ | ✅ |
| Manage Budgets | ❌ | ❌ | ✅ | ✅ |
| Manage Users | ❌ | ❌ | ❌ | ✅ |
| View Audit Logs | ❌ | ❌ | ❌ | ✅ |
| Manage Settings | ❌ | ❌ | ❌ | ✅ |
