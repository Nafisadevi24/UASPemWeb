<?php
include "cek_session.php";
include "koneksi.php";

$edit = false;
$data_edit = null;

// Tambah penulis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $conn->query("INSERT INTO penulis (nama, username, password) VALUES ('$nama', '$username', '$password')");
    header("Location: penulis.php");
    exit;
}

// Hapus penulis
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM penulis WHERE id_penulis = $id");
    header("Location: penulis.php");
    exit;
}

// Edit penulis
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $data_edit = $conn->query("SELECT * FROM penulis WHERE id_penulis = $id")->fetch_assoc();
}

// Simpan perubahan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE penulis SET nama='$nama', username='$username', password='$password' WHERE id_penulis=$id");
    } else {
        $conn->query("UPDATE penulis SET nama='$nama', username='$username' WHERE id_penulis=$id");
    }
    header("Location: penulis.php");
    exit;
}

$penulis = $conn->query("SELECT * FROM penulis");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Penulis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="card p-4">
        <h3 class="text-center text-primary"><?= $edit ? 'Edit Penulis' : 'Kelola Penulis' ?></h3>
        <form method="post" class="mb-4">
            <input type="hidden" name="id" value="<?= $edit ? $data_edit['id_penulis'] : '' ?>">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Penulis</label>
                <input type="text" class="form-control" name="nama" value="<?= $edit ? $data_edit['nama'] : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= $edit ? $data_edit['username'] : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><?= $edit ? 'Password Baru (opsional)' : 'Password' ?></label>
                <input type="password" class="form-control" name="password" <?= $edit ? '' : 'required' ?>>
            </div>
            <?php if ($edit): ?>
                <button type="submit" name="simpan" class="btn btn-warning">Simpan Perubahan</button>
                <a href="penulis.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn btn-success">Tambah Penulis</button>
            <?php endif; ?>
        </form>

        <h5>Daftar Penulis</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $penulis->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nama']) ?></td>
                        <td><?= htmlspecialchars($p['username']) ?></td>
                        <td>
                            <a href="penulis.php?edit=<?= $p['id_penulis'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="penulis.php?hapus=<?= $p['id_penulis'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus penulis ini?')">Hapus</a>
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
