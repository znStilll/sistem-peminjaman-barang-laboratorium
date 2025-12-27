<?php
// 1. Sesuaikan jalur include karena file ini ada di luar folder includes
include 'includes/koneksi.php';
session_start();

// 2. Cek keamanan: Pastikan sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// 3. Ambil ID Pinjam dari URL
if (!isset($_GET['id'])) {
    echo "ID Peminjaman tidak ditemukan.";
    exit();
}

$id_pinjam = $_GET['id'];

// 4. Query lengkap untuk mengambil data Mahasiswa dan Alat sekaligus
$sql = "SELECT t.*, u.nama_lengkap, u.username AS nim, a.nama_alat, a.lokasi 
        FROM transaksi t
        JOIN users u ON t.id_user = u.id_user
        JOIN alat a ON t.id_alat = a.id_alat
        WHERE t.id_pinjam = '$id_pinjam' AND t.status_pinjam = 'disetujui'";

$query = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan atau belum disetujui
if (!$data) {
    echo "<script>alert('Surat hanya bisa dicetak jika peminjaman sudah disetujui asisten.'); window.close();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Peminjaman - <?php echo $data['nama_alat']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: white; font-family: "Times New Roman", serif; color: black; }
        .kop-surat { border-bottom: 4px double black; margin-bottom: 30px; padding-bottom: 10px; }
        .stempel { border: 2px solid #ccc; width: 150px; height: 80px; display: flex; align-items: center; justify-content: center; color: #eee; margin: 10px auto; }
        
        /* CSS khusus saat dicetak */
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .container { width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container mt-4 p-5">
    <div class="no-print mb-4 d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary btn-sm">← Kembali ke Dashboard</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">🖨️ Cetak ke PDF / Printer</button>
    </div>

    <div class="text-center kop-surat">
        <h3 class="mb-0 text-uppercase">Laboratorium Elektronika dan Instrumentasi (LEI)</h3>
        <p class="mb-0">Program Studi Teknik Elektro - Universitas Terpilih</p>
        <small>Sekretariat: Gedung Laboratorium Terpadu Lantai 2 | Email: lei@univ.ac.id</small>
    </div>

    <div class="content mt-5">
        <h4 class="text-center text-decoration-underline mb-5">SURAT IZIN PEMINJAMAN ALAT</h4>
        
        <p>Dengan ini menerangkan bahwa mahasiswa di bawah ini:</p>
        
        <table class="table table-borderless w-75 mx-auto my-4">
            <tr>
                <td width="30%">Nama Mahasiswa</td>
                <td>: <strong><?php echo $data['nama_lengkap']; ?></strong></td>
            </tr>
            <tr>
                <td>NIM / ID User</td>
                <td>: <?php echo $data['nim']; ?></td>
            </tr>
            <tr>
                <td>Alat yang Dipinjam</td>
                <td>: <?php echo $data['nama_alat']; ?></td>
            </tr>
            <tr>
                <td>Lokasi Penyimpanan</td>
                <td>: <?php echo $data['lokasi']; ?></td>
            </tr>
            <tr>
                <td>Tanggal Pinjam</td>
                <td>: <?php echo date('d F Y', strtotime($data['tgl_pinjam'])); ?></td>
            </tr>
        </table>

        <p class="mt-5">Telah diberikan izin untuk meminjam alat tersebut untuk keperluan praktikum/riset di lingkungan Laboratorium LEI. Mahasiswa bertanggung jawab penuh atas keutuhan dan kebersihan alat tersebut.</p>
        
        <p>Demikian surat izin ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-7"></div>
        <div class="col-5 text-center">
            <p>Pemeriksa,</p>
            <p class="mb-5">Asisten Laboran LEI</p>
            <br><br>
            <strong>( ........................................ )</strong>
            <p class="small text-muted">Dicetak otomatis oleh Sistem LEI</p>
        </div>
    </div>
</div>

</body>
</html>