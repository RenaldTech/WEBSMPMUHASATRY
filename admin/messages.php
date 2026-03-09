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
        if ($_POST['action'] === 'mark_read') {
            $id = (int)$_POST['message_id'];
            $sql = "UPDATE contact_messages SET status='read' WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Pesan ditandai sudah dibaca!';
            }
        } else if ($_POST['action'] === 'mark_replied') {
            $id = (int)$_POST['message_id'];
            $sql = "UPDATE contact_messages SET status='replied' WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Pesan ditandai sudah dibalas!';
            }
        } else if ($_POST['action'] === 'delete') {
            $id = (int)$_POST['message_id'];
            $sql = "DELETE FROM contact_messages WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Pesan berhasil dihapus!';
            }
        }
    }
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan - Admin</title>
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
        .btn { padding: 0.375rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer; margin-right: 0.5rem; font-size: 0.85rem; }
        .btn-info { background: #3b82f6; color: white; }
        .btn-success { background: #16a34a; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; }
        .message.success { background: #dcfce7; color: #166534; }
        .status { padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-weight: 600; font-size: 0.85rem; }
        .status.new { background: #fca5a5; color: white; }
        .status.read { background: #fef3c7; color: #92400e; }
        .status.replied { background: #dcfce7; color: #166534; }
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
                <li><a href="comments.php">💬 Komentar</a></li>
                <li><a href="messages.php" class="active">✉️ Pesan Kontak</a></li>
                <li><a href="logout.php" style="background: #dc2626;">🚪 Logout</a></li>
            </ul>
        </aside>

        <div class="admin-content">
            <h1>Kelola Pesan Kontak</h1>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subjek</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo $msg['name']; ?></td>
                            <td><?php echo $msg['email']; ?></td>
                            <td><?php echo $msg['subject']; ?></td>
                            <td><?php echo substr($msg['message'], 0, 40) . '...'; ?></td>
                            <td>
                                <span class="status <?php echo $msg['status']; ?>">
                                    <?php echo ucfirst($msg['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($msg['created_at'])); ?></td>
                            <td>
                                <?php if ($msg['status'] === 'new'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="mark_read">
                                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                        <button type="submit" class="btn btn-info">Dibaca</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="mark_replied">
                                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                    <button type="submit" class="btn btn-success">Dibalas</button>
                                </form>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
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
