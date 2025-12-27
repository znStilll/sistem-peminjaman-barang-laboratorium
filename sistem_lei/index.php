<?php
include 'includes/koneksi.php';
session_start();

// Proteksi: Jika belum login, lempar ke login.php
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$nama_user = $_SESSION['nama'];

// Ambil data alat dari database
$query_alat = mysqli_query($conn, "SELECT * FROM alat ORDER BY nama_alat ASC");

// Ambil data peminjaman mahasiswa ini
$query_status = mysqli_query($conn, "SELECT t.*, a.nama_alat 
                                    FROM transaksi t 
                                    JOIN alat a ON t.id_alat = a.id_alat 
                                    WHERE t.id_user = '$id_user' 
                                    ORDER BY t.tgl_pinjam DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Alat - LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .navbar { background-color: #0d6efd; }
        .card-alat { border: none; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-alat:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .status-badge { font-size: 0.75rem; padding: 5px 10px; border-radius: 20px; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-cpu"></i> SISTEM LEI</a>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-block small">Halo, <strong><?php echo $nama_user; ?></strong></span>
            <?php if($_SESSION['role'] == 'asisten'): ?>
                <a href="dashboard_asisten.php" class="btn btn-light btn-sm me-2">Panel Asisten</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="mb-4 fw-bold"><i class="bi bi-grid-3x3-gap"></i> Katalog Alat</h4>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php while($alat = mysqli_fetch_assoc($query_alat)) : ?>
                <div class="col">
                    <div class="card h-100 card-alat">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark"><?php echo $alat['nama_alat']; ?></h5>
                            <p class="card-text text-muted mb-2 small">
                                <i class="bi bi-geo-alt"></i> Lokasi: <?php echo $alat['lokasi']; ?><br>
                                <i class="bi bi-box-seam"></i> Tersedia: <strong><?php echo $alat['stok']; ?> unit</strong>
                            </p>
                            
                            <?php if($alat['stok'] > 0): ?>
                                <a href="proses_pinjam.php?id=<?php echo $alat['id_alat']; ?>" 
                                   class="btn btn-primary w-100 mt-2" 
                                   onclick="return confirm('Ajukan peminjaman untuk alat ini?')">
                                   <i class="bi bi-send"></i> Ajukan Pinjam
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 mt-2" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <h4 class="mb-4 fw-bold"><i class="bi bi-clock-history"></i> Status Pinjaman</h4>
            <?php if(mysqli_num_rows($query_status) == 0): ?>
                <div class="alert alert-info small">Belum ada riwayat peminjaman.</div>
            <?php endif; ?>

            <?php while($st = mysqli_fetch_assoc($query_status)) : ?>
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 fw-bold small"><?php echo $st['nama_alat']; ?></h6>
                        <?php 
                            $bg = "bg-warning text-dark"; // pending
                            if($st['status_pinjam'] == 'disetujui') $bg = "bg-success";
                            if($st['status_pinjam'] == 'kembali') $bg = "bg-primary";
                        ?>
                        <span class="badge <?php echo $bg; ?> status-badge"><?php echo strtoupper($st['status_pinjam']); ?></span>
                    </div>
                    <p class="text-muted mb-2" style="font-size: 0.8rem;">
                        <?php echo date('d M Y, H:i', strtotime($st['tgl_pinjam'])); ?>
                    </p>
                    
                    <?php if($st['status_pinjam'] == 'disetujui'): ?>
                        <a href="cetak_surat.php?id=<?php echo $st['id_pinjam']; ?>" class="btn btn-outline-dark btn-sm w-100">
                            <i class="bi bi-printer"></i> Cetak Surat Izin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>