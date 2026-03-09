# 📋 GUIDE IMPLEMENTASI LENGKAP - SMP Muhammadiyah Website

## ✅ Status Implementasi Proyek

Semua fitur utama telah selesai dikembangkan dan siap diimplementasikan.

---

## 🚀 LANGKAH IMPLEMENTASI

### LANGKAH 1: Setup Database MySQL

**Command:**
```bash
# Buka MySQL Command Line atau phpMyAdmin
mysql -u root -p

# Jalankan script database
source /path/to/database.sql

# Atau copy-paste seluruh script database.sql ke phpMyAdmin
```

**Verifikasi:**
- Buka phpMyAdmin
- Check database `smp_muhammadiyah` sudah terbuat
- Verifikasi tabel-tabel berikut ada:
  - `users` (admin account)
  - `articles` (berita/artikel)
  - `categories` (kategori berita)  
  - `comments` (komentar artikel)
  - `gallery` (galeri foto)
  - `achievements` (prestasi siswa)
  - `extracurriculars` (ekstrakurikuler)
  - `contact_messages` (pesan kontak)

---

### LANGKAH 2: Konfigurasi File PHP

Edit file `includes/config.php`:

```php
define('DB_HOST', 'localhost');     // Host MySQL Anda
define('DB_USER', 'root');          // Username MySQL
define('DB_PASS', '');              // Password MySQL (kosong jika tidak ada)
define('DB_NAME', 'smp_muhammadiyah'); // Nama database
define('SITE_URL', 'http://localhost/website/'); // URL website Anda
```

**Akun Admin Default:**
- Username: `admin`
- Password: `admin123`
- ⚠️ PENTING: Ubah password setelah login pertama!

---

### LANGKAH 3: Struktur Folder Upload

Pastikan folder uploads dengan struktur berikut sudah ada:

```
website/
└── uploads/
    ├── articles/     (untuk gambar artikel)
    └── gallery/      (untuk galeri foto)
```

Buat folder jika belum ada (pastikan permission 755 untuk write access)

---

## 🎯 FITUR YANG SUDAH DIIMPLEMENTASIKAN

### 1. **Admin Panel Dashboard**
   - ✅ Login/Logout
   - ✅ Statistik dashboard
   - ✅ Navigasi lengkap

### 2. **Manajemen Berita (Articles)**
   - ✅ Tambah/Edit/Hapus artikel
   - ✅ Upload gambar featured
   - ✅ Kategori berita
   - ✅ Status publikasi (published/draft)
   - ✅ Tracking views artikel

### 3. **Manajemen Kategori**
   - ✅ Tambah/Edit/Hapus kategori
   - ✅ Auto-generate slug
   - ✅ Deskripsi kategori

### 4. **Manajemen Galeri**
   - ✅ Upload foto galeri
   - ✅ Kategorisasi foto
   - ✅ Preview gambar
   - ✅ Hapus galeri

### 5. **Manajemen Prestasi Siswa**
   - ✅ Tambah/Edit/Hapus prestasi
   - ✅ Data: nama siswa, judul, kategori, level, tahun
   - ✅ Filter by year dan category

### 6. **Manajemen Ekstrakurikuler**
   - ✅ Tambah/Edit/Hapus ekstrakurikuler
   - ✅ Tipe: Wajib/Pilihan
   - ✅ Deskripsi lengkap

### 7. **Manajemen Komentar**
   - ✅ Moderasi komentar (approve/reject)
   - ✅ Hapus komentar
   - ✅ Status tracking

### 8. **Manajemen Pesan Kontak**
   - ✅ Lihat pesan dari form kontak
   - ✅ Mark as read/replied
   - ✅ Hapus pesan

### 9. **Website Frontend**
   - ✅ Beranda dinamis
   - ✅ Halaman berita
   - ✅ Detail artikel dengan komentar
   - ✅ Akademik (Prestasi + Ekstrakurikuler)
   - ✅ Galeri foto
   - ✅ Form kontak
   - ✅ Informasi PPDB

