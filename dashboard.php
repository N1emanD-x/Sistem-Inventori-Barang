<?php
/* FILE: dashboard.php
   FUNGSI: Halaman utama setelah login
   - Proteksi SESSION
   - Statistik data real-time dari database
   - Navigasi ke CRUD Barang & Kategori
   - Tombol Logout */
include 'config.php';

/*  PROTEKSI HALAMAN  */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

/*  AMBIL DATA STATISTIK  */
$total_barang   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM barang"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori"))['total'];
$total_user     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_stok     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM barang"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /*  BASE  */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: #111315;
            font-family: 'Segoe UI', sans-serif;
            color: #f0f0f0;
        }

        /*  SIDEBAR  */
        #sidebar {
            width: 220px;
            min-height: 100vh;
            background-color: #1a1d21;
            border-right: 1px solid #2a2d32;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 24px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #f0f0f0;
            border-bottom: 1px solid #2a2d32;
            letter-spacing: 0.3px;
        }
        .sidebar-brand span {
            color: #6c757d;
            font-weight: 400;
            font-size: 0.75rem;
            display: block;
            margin-top: 2px;
        }
        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.15s;
        }
        .sidebar-nav a:hover {
            color: #f0f0f0;
            background-color: #22262b;
        }
        .sidebar-nav a.active {
            color: #f0f0f0;
            background-color: #22262b;
            border-left: 2px solid #f0f0f0;
        }
        .sidebar-nav a i { font-size: 1rem; }

        /*  SIDEBAR FOOTER (logout)  */
        .sidebar-footer {
            padding: 16px 0;
            border-top: 1px solid #2a2d32;
        }
        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.15s;
        }
        .sidebar-footer a:hover { color: #f87171; }

        /*  MAIN CONTENT  */
        #main {
            margin-left: 220px;
            min-height: 100vh;
        }

        /*  TOPBAR  */
        #topbar {
            background-color: #1a1d21;
            border-bottom: 1px solid #2a2d32;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #topbar .page-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #f0f0f0;
        }
        #topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #adb5bd;
        }
        .badge-status {
            font-size: 0.72rem;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }
        .badge-admin { background: #2a2020; color: #f87171; }
        .badge-user  { background: #1a2030; color: #93c5fd; }

        /*  TOMBOL LOGOUT TOPBAR  */
        .btn-logout {
            background: transparent;
            border: 1px solid #2a2d32;
            color: #6c757d;
            border-radius: 6px;
            padding: 5px 12px;
            font-size: 0.82rem;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-logout:hover { border-color: #f87171; color: #f87171; }

        /*  KONTEN  */
        .content { padding: 28px; }

        /*  KARTU STATISTIK  */
        .stat-card {
            background-color: #1a1d21;
            border: 1px solid #2a2d32;
            border-radius: 10px;
            padding: 20px 22px;
        }
        .stat-card .stat-label {
            font-size: 0.78rem;
            color: #6c757d;
            margin-bottom: 8px;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f0f0f0;
            line-height: 1;
        }
        .stat-card .stat-icon {
            font-size: 1.4rem;
            color: #2a2d32;
        }

        /* SHORTCUT CARD */
        .shortcut-card {
            background-color: #1a1d21;
            border: 1px solid #2a2d32;
            border-radius: 10px;
            padding: 22px 24px;
            text-decoration: none;
            display: block;
            transition: border-color 0.15s;
        }
        .shortcut-card:hover { border-color: #495057; }
        .shortcut-card h6 {
            color: #f0f0f0;
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .shortcut-card p {
            color: #6c757d;
            font-size: 0.82rem;
            margin-bottom: 14px;
        }
        .shortcut-card .btn-go {
            font-size: 0.8rem;
            color: #adb5bd;
            border: 1px solid #2a2d32;
            border-radius: 6px;
            padding: 4px 12px;
            background: transparent;
            text-decoration: none;
            transition: all 0.15s;
        }
        .shortcut-card:hover .btn-go {
            border-color: #495057;
            color: #f0f0f0;
        }

        /* === SECTION TITLE === */
        .section-title {
            font-size: 0.78rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div id="sidebar">
    <div class="sidebar-brand">
        Inventori
        <span>Sistem Manajemen Barang</span>
    </div>
    <div class="sidebar-nav">
        <!-- Menu Dashboard -->
        <a href="dashboard.php" class="active">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <!-- Menu Barang -->
        <a href="barang/index.php">
            <i class="bi bi-box"></i> Barang
        </a>
        <!-- Menu Kategori -->
        <a href="kategori/index.php">
            <i class="bi bi-tag"></i> Kategori
        </a>
    </div>
    <!-- Logout di sidebar -->
    <div class="sidebar-footer">
        <a href="logout.php">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>

<!-- MAIN CONTENN -->
<div id="main">

    <!-- TOPBAR -->
    <div id="topbar">
        <div class="page-title">Dashboard</div>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <?= $_SESSION['username'] ?>
            <!-- Badge status Admin/User -->
            <span class="badge-status <?= $_SESSION['status'] == 'Admin' ? 'badge-admin' : 'badge-user' ?>">
                <?= $_SESSION['status'] ?>
            </span>
            <!-- Tombol logout topbar -->
            <a href="logout.php" class="btn-logout">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="content">

        <!-- Sambutan -->
        <div class="mb-4">
            <div style="font-size:1.05rem; font-weight:600; color:#f0f0f0;">
                Selamat datang, <?= $_SESSION['username'] ?>
            </div>
            <div style="font-size:0.83rem; color:#6c757d; margin-top:4px;">
                Berikut ringkasan data inventori saat ini.
            </div>
        </div>

        <!-- KARTU STATISTIK REAL-TIME -->
        <div class="section-title">Ringkasan Data</div>
        <div class="row g-3 mb-4">

            <!-- Total Barang -->
            <div class="col-md-3">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Barang</div>
                        <div class="stat-value"><?= $total_barang ?></div>
                    </div>
                    <i class="bi bi-box stat-icon"></i>
                </div>
            </div>

            <!-- Total Kategori -->
            <div class="col-md-3">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Kategori</div>
                        <div class="stat-value"><?= $total_kategori ?></div>
                    </div>
                    <i class="bi bi-tag stat-icon"></i>
                </div>
            </div>

            <!-- Total Stok -->
            <div class="col-md-3">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total Stok</div>
                        <div class="stat-value"><?= $total_stok ?? 0 ?></div>
                    </div>
                    <i class="bi bi-stack stat-icon"></i>
                </div>
            </div>

            <!-- Total User -->
            <div class="col-md-3">
                <div class="stat-card d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total User</div>
                        <div class="stat-value"><?= $total_user ?></div>
                    </div>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>

        </div>

        <!-- SHORTCUT KE CRUD -->
        <div class="section-title">Menu</div>
        <div class="row g-3">

            <!-- Shortcut Barang -->
            <div class="col-md-6">
                <a href="barang/index.php" class="shortcut-card">
                    <h6><i class="bi bi-box me-2"></i>Manajemen Barang</h6>
                    <p>Tambah, lihat, edit, dan hapus data barang inventori.</p>
                    <span class="btn-go">Kelola Barang →</span>
                </a>
            </div>

            <!-- Shortcut Kategori -->
            <div class="col-md-6">
                <a href="kategori/index.php" class="shortcut-card">
                    <h6><i class="bi bi-tag me-2"></i>Manajemen Kategori</h6>
                    <p>Tambah, lihat, edit, dan hapus data kategori barang.</p>
                    <span class="btn-go">Kelola Kategori →</span>
                </a>
            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>