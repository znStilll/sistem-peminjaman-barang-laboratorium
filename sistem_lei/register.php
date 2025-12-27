<?php
include 'includes/koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama     = $_POST['nama_lengkap'];
    $role     = $_POST['role'];

    // Perintah SQL untuk memasukkan data
    $query = mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role) 
                                  VALUES ('$username', '$password', '$nama', '$role')");

    if ($query) {
        echo "<script>alert('Registrasi Berhasil!'); window.location='login.php';</script>";
    } else {
        echo "Gagal mendaftar: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container bg-white p-4 shadow-sm rounded" style="max-width: 400px;">
        <h4 class="text-center mb-4">Daftar Akun Baru</h4>
        <form method="POST">
            <div class="mb-3">
                <label>NIM / ID User</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Daftar Sebagai</label>
                <select name="role" class="form-select">
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="asisten">Asisten Lab</option>
                </select>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">Daftar Sekarang</button>
            <p class="text-center mt-3 small">Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>