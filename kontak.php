<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - SMP Muhammadiyah (Tahfidz) Salatiga</title>
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
                <li><a href="akademik.php">Akademik</a></li>
                <li><a href="berita.php">Berita</a></li>
                <li><a href="spmb.php">SPMB</a></li>
                <li><a href="galeri.php">Galeri</a></li>
                <li><a href="kontak.php" class="active">Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- HERO SECTION -->
<section class="section">
    <h2 class="section-title">Hubungi Kami</h2>
    <p class="section-subtitle">
        Kami siap membantu pertanyaan mengenai akademik, pendaftaran, maupun informasi sekolah.
    </p>
</section>
<section class="section" style="background:#f9fafb;">
    <h2 class="section-title">Media Sosial</h2>
    <p class="section-subtitle">Ikuti kami untuk mendapatkan informasi terbaru</p>

    <div class="social-grid">

        <a href="https://facebook.com/smpmuhammadiyahsalatiga" target="_blank" class="social-card facebook">
            <div>📘</div>
            <h3>Facebook</h3>
            <p>SMP Muhammadiyah Salatiga</p>
        </a>

        <a href="https://instagram.com/smpmuhammadiyahsalatiga" target="_blank" class="social-card instagram">
            <div>📷</div>
            <h3>Instagram</h3>
            <p>@smpmuhammadiyahsalatiga</p>
        </a>

        <a href="https://tiktok.com/@smpmuhammadiyahsalatiga" target="_blank" class="social-card tiktok">
            <div>🎵</div>
            <h3>TikTok</h3>
            <p>@smpmuhammadiyahsalatiga</p>
        </a>

        <a href="https://youtube.com/@smpmuhammadiyahcempakasala5600" target="_blank" class="social-card youtube">
            <div>▶️</div>
            <h3>YouTube</h3>
            <p>SMP Muhammadiyah Salatiga</p>
        </a>

    </div>
</section>
<section class="section">
    <h2 class="section-title">Lokasi & Informasi Kontak</h2>

    <div class="contact-grid">

        <!-- MAP -->
        <div class="map-container">
            <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.6701839087667!2d110.98394512346854!3d-7.219384892015637"
            width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
        </div>

        <!-- CONTACT INFO -->
        <div class="contact-details">

            <div class="contact-row">
                <h3>📍 Alamat</h3>
                <p>
                Jl. Cempaka No.5-7, Jetis<br>
                Kecamatan Sidorejo<br>
                Kota Salatiga, Jawa Tengah 50711
                </p>
            </div>

            <div class="contact-row">
                <h3>📞 Telepon</h3>
                <p>(0298) 322 441</p>
                <p>WA: 0857 2848 9757</p>
            </div>

            <div class="contact-row">
                <h3>📧 Email</h3>
                <p>info@smpmuhammadiyah-salatiga.sch.id</p>
                <p>spmb@smpmuhammadiyah-salatiga.sch.id</p>
            </div>

            <div class="contact-row">
                <h3>⏰ Jam Operasional</h3>
                <p>Senin - Jumat</p>
                <p>07.00 - 16.00 WIB</p>
            </div>

        </div>

    </div>
</section>
<section class="section" style="background:#f9fafb;">
    <h2 class="section-title">Kirim Pesan</h2>
    <p class="section-subtitle">
        Silakan kirim pertanyaan atau pesan kepada kami melalui formulir berikut.
    </p>

    <div class="contact-form-container" style="max-width: 600px; margin: auto;">
        <form id="contactForm">
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="text" name="name" placeholder="Nama Lengkap" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="tel" name="phone" placeholder="Nomor Telepon" required>
                <select name="subject" required style="padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="">Pilih Subjek</option>
                    <option>SPMB</option>
                    <option>Akademik</option>
                    <option>Fasilitas</option>
                    <option>Lainnya</option>
                </select>
            </div>

            <textarea name="message" placeholder="Tulis pesan Anda..." rows="6" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;"></textarea>

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                Kirim Pesan
            </button>
        </form>
    </div>
</section>
    <!-- FOOTER --><footer>
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
                <a href="index.php">Home</a>
                <a href="akademik.php">Akademik</a>
                <a href="berita.php">Berita</a>
                <a href="spmb.php">SPMB</a>
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
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pesan Anda berhasil dikirim! Terima kasih');
                    document.getElementById('contactForm').reset();
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
