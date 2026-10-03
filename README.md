# DISARDA CIMAHI
## Sistem Informasi Dinas Arsip dan Perpustakaan Kota Cimahi

Aplikasi web berbasis PHP untuk manajemen arsip dengan fitur lengkap.

---

## Fitur Utama

- **Login System** - Autentikasi pengguna dengan session management
- **Dashboard** - Statistik arsip dan ringkasan data
- **Manajemen Arsip** - CRUD arsip dengan berbagai atribut
- **Manajemen Pegawai** - Data pegawai pengelola arsip
- **Manajemen Pengguna** - Data pengguna internal/eksternal
- **Master Data** - Jenis Arsip, Klasifikasi, Lokasi Penyimpanan
- **User Management** - Kelola akun dengan role (Admin, Operator, Viewer)
- **Roles & Hak Akses** - Pengaturan hak akses
- **Audit Log** - Riwayat perubahan data
- **Laporan** - Laporan arsip dengan filter

---

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3+
- Apache/Nginx Web Server
- XAMPP/WAMP/LAMP (untuk development lokal)

---

## Cara Instalasi

### 1. Download & Extract
Download folder `disarda_cimahi` dan extract ke folder htdocs (XAMPP) atau www (WAMP).

### 2. Buat Database
1. Buka phpMyAdmin
2. Buat database baru dengan nama `disarda_cimahi`
3. Import file `sql/database.sql`

### 3. Konfigurasi Database
Edit file `config/database.php` sesuai konfigurasi server Anda:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Password MySQL Anda
define('DB_NAME', 'disarda_cimahi');
```

### 4. Akses Aplikasi
Buka browser dan akses: `http://localhost/disarda_cimahi`

---

## Login Default

| Username | Password | Role |
|----------|----------|------|
| admin | password | Admin |
| operator | password | Operator |

**Catatan:** Password default adalah `password`. Segera ubah setelah login pertama.

---

## Struktur Folder

```
disarda_cimahi/
├── assets/
│   ├── css/
│   │   └── style.css       # Stylesheet utama
│   ├── js/
│   │   └── main.js         # JavaScript utama
│   └── images/             # Folder gambar
├── config/
│   ├── config.php          # Konfigurasi aplikasi
│   └── database.php        # Konfigurasi database
├── includes/
│   ├── header.php          # Header template
│   ├── sidebar.php         # Sidebar navigasi
│   ├── topbar.php          # Top bar header
│   └── footer.php          # Footer template
├── pages/                  # (opsional untuk halaman tambahan)
├── sql/
│   └── database.sql        # Schema database
├── index.php               # Dashboard
├── login.php               # Halaman login
├── logout.php              # Proses logout
├── arsip.php               # Manajemen arsip
├── pegawai.php             # Manajemen pegawai
├── pengguna.php            # Manajemen pengguna
├── jenis_arsip.php         # Master jenis arsip
├── klasifikasi.php         # Master klasifikasi
├── penyimpanan.php         # Master lokasi penyimpanan
├── users.php               # Manajemen user (Admin)
├── roles.php               # Manajemen roles (Admin)
├── audit_log.php           # Audit log (Admin)
├── laporan.php             # Laporan arsip
├── profile.php             # Profil pengguna
└── README.md               # Dokumentasi ini
```

---

## Tabel Database

1. **Users** - Data user untuk login
2. **Pegawai** - Data pegawai
3. **Pengguna** - Data pengguna arsip
4. **Jenis_Arsip** - Master jenis arsip
5. **Klasifikasi_Arsip** - Master klasifikasi
6. **Penyimpanan** - Master lokasi penyimpanan
7. **Arsip** - Data arsip utama
8. **Roles** - Master roles
9. **Proses_Pengarsipan** - Proses pengarsipan
10. **Pengguna_Arsip** - Relasi pengguna-arsip
11. **Pengguna_Roles** - Relasi pengguna-roles
12. **Audit_Log** - Log perubahan data

---

## Hak Akses Role

| Fitur | Admin | Operator | Viewer |
|-------|-------|----------|--------|
| Dashboard | ✓ | ✓ | ✓ |
| Lihat Data | ✓ | ✓ | ✓ |
| Tambah/Edit | ✓ | ✓ | ✗ |
| Hapus | ✓ | ✗ | ✗ |
| User Management | ✓ | ✗ | ✗ |
| Audit Log | ✓ | ✗ | ✗ |

---

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap 5, Font Awesome 6
- **JavaScript**: jQuery, DataTables

---

## Lisensi

Dibuat untuk DISARDA CIMAHI - Dinas Arsip dan Perpustakaan Kota Cimahi

---

## Kontak

Untuk pertanyaan dan dukungan, silakan hubungi tim pengembang.
