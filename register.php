<?php
/* FILE: register.php
   FUNGSI: Halaman registrasi pengguna baru
   - Menampilkan form register (username, password, status)
   - Menyimpan data ke database
   - Tombol register TIDAK memiliki aksi (sesuai soal) */
include 'config.php';

/*  PROSES REGISTER (jalan saat form di-submit)  */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil input dari form
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $status   = $_POST['status'];

    // Cek apakah username sudah ada di database
    $cek   = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan, coba yang lain!";
    } else {
        // Simpan user baru ke database
        $query = "INSERT INTO users (username, password, status) 
                  VALUES ('$username', '$password', '$status')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Registrasi gagal, coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- HEAD: Meta, Title, dan CSS Bootstrap -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Inventori</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* --- Custom Style untuk halaman register --- */
        body {
            background-color: #111315;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .btn-register {
            background: #111315;
            color: white;
        }
        .btn-register:hover {
            background: #111315;
            color: white;
        }
    </style>
</head>
<body>

    <!-- BODY: Form Register -->
    <div class="card p-4" style="width: 400px;">

        <!-- Icon & Judul -->
        <div class="text-center mb-4">
            <i class="bi bi-person-circle" style="font-size: 3rem; color: #111315;"></i>
            <h4 class="mt-2 fw-bold">REGISTER</h4>
        </div>

        <!-- Pesan Error -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Pesan Sukses -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <!-- Form Register -->
        <!-- NOTE: Sesuai soal, tombol Register tidak memiliki aksi apapun -->
        <form>

            <!-- Input Username -->
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control"
                           placeholder="Masukkan username" required>
                </div>
            </div>

            <!-- Input Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password"
                           class="form-control" placeholder="Masukkan password" required>
                    <!-- Tombol show/hide password -->
                    <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword()">
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

            <!-- Tombol Register & Reset -->
            <!-- Tombol register type="button" agar tidak ada aksi submit (sesuai soal) -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-register w-100">Register</button>
                <button type="reset" class="btn btn-outline-secondary w-100">Reset</button>
            </div>

        </form>

        <!-- Link kembali ke halaman Login -->
        <div class="text-center mt-3">
            <small>Sudah punya akun?
                <a href="login.php">Masuk disini</a>
            </small>
        </div>

    </div>

    <!-- SCRIPT: Bootstrap JS & Toggle Password -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* Fungsi show/hide password */
        function togglePassword() {
            const pwd     = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type          = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                pwd.type          = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        }
    </script>

</body>
</html>