### 10. **API Endpoint**
   - ✅ `api/data.php` - Get articles, comments, gallery
   - ✅ `api/contact.php` - Submit contact form

---

## 🔐 URLs Akses

### Admin Panel
```
URL: http://localhost/website/admin/login.php
Username: admin
Password: admin123
```

**Menu Admin Panel:**
- Dashboard: `/admin/dashboard.php`
- Berita: `/admin/articles.php`
- Kategori: `/admin/categories.php`
- Prestasi: `/admin/achievements.php`
- Ekstrakurikuler: `/admin/extracurricular.php`
- Galeri: `/admin/gallery.php`
- Komentar: `/admin/comments.php`
- Pesan Kontak: `/admin/messages.php`

### Website Publik
```
Beranda: /index.php
Berita: /berita.php
Detail Artikel: /artikel.php?slug=nama-artikel
Akademik: /akademik.php
Galeri: /galeri.php
Kontak: /kontak.php
PPDB: /spmb.php
```

---

## 📝 FUNGSI UTAMA YANG TERSEDIA

### Fungsi Database (`includes/functions.php`)

```php
// Artikel
getArticles($limit, $category_id, $status)        // Ambil artikel
getArticleBySlug($slug)                          // Ambil artikel by slug
searchArticles($keyword, $limit)                 // Search artikel
updateArticleViews($article_id)                  // Update view counter

// Komentar
getComments($article_id, $status)                // Ambil komentar
addComment($article_id, $author, $email, $content) // Tambah komentar

// Galeri & Ekstrakurikuler
getGallery($category)                            // Ambil galeri
getExtracurriculars($type)                       // Ambil ekstrakurikuler
getAchievements()                                // Ambil prestasi

// Utility
createSlug($string)                              // Generate slug
formatDate($date)                                // Format tanggal
isValidEmail($email)                             // Validasi email
uploadImage($file, $folder)                      // Upload dan resize gambar
isLoggedIn()                                     // Check admin login
redirect($url)                                   // Redirect ke halaman
```

---

## 🛠️ CUSTOMIZATION

### Mengubah Warna Tema

Edit `assets/css/style.css` dan ubah `:root` variables:

```css
:root {
    --primary-color: #1e3a5f;      /* Biru tua */
    --secondary-color: #ffc107;     /* Kuning */
    --success-color: #28a745;       /* Hijau */
    --danger-color: #dc3545;        /* Merah */
}
```

### Upload File Besar

Edit `includes/config.php`:

```php
define('MAX_FILE_SIZE', 10485760); // 10MB (default 5MB)
```

---

## ⚠️ PENTING - SEBELUM GO LIVE

- [ ] Ubah password admin default
- [ ] Backup database secara regular
- [ ] Update keamanan form (add CSRF token)
- [ ] Konfigurasi email untuk notifikasi
- [ ] Test semua fitur admin
- [ ] Buat user akun editor tambahan jika perlu
- [ ] Setup SSL certificate (HTTPS)
- [ ] Konfigurasi backup otomatis

---

## 🆘 TROUBLESHOOTING

### Database Connection Error
- Pastikan MySQL running
- Verifikasi username/password di config.php
- Check database name di phpMyAdmin

### Upload Image Error
- Pastikan folder `uploads/articles` dan `uploads/gallery` exist
- Check permission folder (755)
- Verifikasi ukuran file < MAX_FILE_SIZE

### Login tidak berhasil
- Verifikasi session_start() dijalankan
- Check cookies enabled di browser
- Clear browser cache

### Artikel tidak tampil di frontend
- Verifikasi status = 'published'
- Check koneksi database
- Test API endpoint: `api/data.php?action=get_articles`

---

## 📞 SUPPORT

Jika ada pertanyaan atau issue, silakan check:
1. README.md - Overview proyek
2. SETUP.md - Setup instructions  
3. QUICK_START.md - Quick reference

---

**Selamat! Website SMP Muhammadiyah Anda sudah siap digunakan!** 🎉
