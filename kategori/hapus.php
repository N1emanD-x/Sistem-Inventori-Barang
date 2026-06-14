<?php
/* =============================================
   FILE: kategori/hapus.php
   FUNGSI: Menghapus data kategori dari database
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
mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");

/* --- Redirect ke index dengan pesan sukses --- */
header("Location: index.php?success=hapus");
exit();
?>