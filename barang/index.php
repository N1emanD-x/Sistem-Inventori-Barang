<?php
/* =============================================
   FILE: barang/index.php
   FUNGSI: Menampilkan daftar semua barang
============================================= */
include '../config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

/* --- AMBIL DATA BARANG + NAMA KATEGORI (JOIN) --- */
$query  = "SELECT b.*, k.nama_kategori
           FROM barang b
           LEFT JOIN kategori k ON b.id_kategori = k.id
           ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang — Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #111315; font-family: 'Segoe UI', sans-serif; color: #f0f0f0; }
        #sidebar {
            width: 220px; min-height: 100vh; background-color: #1a1d21;
            border-right: 1px solid #2a2d32; position: fixed; top: 0; left: 0;
            display: flex; flex-direction: column;
        }
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
        .section-title { font-size: 0.78rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 14px; }
        .table-card { background-color: #1a1d21; border: 1px solid #2a2d32; border-radius: 10px; overflow: hidden; }
        .table-card-header { padding: 16px 20px; border-bottom: 1px solid #2a2d32; display: flex; justify-content: space-between; align-items: center; }
        .table-card-header span { font-size: 0.88rem; font-weight: 600; color: #f0f0f0; }
        .btn-tambah { background: #f0f0f0; color: #111315; border: none; border-radius: 6px; padding: 6px 14px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: background 0.15s; }
        .btn-tambah:hover { background: #d0d0d0; color: #111315; }
        .table { margin: 0; }
        .table thead th { background-color: #1a1d21; border-bottom: 1px solid #2a2d32; color: #6c757d; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px 20px; }
        .table tbody td { background-color: #1a1d21; border-bottom: 1px solid #22262b; color: #adb5bd; font-size: 0.88rem; padding: 12px 20px; vertical-align: middle; }
        .table tbody tr:hover td { background-color: #22262b; }
        .table tbody tr:last-child td { border-bottom: none; }
        .badge-stok-ok  { background: #1a2030; color: #93c5fd; font-size: 0.75rem; padding: 3px 8px; border-radius: 4px; }
        .badge-stok-low { background: #2a2020; color: #f87171; font-size: 0.75rem; padding: 3px 8px; border-radius: 4px; }
        .badge-kat { background: #1a2a1a; color: #86efac; font-size: 0.75rem; padding: 3px 8px; border-radius: 4px; }
        .btn-edit { background: transparent; border: 1px solid #2a2d32; color: #adb5bd; border-radius: 6px; padding: 4px 10px; font-size: 0.78rem; text-decoration: none; transition: all 0.15s; margin-right: 4px; }
        .btn-edit:hover { border-color: #f59e0b; color: #f59e0b; }
        .btn-hapus { background: transparent; border: 1px solid #2a2d32; color: #adb5bd; border-radius: 6px; padding: 4px 10px; font-size: 0.78rem; text-decoration: none; transition: all 0.15s; }
        .btn-hapus:hover { border-color: #f87171; color: #f87171; }
        .alert-success-dark { background: #1a2a1a; border: 1px solid #2a5c2a; color: #86efac; border-radius: 8px; padding: 10px 14px; font-size: 0.85rem; margin-bottom: 20px; }
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
        <div class="page-title">Barang</div>
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

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success-dark">
                <?php
                    if ($_GET['success'] == 'tambah') echo '✓ Barang berhasil ditambahkan.';
                    if ($_GET['success'] == 'edit')   echo '✓ Barang berhasil diperbarui.';
                    if ($_GET['success'] == 'hapus')  echo '✓ Barang berhasil dihapus.';
                ?>
            </div>
        <?php endif; ?>

        <div class="section-title">Data Barang</div>

        <div class="table-card">
            <div class="table-card-header">
                <span>Daftar Barang</span>
                <a href="tambah.php" class="btn-tambah">+ Tambah</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="color:#f0f0f0;"><?= $row['nama_barang'] ?></td>
                        <td>
                            <!-- Badge nama kategori -->
                            <span class="badge-kat">
                                <?= $row['nama_kategori'] ?? 'Tanpa Kategori' ?>
                            </span>
                        </td>
                        <td>
                            <!-- Badge stok merah jika < 5 -->
                            <span class="<?= $row['stok'] < 5 ? 'badge-stok-low' : 'badge-stok-ok' ?>">
                                <?= $row['stok'] ?>
                            </span>
                        </td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-hapus"
                               onclick="return confirm('Hapus barang ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($result) == 0): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:#6c757d; padding:32px;">
                            Belum ada data barang
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>