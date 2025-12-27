<?php
include 'includes/koneksi.php';
session_start();

// Jika sudah login, langsung lempar ke index
if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SISTEM LEI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 20px 25px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">Peminjaman Barang </h3>
                        <p class="text-muted">Laboratorium Elektronika & Instrumentasi</p>
                    </div>

                    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == "gagal") : ?>
                        <div class="alert alert-danger py-2 text-center" role="alert">
                            <small>Username atau Password Salah!</small>
                        </div>
                    <?php endif; ?>

                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username / NIM</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary fw-bold">MASUK</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>