<?php
include "koneksi.php";
$q = $_GET['q'];
$data = $conn->query("SELECT * FROM artikel WHERE judul LIKE '%$q%'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pencarian - <?= htmlspecialchars($q) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafc;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 850px;
            margin-top: 50px;
        }
        .card-title a {
            text-decoration: none;
            color: #0d6efd;
        }
        .card-title a:hover {
            text-decoration: underline;
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

<div class="container">
    <div class="mb-4 text-center">
        <h2 class="text-primary">Hasil Pencarian</h2>
        <p class="text-muted">Menampilkan hasil untuk: <strong><?= htmlspecialchars($q) ?></strong></p>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali ke Beranda</a>
    </div>

    <?php if ($data->num_rows > 0): ?>
        <?php while ($row = $data->fetch_assoc()): ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <a href="detail_artikel.php?id=<?= $row['id_artikel'] ?>">
                        <?= htmlspecialchars($row['judul']) ?>
                    </a>
                </h5>
                <p class="card-text"><?= substr(strip_tags($row['isi']), 0, 150) ?>...</p>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">Tidak ada artikel yang ditemukan.</div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer style="background-color: #2b4c7e; color: white; text-align: center; padding: 10px 0;">
    &copy; <?= date('Y') ?> Blog Dinamis - Informasi, Teknologi, Bisnis, Pendidikan, dan Kesehatan.
</footer>

</body>
</html>
