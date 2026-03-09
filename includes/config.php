<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smp_muhammadiyah');

// Konfigurasi Umum
define('SITE_NAME', 'SMP Muhammadiyah Tahfidz Salatiga');
define('SITE_URL', 'http://localhost/website/');
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/website/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Timezone
date_default_timezone_set('Asia/Jakarta');
