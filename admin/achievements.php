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
            $student_name = $db->escapeString($_POST['student_name']);
            $year = (int)$_POST['year'];
            $achievement_title = $db->escapeString($_POST['achievement_title']);
            $category = $db->escapeString($_POST['category']);
            $level = $db->escapeString($_POST['level']);

            if (!$student_name || !$achievement_title) {
                $error = 'Nama siswa dan judul prestasi harus diisi!';
            } else {
                if ($_POST['action'] === 'add') {
                    $sql = "INSERT INTO achievements (student_name, year, achievement_title, category, level) 
                            VALUES ('$student_name', $year, '$achievement_title', '$category', '$level')";
                    if ($db->query($sql)) {
                        $message = 'Prestasi berhasil ditambahkan!';
                    } else {
                        $error = 'Gagal menambahkan prestasi!';
                    }
                } else {
                    $id = (int)$_POST['achievement_id'];
                    $sql = "UPDATE achievements SET student_name='$student_name', year=$year, 
                            achievement_title='$achievement_title', category='$category', level='$level' 
                            WHERE id=$id";
                    if ($db->query($sql)) {
                        $message = 'Prestasi berhasil diupdate!';
                    } else {
                        $error = 'Gagal mengupdate prestasi!';
                    }
                }
            }
        } else if ($_POST['action'] === 'delete') {
            $id = (int)$_POST['achievement_id'];
            $sql = "DELETE FROM achievements WHERE id=$id";
            if ($db->query($sql)) {
                $message = 'Prestasi berhasil dihapus!';
            } else {
                $error = 'Gagal menghapus prestasi!';
            }
        }
    }
}

// Ambil data prestasi
$achievements = $db->query("SELECT * FROM achievements ORDER BY year DESC, no ASC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Prestasi - Admin</title>
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
        table tr:hover { background: #f3f4f6; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.875rem; }
        .btn-small { padding: 0.375rem 0.75rem; margin-right: 0.5rem; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-primary:hover { background: #2d5a8c; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-edit { background: #2563eb; color: white; }
        .btn-logout { background: #dc2626; color: white; padding: 0.5rem 1rem; }
        .message { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; }
        .message.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .message.error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 500px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .close { font-size: 2rem; font-weight: bold; cursor: pointer; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 1rem; }
        .form-group textarea { resize: vertical; min-height: 100px; }
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
                <li><a href="achievements.php" class="active">🏆 Prestasi</a></li>
                <li><a href="extracurricular.php">⚽ Ekstrakurikuler</a></li>
                <li><a href="gallery.php">📷 Galeri</a></li>
                <li><a href="comments.php">💬 Komentar</a></li>
                <li><a href="messages.php">✉️ Pesan Kontak</a></li>
                <li><a href="logout.php" class="btn-logout">🚪 Logout</a></li>
            </ul>
        </aside>

        <div class="admin-content">
            <div class="admin-header">
                <h1>Kelola Prestasi Siswa</h1>
                <button class="btn btn-primary" onclick="openModal()">+ Tambah Prestasi</button>
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
                        <th>Nama Siswa</th>
                        <th>Judul Prestasi</th>
                        <th>Kategori</th>
                        <th>Level</th>
                        <th>Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achievements as $achievement): ?>
                        <tr>
                            <td><?php echo $achievement['student_name']; ?></td>
                            <td><?php echo $achievement['achievement_title']; ?></td>
                            <td><?php echo $achievement['category']; ?></td>
                            <td><?php echo $achievement['level']; ?></td>
                            <td><?php echo $achievement['year']; ?></td>
                            <td>
                                <button class="btn btn-edit btn-small" onclick="editModal(<?php echo $achievement['id']; ?>)">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="achievement_id" value="<?php echo $achievement['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-small">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="achievementModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Prestasi</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="achievement_id" id="achievement_id" value="">

                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="student_name" id="student_name" required>
                </div>

                <div class="form-group">
                    <label>Judul Prestasi</label>
                    <input type="text" name="achievement_title" id="achievement_title" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="category" id="category" placeholder="Akademik, Olahraga, Seni, dll">
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <select name="level" id="level">
                        <option value="Sekolah">Tingkat Sekolah</option>
                        <option value="Kota">Tingkat Kota</option>
                        <option value="Provinsi">Tingkat Provinsi</option>
                        <option value="Nasional">Tingkat Nasional</option>
                        <option value="Internasional">Tingkat Internasional</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tahun</label>
                    <input type="number" name="year" id="year" value="<?php echo date('Y'); ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Prestasi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Prestasi';
            document.getElementById('action').value = 'add';
            document.getElementById('achievement_id').value = '';
            document.getElementById('student_name').value = '';
            document.getElementById('achievement_title').value = '';
            document.getElementById('category').value = '';
            document.getElementById('level').value = 'Sekolah';
            document.getElementById('year').value = new Date().getFullYear();
            document.getElementById('achievementModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('achievementModal').style.display = 'none';
        }

        function editModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Prestasi';
            document.getElementById('action').value = 'edit';
            document.getElementById('achievement_id').value = id;
            document.getElementById('achievementModal').style.display = 'block';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('achievementModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
