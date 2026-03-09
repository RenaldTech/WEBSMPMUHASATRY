-- Database untuk SMP Muhammadiyah
CREATE DATABASE IF NOT EXISTS smp_muhammadiyah;
USE smp_muhammadiyah;

-- Tabel untuk Admin/User
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Kategori Berita
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Berita
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    category_id INT,
    author_id INT,
    content LONGTEXT,
    featured_image VARCHAR(255),
    excerpt VARCHAR(300),
    status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel untuk Komentar
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT NOT NULL,
    author_name VARCHAR(100),
    author_email VARCHAR(100),
    content TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Tabel untuk Galeri
CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    category VARCHAR(100),
    image_path VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Prestasi Siswa
CREATE TABLE achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    no INT,
    student_name VARCHAR(100),
    year INT,
    achievement_title VARCHAR(255),
    category VARCHAR(100),
    level VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Ekstrakurikuler
CREATE TABLE extracurriculars (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('wajib', 'pilihan') DEFAULT 'pilihan',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk Pengumuman (Kontak/SPMB)
CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    type VARCHAR(50),
    attachment_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk Pesan dari Formulir Kontak
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    subject VARCHAR(255),
    message TEXT,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data kategori default
INSERT INTO categories (name, slug, description) VALUES 
('Darut Taqwa Camp', 'darut-taqwa-camp', 'Kegiatan Darut Taqwa Camp'),
('Ekstrakurikuler', 'ekstrakurikuler', 'Program Ekstrakurikuler'),
('Kemerdekaan', 'kemerdekaan', 'Perayaan Kemerdekaan'),
('Prestasi', 'prestasi', 'Prestasi Siswa'),
('Berita Umum', 'berita-umum', 'Berita-berita umum sekolah');

-- Insert ekskulikuler default
INSERT INTO extracurriculars (name, type, description) VALUES 
('Hisbul Waton', 'wajib', 'Organisasi siswa'),
('Drumband', 'wajib', 'Marching band sekolah'),
('Tapak Suci', 'wajib', 'Pencak silat tradisional'),
('Kaligrafi', 'pilihan', 'Seni kaligrafi'),
('Menari', 'pilihan', 'Seni tari'),
('Tata Boga', 'pilihan', 'Memasak dan resep'),
('Bahasa Jepang', 'pilihan', 'Belajar bahasa Jepang'),
('Renang', 'pilihan', 'Olahraga renang'),
('Karate', 'pilihan', 'Seni beladiri karate');

-- Insert admin default (password: admin123)
INSERT INTO users (username, password, email, role) VALUES 
('admin', '$2y$10$YOvVJAdP2OEdo8/fMz2dPO2HcVaYNr2VYfKgKvxnk9qgR5q8aLLWu', 'admin@smp.sch.id', 'admin');
