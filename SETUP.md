# SMP Muhammadiyah Tahfidz Salatiga - Sistem Database

## 📋 Panduan Setup dan Instalasi

Selamat datang! Dokumentasi ini akan memandu Anda untuk mengkonfigurasi sistem database MySQL dan Admin Panel untuk website SMP Muhammadiyah.

---

## 🚀 LANGKAH-LANGKAH SETUP

### 1. **Install Database MySQL**

```bash
# Buka phpMyAdmin atau MySQL Command Line
# Copy-paste seluruh kode dari file: database.sql

# Atau gunakan command line:
mysql -u root -p < database.sql
```

**Akun Database Default:**
- Host: `localhost`
- User: `root`
- Password: (kosong)
- Database: `smp_muhammadiyah`

Jika Anda menggunakan password, ubah di file `includes/config.php`

---

### 2. **Konfigurasi File**

Edit file `includes/config.php`:
```php
define('DB_HOST', 'localhost');     // Host database
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', '');              // Password MySQL
define('DB_NAME', 'smp_muhammadiyah'); // Nama database
define('SITE_URL', 'http://localhost/website/'); // URL website
```

---

### 3. **Password Admin Default**

Setelah database dibuat, akun admin sudah tersedia:

| Field | Value |
|-------|-------|
| Username | admin |
| Password | admin123 |

⚠️ **PENTING:** Ubah password admin setelah login pertama kali!

---

## 🌐 MENGAKSES SISTEM

### Admin Panel
- **URL:** `http://localhost/website/admin/login.php`
- **Username:** admin
- **Password:** admin123

### Website Publik
- **Beranda:** `http://localhost/website/index.php`
- **Berita:** `http://localhost/website/berita.php`
- **Akademik:** `http://localhost/website/akademik.php`
- **Galeri:** `http://localhost/website/galeri.php`
- **Kontak:** `http://localhost/website/kontak.php`
- **SPMB:** `http://localhost/website/spmb.php`

---

## 📁 STRUKTUR FOLDER

```
website/
├── index.php                 # Halaman beranda (PHP)
├── berita.php               # Halaman berita (PHP)
├── artikel.php              # Halaman detail artikel (PHP)
├── akademik.php             # Halaman akademik (PHP)
├── galeri.php               # Halaman galeri (PHP)
├── kontak.php               # Halaman kontak (PHP)
├── spmb.php                 # Halaman SPMB (PHP)
├── database.sql             # File database SQL
│
├── admin/                   # Folder admin panel
│   ├── login.php           # Halaman login admin
│   ├── dashboard.php       # Dashboard admin
│   ├── articles.php        # Kelola berita
│   ├── categories.php      # Kelola kategori
│   ├── gallery.php         # Kelola galeri
│   ├── achievements.php    # Kelola prestasi
│   ├── extracurricular.php # Kelola ekstrakurikuler
│   ├── comments.php        # Kelola komentar
│   ├── messages.php        # Kelola pesan kontak
│   └── logout.php          # Logout
│
├── api/                    # Folder API
│   ├── data.php           # API untuk fetch data
│   └── contact.php        # API untuk form kontak
│
├── includes/              # Folder library
│   ├── config.php         # Konfigurasi database
│   ├── db.php             # Koneksi database
│   └── functions.php      # Fungsi-fungsi
│
├── uploads/               # Folder untuk upload
│   ├── articles/          # Upload gambar artikel
│   └── gallery/           # Upload gambar galeri
│
└── assets/                # Asset publik
    ├── css/style.css
    ├── js/script.js
    └── images/
```

---

## 💾 TABEL DATABASE

### 1. **users** - Tabel pengguna/admin
```sql
id, username, password, email, role, created_at
```

### 2. **articles** - Tabel berita
```sql
id, title, slug, category_id, author_id, content, featured_image, 
excerpt, status, views, created_at, updated_at, published_at
```

