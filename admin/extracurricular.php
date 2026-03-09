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
            $name = $db->escapeString($_POST['name']);
            $type = $_POST['type'];
            $description = $db->escapeString($_POST['description']);

            if (!$name) {
                $error = 'Nama ekstrakurikuler harus diisi!';
            } else {
                if ($_POST['action'] === 'add') {
                    $sql = "INSERT INTO extracurriculars (name, type, description) 
                            VALUES ('$name', '$type', '$description')";
                    if ($db->query($sql)) {
                        $message = 'Ekstrakurikuler berhasil ditambahkan!';
                    } else {
                        $error = 'Gagal menambahkan ekstrakurikuler!';
                    }
                } else {
                    $id = (int)$_POST['extracurricular_id'];
                    $sql = "UPDATE extracurriculars SET name='$name', type='$type', description='$description' WHERE id=$id";
                    if ($db->query($sql)) {
                        $message = 'Ekstrakurikuler berhasil diupdate!';
                    } else {
                        $error = 'Gagal mengupdate ekstrakurikuler!';
                    }
                }
            }
        } else if ($_POST['action'] === 'delete') {
            $id = (int)$_POST['extracurricular_id'];
            $sql = "DELETE FROM extracurriculars WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Ekstrakurikuler berhasil dihapus!';
            } else {
                $error = 'Gagal menghapus ekstrakurikuler!';
            }
        }
    }
}

// Ambil data ekstrakurikuler
$extracurriculars = $db->query("SELECT * FROM extracurriculars ORDER BY type DESC, name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ekstrakurikuler - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { display: grid; grid-template-columns: 250px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #1e3a5f; color: white; padding: 2rem 0; }
        .admin-content { padding: 2rem; background: #f9fafb; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .sidebar-title { font-size: 1.5rem; font-weight: 700; padding: 1rem 1.5rem; margin-bottom: 1rem; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li { margin: 0; }
        .sidebar-menu a { display: block; padding: 1rem 1.5rem; color: white; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2d5a8c; }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 1rem; }
        table th { background: #1e3a5f; color: white; padding: 1rem; text-align: left; }
        table td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-edit { background: #2563eb; color: white; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; }
        .message.success { background: #dcfce7; color: #166534; }
        .message.error { background: #fee2e2; color: #991b1b; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 500px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
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
                <li><a href="extracurricular.php" class="active">⚽ Ekstrakurikuler</a></li>
                <li><a href="gallery.php">📷 Galeri</a></li>
                <li><a href="comments.php">💬 Komentar</a></li>
                <li><a href="messages.php">✉️ Pesan Kontak</a></li>
                <li><a href="logout.php" style="background: #dc2626;">🚪 Logout</a></li>
            </ul>
        </aside>

        <div class="admin-content">
            <div class="admin-header">
                <h1>Kelola Ekstrakurikuler</h1>
                <button class="btn btn-primary" onclick="openModal()">+ Tambah Ekstrakurikuler</button>
            </div>

            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Nama Ekstrakurikuler</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($extracurriculars as $ekskul): ?>
                        <tr>
                            <td><?php echo $ekskul['name']; ?></td>
                            <td><?php echo ucfirst($ekskul['type']); ?></td>
                            <td><?php echo substr($ekskul['description'], 0, 50) . '...'; ?></td>
                            <td>
                                <button class="btn btn-edit" style="padding:0.375rem 0.75rem;">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="extracurricular_id" value="<?php echo $ekskul['id']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding:0.375rem 0.75rem;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="ekstrakurikulerModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Ekstrakurikuler</h2>
            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="extracurricular_id" id="extracurricular_id" value="">

                <div class="form-group">
                    <label>Nama Ekstrakurikuler</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Tipe</label>
                    <select name="type" id="type" required>
                        <option value="wajib">Wajib</option>
                        <option value="pilihan">Pilihan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Ekstrakurikuler</button>
                    <button type="button" class="btn" onclick="closeModal()" style="background: #6b7280; color: white;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Ekstrakurikuler';
            document.getElementById('action').value = 'add';
            document.getElementById('name').value = '';
            document.getElementById('type').value = 'pilihan';
            document.getElementById('description').value = '';
            document.getElementById('ekstrakurikulerModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('ekstrakurikulerModal').style.display = 'none';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('ekstrakurikulerModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
