<?php
include 'includes/koneksi.php';
session_start();

// Proteksi Asisten
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'asisten') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT t.*, u.nama_lengkap, a.nama_alat, asisten.nama_lengkap AS nama_asisten
        FROM transaksi t
        JOIN users u ON t.id_user = u.id_user
        JOIN alat a ON t.id_alat = a.id_alat
        LEFT JOIN users asisten ON t.id_asisten = asisten.id_user
        ORDER BY t.tgl_pinjam DESC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Peminjaman LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS untuk tampilan layar */
        body { background-color: #f8f9fa; }
        .kop-surat { display: none; } /* Sembunyikan kop di browser */

        /* CSS KHUSUS CETAK */
        @media print {
            .no-print { display: none; } /* Sembunyikan tombol & navbar saat print */
            .kop-surat { display: block; border-bottom: 3px double #000; margin-bottom: 20px; text-align: center; }
            body { background-color: white; padding: 0; }
            .card { border: none !important; box-shadow: none !important; }
            table { width: 100% !important; border: 1px solid black !important; }
            th, td { border: 1px solid black !important; padding: 5px !important; font-size: 12px; }
            .badge { color: black !important; border: 1px solid black; background: none !important; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 no-print">
    <div class="container">
        <span class="navbar-brand">REKAP DATA LEI</span>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm me-2">🖨️ Cetak Rekap</button>
            <a href="dashboard_asisten.php" class="btn btn-outline-light btn-sm">Kembali</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="kop-surat">
        <h3>LAPORAN REKAPITULASI PEMINJAMAN ALAT</h3>
        <h4>Laboratorium Elektronika dan Instrumentasi (LEI)</h4>
        <p>Dicetak pada: <?php echo date('d-m-Y H:i'); ?></p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Waktu</th>
                        <th>Mahasiswa</th>
                        <th>Alat</th>
                        <th>Status</th>
                        <th>Asisten</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?php echo date('d/m/y H:i', strtotime($row['tgl_pinjam'])); ?></td>
                        <td><strong><?php echo $row['nama_lengkap']; ?></strong></td>
                        <td><?php echo $row['nama_alat']; ?></td>
                        <td>
                            <span class="badge <?php echo ($row['status_pinjam'] == 'disetujui') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                <?php echo strtoupper($row['status_pinjam']); ?>
                            </span>
                        </td>
                        <td><small><?php echo $row['nama_asisten'] ?? '-'; ?></small></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>