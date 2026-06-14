<?php
/* =============================================
   FILE: barang/hapus.php
   FUNGSI: Menghapus data barang dari database
   - Ambil ID dari URL
   - Hapus data dari database
   - Redirect ke index dengan pesan sukses
============================================= */
include '../config.php';

/* --- PROTEKSI HALAMAN --- */
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

/* --- AMBIL ID & HAPUS DATA --- */
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");

/* --- Redirect ke index dengan pesan sukses --- */
header("Location: index.php?success=hapus");
exit();
?>