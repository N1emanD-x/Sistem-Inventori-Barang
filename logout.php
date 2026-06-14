<?php
/* FILE: logout.php
   FUNGSI: Menghapus session dan redirect ke login */
include 'config.php';

// Hapus semua data session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>