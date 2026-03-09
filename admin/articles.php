<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('admin/login.php');
}

global $db;

// Proses form
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $title = $db->escapeString($_POST['title']);
            $category_id = (int)$_POST['category_id'];
            $content = $db->escapeString($_POST['content']);
            $excerpt = $db->escapeString($_POST['excerpt']);
            $status = $_POST['status'];
            
            $slug = createSlug($title);
            
            // Handle upload gambar
            $featured_image = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
                $upload = uploadImage($_FILES['featured_image'], 'articles');
                if ($upload['success']) {
                    $featured_image = $upload['path'];
                } else {
                    $error = $upload['message'];
                }
            }
            
            if ($_POST['action'] === 'add') {
                $author_id = $_SESSION['user_id'];
                $published_at = ($status === 'published') ? date('Y-m-d H:i:s') : NULL;
                
                if ($featured_image) {
                    $sql = "INSERT INTO articles (title, slug, category_id, author_id, content, excerpt, featured_image, status, published_at) 
                            VALUES ('$title', '$slug', $category_id, $author_id, '$content', '$excerpt', '$featured_image', '$status', " . ($published_at ? "'$published_at'" : "NULL") . ")";
                } else {
                    $sql = "INSERT INTO articles (title, slug, category_id, author_id, content, excerpt, status, published_at) 
                            VALUES ('$title', '$slug', $category_id, $author_id, '$content', '$excerpt', '$status', " . ($published_at ? "'$published_at'" : "NULL") . ")";
                }
                
                if ($db->query($sql)) {
                    $message = 'Berita berhasil ditambahkan!';
                } else {
                    $error = 'Gagal menambahkan berita: ' . $db->getConnection()->error;
                }
            } else if ($_POST['action'] === 'edit') {
                $article_id = (int)$_POST['article_id'];
                
                $update_fields = "title = '$title', slug = '$slug', category_id = $category_id, content = '$content', excerpt = '$excerpt', status = '$status'";
                
                if ($featured_image) {
                    $update_fields .= ", featured_image = '$featured_image'";
                }
                
                if ($status === 'published') {
                    $check_published = $db->query("SELECT published_at FROM articles WHERE id = $article_id")->fetch_assoc();
                    if (!$check_published['published_at']) {
                        $update_fields .= ", published_at = '" . date('Y-m-d H:i:s') . "'";
                    }
                }
                
                $sql = "UPDATE articles SET $update_fields WHERE id = $article_id";
                
                if ($db->query($sql)) {
                    $message = 'Berita berhasil diperbarui!';
                } else {
                    $error = 'Gagal memperbarui berita: ' . $db->getConnection()->error;
                }
            }
        } else if ($_POST['action'] === 'delete') {
            $article_id = (int)$_POST['article_id'];
            
            // Hapus file gambar jika ada
            $article = $db->query("SELECT featured_image FROM articles WHERE id = $article_id")->fetch_assoc();
            if ($article && $article['featured_image']) {
                $file_path = UPLOAD_DIR . $article['featured_image'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            $sql = "DELETE FROM articles WHERE id = $article_id";
            if ($db->query($sql)) {
                $message = 'Berita berhasil dihapus!';
            } else {
                $error = 'Gagal menghapus berita';
            }
        }
    }
}

// Ambil data artikel
$articles = $db->query("SELECT a.*, c.name as category_name FROM articles a 
                        LEFT JOIN categories c ON a.category_id = c.id 
                        ORDER BY a.created_at DESC")->fetch_all(MYSQLI_ASSOC);

// Ambil kategori
$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--primary-color);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
        }
        
        .admin-header {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-add {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-add:hover {
            background: #003d99;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .articles-table {
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background: #f5f5f5;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-published {
            background: #d4edda;
            color: #155724;
        }
        
        .status-draft {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-archived {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .btn-group {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background: #0052CC;
            color: white;
        }
        
        .btn-edit:hover {
            background: #003d99;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal.show {
            display: block;
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 5px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .modal-close:hover {
            color: #000;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 200px;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(0, 82, 204, 0.3);
        }
        
        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }
        
        .btn-submit:hover {
            background: #003d99;
        }
        
        .btn-cancel {
            background: #999;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .btn-cancel:hover {
            background: #777;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="sidebar-title">📊 Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="articles.php" class="active">📝 Kelola Berita</a></li>
                <li><a href="categories.php">📂 Kategori</a></li>
                <li><a href="gallery.php">🖼️ Galeri</a></li>
                <li><a href="achievements.php">⭐ Prestasi</a></li>
                <li><a href="extracurricular.php">🎭 Ekstrakurikuler</a></li>
                <li><a href="comments.php">💬 Komentar</a></li>
                <li><a href="messages.php">✉️ Pesan</a></li>
                <li><a href="logout.php" style="margin-top: 20px;">🚪 Logout</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <div class="admin-header">
                <h1>Kelola Berita</h1>
                <button class="btn-add" onclick="openModal('addModal')">+ Tambah Berita</button>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="articles-table">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo substr($article['title'], 0, 50); ?>...</td>
                                <td><?php echo $article['category_name']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $article['status']; ?>">
                                        <?php echo ucfirst($article['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo formatDate($article['created_at']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-sm btn-edit" onclick="editArticle(<?php echo $article['id']; ?>)">Edit</button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                                            <button type="submit" class="btn-sm btn-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Tambah Berita -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal('addModal')">&times;</span>
            <h2>Tambah Berita Baru</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="form-group">
                    <label for="title">Judul Berita *</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Ringkasan (Excerpt)</label>
                    <textarea id="excerpt" name="excerpt" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="content">Isi Berita *</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="featured_image">Gambar Utama</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft">Draft</option>
                        <option value="published">Publis</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Simpan Berita</button>
                <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Batal</button>
            </form>
        </div>
    </div>
    
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }
        
        function editArticle(id) {
            alert('Fitur edit akan dikembangkan lebih lanjut');
        }
        
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>
