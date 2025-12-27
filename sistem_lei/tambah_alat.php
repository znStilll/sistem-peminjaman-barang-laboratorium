<?php
include 'includes/koneksi.php';
session_start();

// Keamanan: Hanya asisten yang boleh menambah alat
if ($_SESSION['role'] != 'asisten') {
    echo "<script>alert('Akses Ditolak! Hanya Asisten yang boleh menambah alat.'); window.location='index.php';</script>";
    exit();
}

if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama_alat'];
    $stok   = $_POST['stok'];
    $lokasi = $_POST['lokasi'];

    $query = mysqli_query($conn, "INSERT INTO alat (nama_alat, stok, lokasi, status) 
                                  VALUES ('$nama', '$stok', '$lokasi', 'tersedia')");

    if ($query) {
        echo "<script>alert('Alat Berhasil Ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "Gagal menambah alat: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Alat - LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Inventaris Alat LEI</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Signal Generator" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Stok</label>
                        <input type="number" name="stok" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Rak/Laci</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lemari C-3" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" name="simpan" class="btn btn-success">Simpan Alat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>