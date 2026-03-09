<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

global $db;

// Get ekstrakurikuler
$wajib = getExtracurriculars('wajib');
$pilihan = getExtracurriculars('pilihan');

// Get prestasi
$achievements = getAchievements();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademik - SMP Muhammadiyah (Tahfidz) Salatiga</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- NAVBAR -->
    <nav>
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <span><img src="assets/images/logo.png" alt="Logo SMP Muhammadiyah"></span>
                <span>SMP Muhammadiyah<br>(Tahfidz) Salatiga</span>
            </a>
            <button class="nav-toggle">☰</button>
            <ul class="navbar-menu">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="akademik.php" class="active">Akademik</a></li>
                <li><a href="berita.php">Berita</a></li>
                <li><a href="spmb.php">SPMB</a></li>
                <li><a href="galeri.php">Galeri</a></li>
                <li><a href="kontak.php">Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Program Akademik</h1>
            <p>Pendidikan berkualitas dengan pendekatan holistik</p>
        </div>
    </section>

<!-- PROGRAM UNGGULAN -->
<section class="section" style="background-color:#f9fafb;">
    <h2 class="section-title">Program Unggulan</h2>

    <div class="cards-grid">

        <div class="card">
            <div class="card-header">
                <h3>GO-Glow</h3>
            </div>
            <div class="card-body">
                <p>1. Daily conversation</p>
                <p>2. Story telling</p>
                <p>3. Speech</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Pointer</h3>
            </div>
            <div class="card-body">
                <p>1. Desain</p>
                <p>2. Kalender</p>
                <p>3. Undangan</p>
                <p>4. Kartu nama</p>
                <p>5. Sinematografi</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Sainsmatika</h3>
            </div>
            <div class="card-body">
                <p>1. Memiliki club pendamping Matematika dan Sains</p>
                <p>2. Prestasi di bidang Matematika dan Sains</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>E-Sports</h3>
            </div>
            <div class="card-body">
                <p>1. Wadah prestasi non akademik</p>
                <p>2. Latihan dan kompetisi</p>
                <p>3. Mengadakan lomba E-Sports</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Sispala</h3>
            </div>
            <div class="card-body">
                <p>1. Wadah siswa pecinta alam</p>
                <p>2. Belajar kompas, eksplorasi, dan pelestarian alam</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Ekstrakurikuler</h3>
            </div>
            <div class="card-body">
                <p>
                    Berbagai kegiatan ekstrakurikuler untuk mengembangkan 
                    bakat siswa di bidang seni, olahraga, dan akademik.
                </p>
            </div>
        </div>

    </div>
</section>
    <!-- EKSTRAKURIKULER -->
    <section class="ekskul-section">

    <h2 class="section-title">Ekstrakurikuler</h2>

    <div class="ekskul-grid">

        <!-- WAJIB -->
        <div class="ekskul-box wajib">
            <h3>Ekstrakurikuler Wajib</h3>
            <ul>
                <?php foreach ($wajib as $ekskul): ?>
                    <li><?php echo $ekskul['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- PILIHAN -->
        <div class="ekskul-box pilihan">
            <h3>Ekstrakurikuler Pilihan</h3>
            <ul>
                <?php foreach ($pilihan as $ekskul): ?>
                    <li><?php echo $ekskul['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>

</section>
    <!-- PRESTASI -->
  <section class="section">
    <h2 class="section-title">Prestasi Siswa</h2>
    <p class="section-subtitle">Pencapaian siswa dalam berbagai kompetisi</p>
    
    <div class="table-container">
        <table class="scrollable-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Tahun</th>
                    <th>Prestasi</th>
                    <th>Kategori</th>
                    <th>Tingkat</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($achievements as $achievement): ?>
                    <tr>
                        <td><?php echo $achievement['no']; ?></td>
                        <td><?php echo $achievement['student_name']; ?></td>
                        <td><?php echo $achievement['year']; ?></td>
                        <td><?php echo $achievement['achievement_title']; ?></td>
                        <td><?php echo $achievement['category']; ?></td>
                        <td><?php echo $achievement['level']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
    <!-- FOOTER -->
   <footer>
        <div class="footer-container">
            <div class="footer-section">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <span style="font-size: 2.5rem;"><img src="assets/images/logo.png" alt="Logo SMP Muhammadiyah"></span>
                    <div style="font-weight: 700; font-size: 0.95rem;">SMP Muhammadiyah<br>(Tahfidz) Salatiga</div>
                </div>
                <p>Jl. Cempaka No.5-7, Jetis, Kecamatan Sidorejo, Kota Salatiga, Jawa Tengah 50711.</p>
                <p style="margin-top: 1.5rem; font-weight: 600;">Follow us on:</p>
                <div class="social-links">
                    <a href="https://facebook.com/smpmuhsltg" target="_blank" title="Facebook"><img src="assets/images/facebook.jpg" alt="facebook"></a>
                    <a href="https://instagram.com/smpmuhammadiyahsalatiga" target="_blank" title="Instagram"><img src="assets/images/instagram.jpg" alt="instagram"></a>
                    <a href="https://www.tiktok.com/@smpmuhammadiyahsalatiga" target="_blank" title="TikTok"><img src="assets/images/tiktok.jpg" alt="tiktok"></a>
                    <a href="https://youtube.com/@smpmuhammadiyahcempakasala5600" target="_blank" title="YouTube"><img src="assets/images/youtube.jpg" alt="youtube"></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <a href="akademik.php">Home</a>
                <a href="akademik.php#program unggulan">program unggulan</a>
                <a href="akademik.php#ekstrakulikuler">ekstrakulikuler</a>
                <a href="akademik.php#prestasi">prestasi</a>
            </div>
            <div class="footer-section">
                <h4>All Pages</h4>
                <a href="akademik.php">Akademik</a>
                <a href="berita.php">Berita</a>
                <a href="kontak.php">Kontak</a>
                <a href="spmb.php">SPMB</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026, SMP Muhammadiyah Tahfidz. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
