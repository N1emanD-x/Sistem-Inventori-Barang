<?php
/* =============================================
   FILE: barang/edit.php
   FUNGSI: Form edit data barang
============================================= */
include '../config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id       = $_GET['id'];
$query    = mysqli_query($conn, "SELECT * FROM barang WHERE id='$id'");
$data     = mysqli_fetch_assoc($query);
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = trim($_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $stok        = $_POST['stok'];
    $harga       = $_POST['harga'];

    $update = "UPDATE barang SET nama_barang='$nama_barang', id_kategori='$id_kategori',
               stok='$stok', harga='$harga' WHERE id='$id'";
    if (mysqli_query($conn, $update)) {
        header("Location: index.php?success=edit");
        exit();
    } else {
        $error = "Gagal memperbarui barang!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang — Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #111315; font-family: 'Segoe UI', sans-serif; color: #f0f0f0; }
        #sidebar { width: 220px; min-height: 100vh; background-color: #1a1d21; border-right: 1px solid #2a2d32; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 24px 20px; font-size: 0.95rem; font-weight: 600; color: #f0f0f0; border-bottom: 1px solid #2a2d32; }
        .sidebar-brand span { color: #6c757d; font-weight: 400; font-size: 0.75rem; display: block; margin-top: 2px; }
        .sidebar-nav { flex: 1; padding: 12px 0; }
        .sidebar-nav a { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: #6c757d; text-decoration: none; font-size: 0.88rem; transition: all 0.15s; }
        .sidebar-nav a:hover { color: #f0f0f0; background-color: #22262b; }
        .sidebar-nav a.active { color: #f0f0f0; background-color: #22262b; border-left: 2px solid #f0f0f0; }
        .sidebar-footer { padding: 16px 0; border-top: 1px solid #2a2d32; }
        .sidebar-footer a { display: flex; align-items: center; gap: 10px; padding: 10px 20px; color: #6c757d; text-decoration: none; font-size: 0.88rem; }
        .sidebar-footer a:hover { color: #f87171; }
        #main { margin-left: 220px; }
        #topbar { background-color: #1a1d21; border-bottom: 1px solid #2a2d32; padding: 14px 28px; display: flex; justify-content: space-between; align-items: center; }
        .page-title { font-size: 0.95rem; font-weight: 600; color: #f0f0f0; }
        .user-info { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: #adb5bd; }
        .badge-status { font-size: 0.72rem; padding: 3px 8px; border-radius: 4px; font-weight: 500; }
        .badge-admin { background: #2a2020; color: #f87171; }
        .badge-user  { background: #1a2030; color: #93c5fd; }
        .btn-logout { background: transparent; border: 1px solid #2a2d32; color: #6c757d; border-radius: 6px; padding: 5px 12px; font-size: 0.82rem; text-decoration: none; transition: all 0.15s; }
        .btn-logout:hover { border-color: #f87171; color: #f87171; }
        .content { padding: 28px; }
        .form-card { background-color: #1a1d21; border: 1px solid #2a2d32; border-radius: 10px; padding: 24px; max-width: 520px; }
        .form-card-title { font-size: 0.88rem; font-weight: 600; color: #f0f0f0; margin-bottom: 20px; }
        .form-label { color: #adb5bd; font-size: 0.82rem; margin-bottom: 6px; }
        .form-control, .form-select { background-color: #111315; border: 1px solid #2a2d32; color: #f0f0f0; border-radius: 8px; padding: 10px 14px; font-size: 0.88rem; width: 100%; }
        .form-control:focus, .form-select:focus { background-color: #111315; border-color: #495057; color: #f0f0f0; box-shadow: none; outline: none; }
        .form-select option { background-color: #1a1d21; color: #f0f0f0; }
        .btn-simpan { background: #f0f0f0; color: #111315; border: none; border-radius: 6px; padding: 8px 20px; font-size: 0.85rem; font-weight: 600; transition: background 0.15s; cursor: pointer; }
        .btn-simpan:hover { background: #d0d0d0; }
        .btn-batal { background: transparent; color: #6c757d; border: 1px solid #2a2d32; border-radius: 6px; padding: 8px 20px; font-size: 0.85rem; text-decoration: none; transition: all 0.15s; }
        .btn-batal:hover { color: #adb5bd; border-color: #495057; }
        .alert-dark-error { background: #2a1a1a; border: 1px solid #5c2a2a; color: #f87171; border-radius: 8px; padding: 10px 14px; font-size: 0.85rem; margin-bottom: 16px; }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="sidebar-brand">Inventori <span>Sistem Manajemen Barang</span></div>
    <div class="sidebar-nav">
        <a href="../dashboard.php"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="../barang/index.php" class="active"><i class="bi bi-box"></i> Barang</a>
        <a href="../kategori/index.php"><i class="bi bi-tag"></i> Kategori</a>
    </div>
    <div class="sidebar-footer">
        <a href="../logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>
</div>

<div id="main">
    <div id="topbar">
        <div class="page-title">Edit Barang</div>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <?= $_SESSION['username'] ?>
            <span class="badge-status <?= $_SESSION['status'] == 'Admin' ? 'badge-admin' : 'badge-user' ?>">
                <?= $_SESSION['status'] ?>
            </span>
            <a href="../logout.php" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
        </div>
    </div>

    <div class="content">
        <div class="form-card">
            <div class="form-card-title">Form Edit Barang</div>

            <?php if (isset($error)): ?>
                <div class="alert-dark-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">

                <!-- Input Nama Barang (terisi data lama) -->
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control"
                           value="<?= $data['nama_barang'] ?>" required>
                </div>

                <!-- Dropdown Kategori (selected sesuai data lama) -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php while ($kat = mysqli_fetch_assoc($kategori)): ?>
                            <option value="<?= $kat['id'] ?>"
                                <?= $kat['id'] == $data['id_kategori'] ? 'selected' : '' ?>>
                                <?= $kat['nama_kategori'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Input Stok (terisi data lama) -->
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control"
                           value="<?= $data['stok'] ?>" min="0" required>
                </div>

                <!-- Input Harga (terisi data lama) -->
                <div class="mb-4">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control"
                           value="<?= $data['harga'] ?>" min="0" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-simpan">Update</button>
                    <a href="index.php" class="btn-batal">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>