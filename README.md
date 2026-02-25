# 📊 NeoLedger — Cashflow Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
</p>

---

## 📖 Tentang NeoLedger

**NeoLedger** adalah sistem manajemen arus kas (cashflow) berbasis web yang dirancang untuk membantu bisnis dalam mengelola keuangan secara akurat, transparan, dan efisien. Sistem ini menyediakan pencatatan transaksi pemasukan dan pengeluaran, pengelolaan anggaran, hingga pelaporan keuangan dalam satu platform terpadu.

NeoLedger dibangun dengan pendekatan **multi-user** dan **role-based access control (RBAC)**, sehingga setiap pengguna memiliki hak akses yang sesuai dengan perannya — mulai dari **Admin** yang memiliki kontrol penuh, **Manager** yang bertanggung jawab atas persetujuan transaksi, hingga **Staff** yang bertugas menginput data harian.

---

## 🎯 Latar Belakang

Pengelolaan arus kas merupakan aspek krusial dalam operasional bisnis. Tanpa sistem yang baik, risiko kebocoran dana, pencatatan ganda, dan ketidakakuratan laporan keuangan menjadi tinggi. NeoLedger hadir sebagai solusi untuk:

- **Menghilangkan pencatatan manual** yang rawan kesalahan
- **Menyediakan approval workflow** untuk memastikan setiap transaksi terverifikasi
- **Menghasilkan laporan real-time** yang membantu pengambilan keputusan
- **Menjaga jejak audit** untuk keperluan transparansi dan akuntabilitas

---

## ✨ Fitur Utama

### 💰 Manajemen Transaksi
Pencatatan pemasukan dan pengeluaran dengan dukungan **approval workflow** bertingkat. Setiap transaksi melewati proses verifikasi (pending → approved/rejected) untuk menjaga keakuratan data keuangan. Dilengkapi fitur lampiran dokumen pendukung.

### 🔄 Transfer Antar Akun
Pemindahan dana antar akun bank atau kas tercatat otomatis di kedua sisi — akun pengirim dan penerima — sehingga saldo selalu sinkron tanpa perlu input manual berulang.

### 🏦 Manajemen Akun
Pengelolaan berbagai jenis akun keuangan (bank, kas, e-wallet) dengan pemantauan saldo real-time. Setiap mutasi tercatat dan dapat ditelusuri.

### 📊 Anggaran & Realisasi
Penetapan budget per kategori per bulan. Sistem secara otomatis melacak realisasi pengeluaran dan memberikan peringatan ketika anggaran mendekati atau melampaui batas.

### 🔁 Transaksi Berulang
Penjadwalan transaksi otomatis untuk pembayaran rutin seperti gaji, sewa, atau tagihan bulanan. Mendukung frekuensi harian, mingguan, bulanan, dan tahunan.

### 📈 Laporan Keuangan
Laporan komprehensif per periode yang menampilkan total pemasukan, pengeluaran, dan saldo bersih. Dilengkapi breakdown per kategori dengan visualisasi progress bar dan persentase.

### 📥 Export Data
Export data ke format **Excel (XLSX)**, **CSV**, dan **PDF** dengan template profesional berbranding NeoLedger. Mendukung export dengan filter aktif — hanya data yang ditampilkan yang akan diekspor.

### 👥 Manajemen Pengguna & Hak Akses
Sistem RBAC dengan tiga level peran:
- **Admin** — Akses penuh ke seluruh fitur dan konfigurasi sistem
- **Manager** — Approval transaksi dan akses laporan
- **Staff** — Input transaksi dan akses terbatas

### 📝 Audit Log
Setiap aktivitas tercatat secara otomatis — siapa melakukan apa, kapan, dari IP mana, dan menggunakan perangkat apa. Mendukung kebutuhan compliance dan investigasi.

### 🔔 Notifikasi
Pemberitahuan otomatis untuk transaksi yang membutuhkan persetujuan, perubahan status, dan aktivitas penting lainnya.

### 🌙 Dark Mode
Tampilan mode gelap yang dirancang secara konsisten di seluruh halaman, memberikan kenyamanan visual saat bekerja dalam waktu lama.

---

## 🛠️ Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade Templates, TailwindCSS, Alpine.js |
| Database | MySQL 8.0 |
| Ekspor | Maatwebsite Excel, Barryvdh DomPDF |
| Autentikasi | Laravel Breeze |
| Email | SMTP (dukungan Gmail, cPanel, dll) |

---

## � Arsitektur Sistem

NeoLedger mengikuti arsitektur **MVC (Model-View-Controller)** yang merupakan standar Laravel, dengan penambahan beberapa layer:

- **Models** dengan trait `Auditable` untuk pencatatan otomatis ke audit log
- **Export Classes** berbasis Maatwebsite untuk generate file spreadsheet
- **Blade Components** yang reusable untuk konsistensi UI (export button, modal, dsb.)
- **PDF Templates** dengan styling inline untuk kompatibilitas email client

---

## 🔒 Keamanan

- Autentikasi dengan hashing bcrypt
- Password reset via email dengan token berumur terbatas
- RBAC middleware pada setiap route
- Audit trail otomatis untuk setiap perubahan data
- CSRF protection pada seluruh form
- Input validation di controller dan form request

---

<p align="center">
  Dikembangkan oleh <strong>NeoOne</strong> · 2026
</p>
