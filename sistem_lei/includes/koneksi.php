<?php
// Pengaturan Database
$host = "localhost";
$user = "root";     // Default XAMPP
$pass = "";         // Default XAMPP (kosong)
$db   = "db_peminjaman_lei";

// Melakukan koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
//if (!$conn) {
    // Jika gagal, tampilkan pesan error yang spesifik
  //  die("Gagal terhubung ke database LEI: " . mysqli_connect_error());
//}

// Set zona waktu (Opsional, agar waktu pinjam sesuai waktu lokal Indonesia)
date_default_timezone_set('Asia/Jakarta');

// Aktifkan laporan error untuk memudahkan debugging saat coding
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>