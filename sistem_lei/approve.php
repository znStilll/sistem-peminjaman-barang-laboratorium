<?php
include 'includes/koneksi.php';
session_start();

// Pastikan yang melakukan approval adalah asisten
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'asisten') {
    die("Akses Ditolak!");
}

if (isset($_GET['id']) && isset($_GET['id_alat'])) {
    $id_pinjam = $_GET['id'];
    $id_alat   = $_GET['id_alat'];
    $id_asisten = $_SESSION['id_user']; // Mencatat siapa asisten yang menyetujui

    // 1. Update status transaksi menjadi 'disetujui'
    $sql_update_transaksi = "UPDATE transaksi SET 
                             status_pinjam = 'disetujui', 
                             id_asisten = '$id_asisten' 
                             WHERE id_pinjam = '$id_pinjam'";

    // 2. Kurangi stok alat di tabel alat secara otomatis
    $sql_update_stok = "UPDATE alat SET stok = stok - 1 WHERE id_alat = '$id_alat'";

    // Eksekusi query
    if (mysqli_query($conn, $sql_update_transaksi) && mysqli_query($conn, $sql_update_stok)) {
        // 3. Jika stok mencapai 0, ubah status alat menjadi 'kosong'
        mysqli_query($conn, "UPDATE alat SET status = 'kosong' WHERE id_alat = '$id_alat' AND stok <= 0");

        echo "<script>
                alert('Peminjaman berhasil disetujui! Stok alat telah berkurang.');
                window.location='dashboard_asisten.php';
              </script>";
    } else {
        echo "Gagal memproses approval: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard_asisten.php");
}
?>