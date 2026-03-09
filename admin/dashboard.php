<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Cek login
if (!isLoggedIn()) {
    redirect('admin/login.php');
}

global $db;

// Get statistik
$stats_articles = $db->query("SELECT COUNT(*) as total FROM articles WHERE status = 'published'")->fetch_assoc();
$stats_comments = $db->query("SELECT COUNT(*) as total FROM comments WHERE status = 'pending'")->fetch_assoc();
$stats_messages = $db->query("SELECT COUNT(*) as total FROM contact_messages WHERE status = 'new'")->fetch_assoc();

// Get artikel terbaru
$recent_articles = getArticles(5);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
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
        
        .admin-header {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        
        .recent-articles {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .btn-logout:hover {
            background: #c0392b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="sidebar-title">📊 Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="articles.php">📝 Kelola Berita</a></li>
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
                <h1>Dashboard</h1>
                <div>
                    <span>Selamat datang, <strong><?php echo $_SESSION['username']; ?></strong></span>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats_articles['total']; ?></div>
                    <div class="stat-label">Total Berita Dipublikasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats_comments['total']; ?></div>
                    <div class="stat-label">Komentar Menunggu</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $stats_messages['total']; ?></div>
                    <div class="stat-label">Pesan Baru</div>
                </div>
            </div>
            
            <div class="recent-articles">
                <h2>Berita Terbaru</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Tanggal</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_articles as $article): ?>
                            <tr>
                                <td><?php echo $article['title']; ?></td>
                                <td><?php echo $article['category_name']; ?></td>
                                <td><?php echo $article['author_name']; ?></td>
                                <td><?php echo formatDate($article['published_at']); ?></td>
                                <td><?php echo $article['views']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
