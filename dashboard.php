<?php
include "cek_session.php";
include "koneksi.php";

$artikel = $conn->query("SELECT * FROM artikel ORDER BY tanggal DESC");
$res = $conn->query("SELECT a.*, k.id_kategori FROM artikel a 
    JOIN kontributor k ON a.id_artikel = k.id_artikel 
    ORDER BY a.tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f3f7fa, #dce6f3);
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background: linear-gradient(to bottom right, #003366, #0059b3);
            padding: 25px;
            color: white;
            position: fixed;
            width: 230px;
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 12px 0;
            font-size: 15px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar a:hover {
            text-decoration: underline;
            margin-left: 5px;
        }

        .content {
            margin-left: 250px;
            padding: 40px;
        }

        .card-shadow {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        .img-thumb {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }

        .card-header {
            font-size: 16px;
        }

        .btn-custom {
            margin-bottom: 15px;
        }

        .card-header.bg-primary,
        .card-header.bg-success {
            background: linear-gradient(to right, #0059b3, #007bff);
        }

        .card-header.bg-success {
            background: linear-gradient(to right, #0f9d58, #34a853);
        }

        .btn-warning, .btn-danger {
            font-size: 14px;
            padding: 4px 10px;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4><i class="bi bi-speedometer2"></i> Admin Panel</h4>
    <a href="artikel_tambah.php"><i class="bi bi-file-earmark-plus"></i> Tambah Artikel</a>
    <a href="kategori.php"><i class="bi bi-tags"></i> Kelola Kategori</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="content">
    <h2 class="mb-4 text-primary"><i class="bi bi-house-door"></i> Selamat datang, Admin 👋</h2>

    <!-- FORM CARI -->
    <form class="mb-4 d-flex" action="artikel_cari.php" method="get">
        <input type="text" name="q" class="form-control me-2" placeholder="Cari judul artikel..." required>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>

    <!-- ARTIKEL TERBARU -->
    <div class="card mb-4 card-shadow border-0">
        <div class="card-header bg-primary text-white">
            <strong><i class="bi bi-clock-history"></i> Artikel Terbaru</strong>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0 table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $artikel->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DAFTAR ARTIKEL DENGAN GAMBAR -->
    <div class="card card-shadow border-0">
        <div class="card-header text-white" style="background: linear-gradient(to right, #0d47a1, #1976d2);">
            <strong><i class="bi bi-journal-text"></i> Daftar Artikel & Aksi</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Judul & Isi</th>
                        <th>Kategori</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php
                            $gambar = $row['gambar'];
                            $path = "gambar/" . $gambar;
                            if (!empty($gambar) && file_exists($path)) {
                                echo "<img src='$path' class='img-thumb'>";
                            } else {
                                echo "<img src='gambar/default.jpg' class='img-thumb'>";
                            }
                            ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($row['judul']) ?></strong><br>
                            <small class="text-muted"><?= substr(strip_tags($row['isi']), 0, 60) ?>...</small>
                        </td>
                        <td>
                            <?php
                            $kat = $conn->query("SELECT nama_kategori FROM kategori WHERE id_kategori=" . $row['id_kategori'])->fetch_assoc();
                            echo htmlspecialchars($kat['nama_kategori']);
                            ?>
                        </td>
                        <td>
                            <a href="artikel_edit.php?id=<?= $row['id_artikel'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="artikel_hapus.php?id=<?= $row['id_artikel'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
