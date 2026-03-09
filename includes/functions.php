<?php
require_once 'db.php';

// Fungsi untuk membuat slug
function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Fungsi untuk format tanggal
function formatDate($date) {
    $months = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    
    $date_obj = new DateTime($date);
    $formatted = $date_obj->format('d F Y');
    
    foreach ($months as $eng => $indo) {
        $formatted = str_replace($eng, $indo, $formatted);
    }
    
    return $formatted;
}

// Fungsi untuk validasi email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi untuk upload gambar
function uploadImage($file, $folder = 'articles') {
    global $db;
    
    if (!isset($file) || $file['error'] != 0) {
        return ['success' => false, 'message' => 'File tidak ditemukan'];
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar'];
    }
    
    $upload_path = UPLOAD_DIR . $folder . '/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
    }
    
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
    $filepath = $upload_path . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $folder . '/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk get artikel
function getArticles($limit = null, $category_id = null, $status = 'published') {
    global $db;
    
    $sql = "SELECT a.*, c.name as category_name, u.username as author_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            LEFT JOIN users u ON a.author_id = u.id 
            WHERE a.status = '$status'";
    
    if ($category_id) {
        $sql .= " AND a.category_id = $category_id";
    }
    
    $sql .= " ORDER BY a.published_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk get artikel by slug
function getArticleBySlug($slug) {
    global $db;
    
    $sql = "SELECT a.*, c.name as category_name, u.username as author_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            LEFT JOIN users u ON a.author_id = u.id 
            WHERE a.slug = '" . $db->escapeString($slug) . "' AND a.status = 'published'";
    
    $result = $db->query($sql);
    return $result->fetch_assoc();
}

// Fungsi untuk update views
function updateArticleViews($article_id) {
    global $db;
    $sql = "UPDATE articles SET views = views + 1 WHERE id = $article_id";
    $db->query($sql);
}

// Fungsi untuk get komentar
function getComments($article_id, $status = 'approved') {
    global $db;
    
    $sql = "SELECT * FROM comments 
            WHERE article_id = $article_id AND status = '$status' 
            ORDER BY created_at DESC";
    
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk tambah komentar
function addComment($article_id, $author_name, $author_email, $content) {
    global $db;
    
    $article_id = (int)$article_id;
    $author_name = $db->escapeString($author_name);
    $author_email = $db->escapeString($author_email);
    $content = $db->escapeString($content);
    
    $sql = "INSERT INTO comments (article_id, author_name, author_email, content) 
            VALUES ($article_id, '$author_name', '$author_email', '$content')";
    
    return $db->query($sql);
}

// Fungsi untuk get galeri
function getGallery($category = null) {
    global $db;
    
    $sql = "SELECT * FROM gallery";
    
    if ($category) {
        $sql .= " WHERE category = '" . $db->escapeString($category) . "'";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk get prestasi
function getAchievements() {
    global $db;
    
    $sql = "SELECT * FROM achievements ORDER BY year DESC, id DESC";
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk get ekstrakurikuler
function getExtracurriculars($type = null) {
    global $db;
    
    $sql = "SELECT * FROM extracurriculars";
    
    if ($type) {
        $sql .= " WHERE type = '" . $db->escapeString($type) . "'";
    }
    
    $sql .= " ORDER BY name ASC";
    
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk search artikel
function searchArticles($keyword, $limit = 10) {
    global $db;
    
    $keyword = $db->escapeString($keyword);
    
    $sql = "SELECT a.*, c.name as category_name, u.username as author_name 
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id 
            LEFT JOIN users u ON a.author_id = u.id 
            WHERE a.status = 'published' AND 
            (a.title LIKE '%$keyword%' OR a.content LIKE '%$keyword%' OR 
             a.excerpt LIKE '%$keyword%' OR c.name LIKE '%$keyword%') 
            ORDER BY a.published_at DESC 
            LIMIT $limit";
    
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk check login
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Fungsi untuk check admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

// Fungsi redirect
function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit;
}
