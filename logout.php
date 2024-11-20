<?php
session_start();

// Menghapus semua sesi
session_unset();

// Menghancurkan sesi
session_destroy();

// Mengarahkan pengguna ke halaman login atau beranda
header("Location: index.php");
exit();
?>
