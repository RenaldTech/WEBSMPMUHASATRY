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
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = $db->escapeString($_POST['name']);
            $description = $db->escapeString($_POST['description']);
            $slug = createSlug($name);

            if (!$name) {
                $error = 'Nama kategori harus diisi!';
            } else {
                if ($_POST['action'] === 'add') {
                    $sql = "INSERT INTO categories (name, slug, description) VALUES ('$name', '$slug', '$description')";
                    if ($db->query($sql)) {
                        $message = 'Kategori berhasil ditambahkan!';
                    } else {
                        $error = 'Gagal menambahkan kategori!';
                    }
                } else {
                    $id = (int)$_POST['category_id'];
                    $sql = "UPDATE categories SET name='$name', slug='$slug', description='$description' WHERE id=$id";
                    if ($db->query($sql)) {
                        $message = 'Kategori berhasil diupdate!';
                    } else {
                        $error = 'Gagal mengupdate kategori!';
                    }
                }
            }
        } else if ($_POST['action'] === 'delete') {
            $id = (int)$_POST['category_id'];
            $sql = "DELETE FROM categories WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Kategori berhasil dihapus!';
            } else {
                $error = 'Gagal menghapus kategori!';
            }
        }
    }
}

$categories = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container { display: grid; grid-template-columns: 250px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #1e3a5f; color: white; padding: 2rem 0; }
        .admin-content { padding: 2rem; background: #f9fafb; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .sidebar-title { font-size: 1.5rem; font-weight: 700; padding: 1rem 1.5rem; margin-bottom: 1rem; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu a { display: block; padding: 1rem 1.5rem; color: white; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #2d5a8c; }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 1rem; }
        table th { background: #1e3a5f; color: white; padding: 1rem; text-align: left; }
        table td { padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-small { padding: 0.375rem 0.75rem; margin-right: 0.5rem; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; }
        .message.success { background: #dcfce7; color: #166534; }
        .message.error { background: #fee2e2; color: #991b1b; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 500px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-title">Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="articles.php">📝 Berita</a></li>
                <li><a href="categories.php" class="active">📂 Kategori</a></li>
                <li><a href="achievements.php">🏆 Prestasi</a></li>
                <li><a href="extracurricular.php">⚽ Ekstrakurikuler</a></li>
                <li><a href="gallery.php">📷 Galeri</a></li>
                <li><a href="comments.php">💬 Komentar</a></li>
                <li><a href="messages.php">✉️ Pesan Kontak</a></li>
                <li><a href="logout.php" style="background: #dc2626;">🚪 Logout</a></li>
            </ul>
        </aside>

        <div class="admin-content">
            <div class="admin-header">
                <h1>Kelola Kategori Berita</h1>
                <button class="btn btn-primary" onclick="openModal()">+ Tambah Kategori</button>
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
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['name']; ?></td>
                            <td><?php echo $category['slug']; ?></td>
                            <td><?php echo $category['description']; ?></td>
                            <td>
                                <button class="btn btn-primary btn-small" onclick="editModal(<?php echo $category['id']; ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Kategori</h2>
            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="category_id" id="category_id" value="">

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="description"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    <button type="button" class="btn" onclick="closeModal()" style="background: #6b7280; color: white;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Kategori';
            document.getElementById('action').value = 'add';
            document.getElementById('category_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('categoryModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('categoryModal').style.display = 'none';
        }

        function editModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('action').value = 'edit';
            document.getElementById('category_id').value = id;
            document.getElementById('categoryModal').style.display = 'block';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('categoryModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
