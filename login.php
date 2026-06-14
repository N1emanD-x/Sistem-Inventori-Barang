<?php
/* FILE: login.php
   FUNGSI: Halaman login pengguna
   - Form login dengan username, password, status
   - Validasi ke database
   - Redirect ke dashboard jika berhasil */
include 'config.php';

/* --- PROSES LOGIN --- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $status   = $_POST['status'];

    $query  = "SELECT * FROM users WHERE username='$username' AND status='$status'";
    $result = mysqli_query($conn, $query);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['status']   = $user['status'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username, password, atau status salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- HEAD: Meta, Title, CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* === DARK MODE BASE === */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #111315;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        /* === LOGIN CARD === */
        .login-box {
            background: #1a1d21;
            border: 1px solid #2a2d32;
            border-radius: 12px;
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
        }

        /* === JUDUL === */
        .login-box h5 {
            color: #f0f0f0;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .login-box p {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 28px;
        }

        /* === LABEL === */
        .form-label {
            color: #adb5bd;
            font-size: 0.82rem;
            margin-bottom: 6px;
        }

        /* === INPUT === */
        .form-control, .form-select {
            background-color: #111315;
            border: 1px solid #2a2d32;
            color: #f0f0f0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: #111315;
            border-color: #495057;
            color: #f0f0f0;
            box-shadow: none;
        }
        .form-select option {
            background-color: #1a1d21;
            color: #f0f0f0;
        }

        /* === INPUT GROUP (show/hide password) === */
        .input-group .form-control {
            border-right: none;
        }
        .input-group .btn-eye {
            background-color: #111315;
            border: 1px solid #2a2d32;
            border-left: none;
            color: #6c757d;
            border-radius: 0 8px 8px 0;
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .input-group .btn-eye:hover { color: #adb5bd; }

        /* === TOMBOL LOGIN === */
        .btn-login {
            background-color: #f0f0f0;
            color: #111315;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-login:hover { background-color: #d0d0d0; }

        /* === TOMBOL RESET === */
        .btn-reset {
            background-color: transparent;
            color: #6c757d;
            border: 1px solid #2a2d32;
            border-radius: 8px;
            padding: 10px;
            font-size: 0.9rem;
            width: 100%;
        }
        .btn-reset:hover { color: #adb5bd; border-color: #495057; }

        /* === ALERT ERROR === */
        .alert-dark-error {
            background: #2a1a1a;
            border: 1px solid #5c2a2a;
            color: #f87171;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        /* === LINK === */
        .login-box a {
            color: #adb5bd;
            font-size: 0.82rem;
            text-decoration: none;
        }
        .login-box a:hover { color: #f0f0f0; }

        /* === DIVIDER === */
        .divider {
            border-color: #2a2d32;
            margin: 24px 0;
        }
    </style>
</head>
<body>

    <!-- BODY: Form Login -->
    <div class="login-box">

        <!-- Judul -->
        <h5>Masuk ke Akun</h5>
        <p>Sistem Manajemen Inventori Barang</p>

        <!-- Pesan Error -->
        <?php if (isset($error)): ?>
            <div class="alert-dark-error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Form Login -->
        <form method="POST">

            <!-- Input Username -->
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Masukkan username" required>
            </div>

            <!-- Input Password + toggle show/hide -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                           class="form-control" placeholder="Masukkan password" required>
                    <button type="button" class="btn-eye" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Dropdown Status -->
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Pilih status</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn-login">Login</button>
                <button type="reset" class="btn-reset">Reset</button>
            </div>

        </form>

        <hr class="divider">

        <!-- Link ke Register -->
        <div class="text-center">
            <a href="register.php">Belum punya akun? Daftar disini</a>
        </div>

    </div>

    <!-- SCRIPT: Bootstrap JS + Toggle Password -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type      = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pwd.type      = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>

</body>
</html>