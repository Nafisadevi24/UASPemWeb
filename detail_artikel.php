<?php
include "koneksi.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM artikel WHERE id_artikel=$id")->fetch_assoc();
$terkait = $conn->query("SELECT * FROM artikel WHERE id_artikel != $id ORDER BY tanggal DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $data['judul'] ?> - Blog Dinamis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .judul-artikel {
            font-size: 1.8rem;
            font-weight: 700;
            color: #254d87;
        }
        .isi-artikel {
            line-height: 1.8;
            color: #333;
        }
        .sidebar .input-group input {
            border-radius: 8px 0 0 8px;
        }
        .sidebar .input-group .btn {
            border-radius: 0 8px 8px 0;
        }
        .list-group-item {
            border: none;
            border-bottom: 1px solid #eee;
        }
        .list-group-item a {
            text-decoration: none;
            color: #254d87;
            font-weight: 500;
        }
        .list-group-item a:hover {
            text-decoration: underline;
            color: #1d3a70;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2b4c7e;">
    <div class="container">
        <a class="navbar-brand" href="index.php">Blog Dinamis</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Utama -->
<div class="container d-flex flex-wrap">
    <div class="col-md-8 mb-4">
        <h2 class="judul-artikel"><?= $data['judul'] ?></h2>
        <p class="tanggal">Dipublikasikan pada <?= date('d M Y, H:i', strtotime($data['tanggal'])) ?></p>

        <?php if (!empty($data['gambar'])): ?>
            <img src="gambar/<?= $data['gambar'] ?>" class="img-fluid rounded mb-3" alt="Gambar Artikel">
        <?php endif; ?>

        <div class="isi-artikel">
            <p><?= nl2br($data['isi']) ?></p>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4 sidebar">
        <!-- Form Pencarian -->
        <div class="mb-4">
            <form action="artikel_cari.php" method="get">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari judul...">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>

        <!-- Artikel Terkait -->
        <div class="card card-terkait">
            <div class="card-header bg-primary text-white">
                <strong>Artikel Terkait</strong>
            </div>
            <ul class="list-group list-group-flush">
                <?php while ($row = $terkait->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <a href="detail_artikel.php?id=<?= $row['id_artikel'] ?>">
                            <?= htmlspecialchars($row['judul']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Footer -->
<footer style="background-color: #2b4c7e; color: white; text-align: center; padding: 10px 0;">
    &copy; <?= date('Y') ?> Blog Dinamis - Informasi, Teknologi, Bisnis, Pendidikan, dan Kesehatan.
</footer>

</body>
</html>
