# QUICK START GUIDE - Implementasi Database MySQL

## 📊 Alur Sistem yang Sudah Dibuat

```
┌─────────────────────────────────────────────────────────┐
│            ADMIN PANEL (Backend)                        │
│                                                          │
│  Admin Panel → Database MySQL → API Endpoints          │
│  ├── Kelola Berita                                     │
│  ├── Kelola Kategori                                  │
│  ├── Kelola Galeri                                    │
│  ├── Kelola Prestasi                                 │
│  ├── Kelola Ekstrakurikuler                         │
│  ├── Kelola Komentar                                │
│  └── Kelola Pesan Kontak                            │
│                                                         │
└─────────────────────────────────────────────────────────┘
                         ↓↑ (API)
┌─────────────────────────────────────────────────────────┐
│        WEBSITE PUBLIK (Frontend)                        │
│                                                          │
│  index.php          → Beranda dinamis                  │
│  berita.php         → Daftar berita dari DB           │
│  artikel.php        → Detail artikel + komentar       │
│  akademik.php       → Prestasi + Ekstrakurikuler     │
│  galeri.php         → Galeri dari database            │
│  kontak.php         → Form kontak tersimpan DB        │
│  spmb.php           → Info PPDB                       │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## 🎯 CHECKLIST IMPLEMENTASI

### Langkah 1: Setup Database ✅
- [ ] Buka MySQL atau phpMyAdmin
- [ ] Jalankan script `database.sql`
- [ ] Verifikasi database `smp_muhammadiyah` terbuat

### Langkah 2: Konfigurasi File PHP ✅
- [ ] Edit `includes/config.php` dengan data MySQL Anda
- [ ] Verifikasi `SITE_URL` sesuai dengan localhost Anda
- [ ] Cek koneksi database (buka `index.php`)

### Langkah 3: Folder Upload ✅
- [ ] Pastikan folder `uploads/` ada dan writable
- [ ] Buat folder `uploads/articles/` dan `uploads/gallery/`
- [ ] Set permission minimal 755

### Langkah 4: Test Admin Panel ✅
- [ ] Buka `http://localhost/website/admin/login.php`
- [ ] Login dengan: admin / admin123
- [ ] Lihat dashboard

### Langkah 5: Test Website ✅
- [ ] Buka `http://localhost/website/index.php`
- [ ] Klik menu Berita → Verifikasi koneksi database
- [ ] Test form kontak → Verifikasi data masuk DB

---

## 📝 FITUR YANG SUDAH AKTIF

| Fitur | Status | File |
|-------|--------|------|
| Login Admin | ✅ | `admin/login.php` |
| Dashboard | ✅ | `admin/dashboard.php` |
| Kelola Berita | ✅ | `admin/articles.php` |
| Berita Publik | ✅ | `berita.php`, `artikel.php` |
| Komentar | ✅ | `api/data.php`, `artikel.php` |
| Form Kontak | ✅ | `kontak.php`, `api/contact.php` |
| Galeri Dinamis | ✅ | `galeri.php` |
| Prestasi Dinamis | ✅ | `akademik.php` |
| Search Article | ✅ | `berita.php`, `api/data.php` |

---

## 🚀 NEXT STEPS - FITUR LANJUTAN (OPSIONAL)

```php
// Fitur yang sudah siap framework-nya, tinggal frontend:

1. Categories Management
   File: admin/categories.php
   Function: Kelola kategori berita

2. Gallery Management
   File: admin/gallery.php
   Function: Upload dan kelola galeri

3. Achievements Management
   File: admin/achievements.php
   Function: Tambah/edit prestasi siswa

4. Extracurricular Management
   File: admin/extracurricular.php
   Function: Kelola daftar ekstrakurikuler

5. Comments Moderation
   File: admin/comments.php
   Function: Approve/reject komentar

6. Messages Management
   File: admin/messages.php
   Function: Baca pesan form kontak
```

---

## 💡 TIPS & TRIK

### Mengubah Password Admin
```php
// Jalankan query MySQL ini:
UPDATE users SET password = '$2y$10$YOvVJAdP2OEdo8/fMz2dPO2HcVaYNr2VYfKgKvxnk9qgR5q8aLLWu' 
WHERE username = 'admin';

// Password akan menjadi: admin123
// Untuk membuat password baru, gunakan bcrypt generator
```

### Membuat User Admin Baru
```sql
INSERT INTO users (username, password, email, role) 
VALUES ('username_baru', 'PASSWORD_BCRYPT', 'email@example.com', 'admin');
```

### Backup Database
```bash
# Windows (Command Prompt):
mysqldump -u root -p smp_muhammadiyah > backup.sql

# Linux/Mac:
mysqldump -u root -p smp_muhammadiyah > backup.sql
```

### Restore Database
```bash
mysql -u root -p smp_muhammadiyah < backup.sql
```

---

## 🎨 CUSTOMIZATION

### Menambah Kategori Baru
```sql
INSERT INTO categories (name, slug, description) 
VALUES ('Kategori Baru', 'kategori-baru', 'Deskripsi');
```

### Menambah Ekskulikuler
```sql
INSERT INTO extracurriculars (name, type, description) 
VALUES ('Nama Ekskul', 'pilihan', 'Deskripsi');
```

### Mengubah Admin Akses
```sql
-- Ubah role user jadi editor
UPDATE users SET role = 'editor' WHERE username = 'user123';

-- Ubah role user jadi admin
UPDATE users SET role = 'admin' WHERE username = 'user123';
```

---

## 📱 RESPONSIVE DESIGN

Semua halaman sudah responsive untuk:
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (320px - 767px)

CSS responsive berada di: `assets/css/style.css`

---

## 🔗 API ENDPOINTS

### GET Data
```
GET /api/data.php?action=get_articles       (List berita)
GET /api/data.php?action=get_article&slug=  (Detail artikel)
GET /api/data.php?action=get_gallery        (Galeri)
GET /api/data.php?action=get_achievements   (Prestasi)
GET /api/data.php?action=get_extracurriculars (Ekskulikuler)
GET /api/data.php?action=get_comments&article_id=  (Komentar)
```

### POST Data
```
POST /api/data.php?action=add_comment        (Tambah komentar)
POST /api/contact.php                        (Kirim pesan kontak)
```

---

## ⚠️ COMMON ISSUES & SOLUTIONS

| Masalah | Solusi |
|---------|--------|
| 404 Not Found | Pastikan file .php ada, check URL |
| Connection refused | MySQL belum jalan, start MySQL service |
| Upload failed | Check folder permissions (755), file size |
| Data null di halaman | Pastikan artikel berstatus "published" |
| Login gagal | Clear cache browser, cek username/password |
| Gambar tidak muncul | Path folder uploads benar, file ada |

---

## 📞 SUPPORT & DOCUMENTATION

- **Database:** MySQL 5.7+
- **PHP:** PHP 7.2+
- **Browser:** Chrome, Firefox, Safari, Edge terbaru
- **Database Design:** Normalized structure dengan foreign keys

---

## 🎓 PEMBELAJARAN LEBIH LANJUT

Untuk mengembangkan lebih lanjut:

1. **Belajar PHP:** w3schools.com/php
2. **Belajar MySQL:** w3schools.com/sql
3. **Belajar API REST:** restfulapi.net
4. **Dokumentasi:** mysqli.php.net

---

**Created:** 9 March 2026  
**Version:** 1.0  
**Status:** Production Ready ✅
