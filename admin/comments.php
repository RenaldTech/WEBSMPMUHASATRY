<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    redirect('admin/login.php');
}

global $db;

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'approve' || $_POST['action'] === 'reject') {
            $id = (int)$_POST['comment_id'];
            $status = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
            $sql = "UPDATE comments SET status='$status' WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Komentar berhasil ' . ($status === 'approved' ? 'disetujui' : 'ditolak') . '!';
            }
        } else if ($_POST['action'] === 'delete') {
            $id = (int)$_POST['comment_id'];
            $sql = "DELETE FROM comments WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Komentar berhasil dihapus!';
            }
        }
    }
}

$comments = $db->query("SELECT c.*, a.title as article_title FROM comments c 
                        LEFT JOIN articles a ON c.article_id = a.id 
                        ORDER BY c.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Komentar - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { display: grid; grid-template-columns: 250px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #1e3a5f; color: white; padding: 2rem 0; }
        .admin-content { padding: 2rem; background: #f9fafb; }
        .sidebar-title { font-size: 1.5rem; font-weight: 700; padding: 1rem 1.5rem; margin-bottom: 1rem; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu a { display: block; padding: 1rem 1.5rem; color: white; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2d5a8c; }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 1rem; }
        table th { background: #1e3a5f; color: white; padding: 1rem; text-align: left; }
        table td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .btn { padding: 0.375rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer; margin-right: 0.5rem; }
        .btn-success { background: #16a34a; color: white; }
        .btn-warning { background: #ea8c55; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; }
        .message.success { background: #dcfce7; color: #166534; }
        .status { padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-weight: 600; font-size: 0.85rem; }
        .status.pending { background: #fef3c7; color: #92400e; }
        .status.approved { background: #dcfce7; color: #166534; }
        .status.rejected { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-title">Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="articles.php">📝 Berita</a></li>
                <li><a href="categories.php">📂 Kategori</a></li>
                <li><a href="achievements.php">🏆 Prestasi</a></li>
                <li><a href="extracurricular.php">⚽ Ekstrakurikuler</a></li>
                <li><a href="gallery.php">📷 Galeri</a></li>
                <li><a href="comments.php" class="active">💬 Komentar</a></li>
                <li><a href="messages.php">✉️ Pesan Kontak</a></li>
                <li><a href="logout.php" style="background: #dc2626;">🚪 Logout</a></li>
            </ul>
        </aside>

        <div class="admin-content">
            <h1>Kelola Komentar Artikel</h1>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Artikel</th>
                        <th>Penulis</th>
                        <th>Komentar</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?php echo $comment['article_title']; ?></td>
                            <td><?php echo $comment['author_name']; ?></td>
                            <td><?php echo substr($comment['content'], 0, 50) . '...'; ?></td>
                            <td>
                                <span class="status <?php echo $comment['status']; ?>">
                                    <?php echo ucfirst($comment['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($comment['created_at'])); ?></td>
                            <td>
                                <?php if ($comment['status'] !== 'approved'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <button type="submit" class="btn btn-success">Setuju</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($comment['status'] !== 'rejected'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="reject">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <button type="submit" class="btn btn-warning">Tolak</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