### 3. **categories** - Tabel kategori berita
```sql
id, name, slug, description, created_at
```

### 4. **comments** - Tabel komentar
```sql
id, article_id, author_name, author_email, content, status, created_at
```

### 5. **gallery** - Tabel galeri
```sql
id, title, category, image_path, description, created_at
```

### 6. **achievements** - Tabel prestasi
```sql
id, no, student_name, year, achievement_title, category, level, created_at
```

### 7. **extracurriculars** - Tabel ekstrakurikuler
```sql
id, name, type, description, created_at
```

### 8. **contact_messages** - Tabel pesan kontak
```sql
id, name, email, phone, subject, message, status, created_at
```

---

## 📝 FITUR UTAMA

### ✅ Admin Panel
- 🔐 Login dengan username & password
- 📰 Kelola berita (tambah, edit, hapus, publish)
- 📂 Kelola kategori berita
- 🖼️ Kelola galeri (dengan upload gambar)
- ⭐ Kelola prestasi siswa
- 🎭 Kelola ekstrakurikuler
- 💬 Kelola komentar (approve/reject)
- ✉️ Kelola pesan dari form kontak
- 📊 Dashboard dengan statistik

### ✅ Website Frontend
- 📚 Halaman berita dengan pagination
- 🔍 Pencarian berita
- 💬 Sistem komentar pada setiap artikel
- 📞 Form kontak yang tersimpan di database
- 🖼️ Galeri dengan filter kategori
- 📋 Tabel prestasi dinamis
- 📚 Daftar ekstrakurikuler dari database

---

## 🎯 CARA MENGGUNAKAN

### Menambah Berita
1. Login ke admin: `http://localhost/website/admin/login.php`
2. Klik menu "📝 Kelola Berita"
3. Klik tombol "+ Tambah Berita"
4. Isi form dengan:
   - Judul berita
   - Kategori
   - Isi/content
   - Gambar (opsional)
   - Status (Draft/Publis)
5. Klik "Simpan Berita"

### Menambah Gambar Galeri
1. Login ke admin
2. Klik menu "🖼️ Galeri"
3. Klik "Upload Gambar"
4. Pilih gambar, kategori, dan deskripsi
5. Klik upload

### Menambah Prestasi
1. Login ke admin
2. Klik menu "⭐ Prestasi"
3. Isi data prestasi (nama siswa, tahun, kategori, kesimpulan, tingkat)
4. Klik "Tambah"

---

## 🔧 TROUBLESHOOTING

### Database tidak terkoneksi
**Error:** "Connection failed"
- Pastikan MySQL running
- Cek konfigurasi di `includes/config.php`
- Verifikasi username, password, dan nama database

### Upload gambar gagal
- Pastikan folder `uploads/articles` dan `uploads/gallery` ada
- Folder harus memiliki permission 755
- Ukuran gambar maksimal 5MB

### Admin panel tidak bisa di-akses
- Pastikan URL benar: `http://localhost/website/admin/login.php`
- Clear browser cache
- Cek konfigurasi SITE_URL di `includes/config.php`

---

## 🔐 KEAMANAN

### Rekomendasi Keamanan:
1. ✅ Ubah password admin default setelah instalasi
2. ✅ Jangan expose `config.php` ke publik
3. ✅ Gunakan HTTPS di production
4. ✅ Backup database secara berkala
5. ✅ Update password secara berkala
6. ✅ Jangan upload file berbahaya

---

## 📞 SUPPORT

Untuk pertanyaan atau masalah, hubungi:
- 📧 Email: `info@smpmuhammadiyah-salatiga.sch.id`
- 📞 Telepon: (0298) 322 441
- 💬 WhatsApp: 0857 2848 9757

---

## 📄 LICENSE

Sistem ini dikembangkan untuk SMP Muhammadiyah (Tahfidz) Salatiga.
Semua hak cipta dilindungi © 2026

---

**Last Updated:** 9 Maret 2026
**Versi:** 1.0
