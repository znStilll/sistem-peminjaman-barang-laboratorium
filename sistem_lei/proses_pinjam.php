<?php
// 1. Hubungkan ke database
include 'includes/koneksi.php';
session_start();

// 2. Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// 3. Tangkap ID Alat dari URL (Link yang diklik di index.php)
if (isset($_GET['id'])) {
    $id_alat = $_GET['id'];
    $id_user = $_SESSION['id_user']; // Diambil dari session login

    // 4. Masukkan data ke tabel transaksi dengan status 'pending'
    // id_pinjam akan terisi otomatis (Auto Increment)
    // tgl_pinjam akan terisi otomatis (Current Timestamp)
    $sql = "INSERT INTO transaksi (id_user, id_alat, status_pinjam) 
            VALUES ('$id_user', '$id_alat', 'pending')";
    
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, munculkan notifikasi dan balik ke index
        echo "<script>
                alert('Permintaan pinjam berhasil dikirim! Silakan tunggu konfirmasi asisten.');
                window.location='index.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal mengajukan pinjaman: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses file ini langsung tanpa klik tombol pinjam
    header("Location: index.php");
}
?>