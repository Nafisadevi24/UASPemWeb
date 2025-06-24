<?php
include "cek_session.php";
include "koneksi.php";

$edit_mode = false;
$edit_data = null;

// Proses tambah
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $ket = $_POST['keterangan'];
    $conn->query("INSERT INTO kategori(nama_kategori, keterangan) VALUES('$nama', '$ket')");
    header("Location: kategori.php");
    exit;
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM kategori WHERE id_kategori = $id");
    header("Location: kategori.php");
    exit;
}

// Proses edit - ambil data
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM kategori WHERE id_kategori = $id")->fetch_assoc();
}

// Proses simpan edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $ket = $_POST['keterangan'];
    $conn->query("UPDATE kategori SET nama_kategori='$nama', keterangan='$ket' WHERE id_kategori=$id");
    header("Location: kategori.php");
    exit;
}

// Ambil semua kategori
$kategori = $conn->query("SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 60px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h3 class="mb-4 text-primary text-center">
            <?= $edit_mode ? 'Edit Kategori' : 'Kelola Kategori Artikel' ?>
        </h3>

        <!-- Form Tambah / Edit -->
        <form method="post" class="mb-4">
            <input type="hidden" name="id" value="<?= $edit_mode ? $edit_data['id_kategori'] : '' ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="nama" name="nama"
                       value="<?= $edit_mode ? htmlspecialchars($edit_data['nama_kategori']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required><?= $edit_mode ? htmlspecialchars($edit_data['keterangan']) : '' ?></textarea>
            </div>
            <?php if ($edit_mode): ?>
                <button type="submit" name="simpan" class="btn btn-warning">Simpan Perubahan</button>
                <a href="kategori.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
                <button type="submit" class="card-header text-white" style="background: linear-gradient(to right, #0d47a1, #1976d2);">Tambah Kategori</button>
            <?php endif; ?>
        </form>

        <!-- Tabel Kategori -->
        <h5 class="mb-3">Daftar Kategori</h5>
        <table class="table table-bordered table-striped">
            <thead class="table-secondary">
            <tr>
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th style="width: 140px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $kategori->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td>
                        <a href="kategori.php?edit=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="kategori.php?hapus=<?= $row['id_kategori'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="text-end">
            <a href="dashboard.php" class="btn btn-outline-secondary mt-3">← Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
