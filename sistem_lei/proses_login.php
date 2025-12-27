<?php
include 'includes/koneksi.php';
session_start();

if (isset($_POST['login'])) {
    // Perbaikan: hapus kata 'with' pada nama fungsi
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Cek password langsung (asumsi belum di-hash)
        if ($pass == $data['password']) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama']    = $data['nama_lengkap'];
            $_SESSION['role']    = $data['role'];

            if ($data['role'] == 'asisten') {
                header("Location: dashboard_asisten.php");
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: login.php?pesan=gagal");
        }
    } else {
        header("Location: login.php?pesan=gagal");
    }
}
?>