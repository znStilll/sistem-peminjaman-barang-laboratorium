<?php
include 'includes/koneksi.php';
session_start();

// Proteksi: Hanya asisten yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'asisten') {
    header("Location: index.php");
    exit();
}

// Ambil data peminjaman yang statusnya masih 'pending'
$query = mysqli_query($conn, "SELECT t.*, u.nama_lengkap, a.nama_alat 
                              FROM transaksi t
                              JOIN users u ON t.id_user = u.id_user
                              JOIN alat a ON t.id_alat = a.id_alat
                              WHERE t.status_pinjam = 'pending'
                              ORDER BY t.tgl_pinjam DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">ASISTEN LEI</a>
            <div class="navbar-nav ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm me-2">Lihat Katalog</a>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h4 class="mb-4">Daftar Persetujuan Peminjaman Alat</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu Request</th>
                            <th>Nama Mahasiswa</th>
                            <th>Alat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) == 0) : ?>
                            <tr><td colspan="4" class="text-center text-muted">Tidak ada permintaan pending saat ini.</td></tr>
                        <?php endif; ?>

                        <?php while($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><small><?php echo $row['tgl_pinjam']; ?></small></td>
                            <td><strong><?php echo $row['nama_lengkap']; ?></strong></td>
                            <td><?php echo $row['nama_alat']; ?></td>
                            <td class="text-center">
                                <a href="approve.php?id=<?php echo $row['id_pinjam']; ?>&id_alat=<?php echo $row['id_alat']; ?>" 
                                   class="btn btn-success btn-sm fw-bold" 
                                   onclick="return confirm('Apakah fisik alat sudah dicek dan siap dipinjam?')">
                                   ✔ Setujui (Approve)
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