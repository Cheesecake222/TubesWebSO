<?php
session_start();
include 'db.php'; // Koneksi ke database

// Mengambil berita dari database
$query = "SELECT * FROM home ORDER BY tanggal DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="Asli.css">
    <style>
        /* Gaya untuk kontainer berita */
        .berita-item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Dashboard</h1>
    <ul>
        <li><a href="dashboard_user.php">Dashboard</a></li>
        <li><a href="home_user.php">Berita</a></li>
        <li><a href="data_mahasiswa_user.php">Data Mahasiswa</a></li>
        <li><a href="jadwal_user.php">Jadwal Kuliah</a></li>
        <li><a href="contact_user.php">Contact Us</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<!-- Main Content -->
<main>
<div class="container">
    <!-- Slideshow -->
    <div class="slideshow-container">
    <div class="slide fade">
        <img src="pergambaran/Gambar 1.jpg" style="width: 600px; height: 400px;" alt="Gambar 1">
    </div>
    <div class="slide fade">
        <img src="pergambaran/Gambar 2.jpg" style="width: 600px; height: 400px;" alt="Gambar 2">
    </div>
    <div class="slide fade">
        <img src="pergambaran/Gambar 3.jpg" style="width: 600px; height: 400px;" alt="Gambar 3">
    </div>
    </div>

    <h2>Tentang Kami</h2>
    <h3>Selamat datang di website resmi Informatika Angkatan 2023, 
        platform yang dirancang untuk mempermudah komunikasi, kolaborasi, dan berbagi informasi antar anggota kelas. 
        Mari bersama-sama menciptakan lingkungan belajar yang produktif dan penuh semangat!</h3>
</div>
</main>
<style>
    /* Container untuk slideshow */
    .slideshow-container {
        position: relative;
        max-width: 50%; /* Atur lebar slideshow */
        margin: auto; /* Pusatkan container secara horizontal */
        overflow: hidden; /* Sembunyikan elemen di luar batas */
    }

    /* Gaya untuk semua slide */
    .slides {
        display: flex; /* Atur semua slide dalam satu baris */
        transition: transform 0.5s ease-in-out; /* Transisi geser */
    }

    /* Gambar dalam slide */
    .slide img {
        width: 100%;
        flex-shrink: 0; /* Hindari gambar menyusut */
        border-radius: 15px; /* Lekukan di setiap sudut */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
    }
    
    /* Pusatkan slideshow secara vertikal pada halaman */
    body {
        display: center; /* Gunakan flexbox untuk memusatkan konten */
        justify-content: center; /* Pusatkan secara horizontal */
        align-items: center; /* Pusatkan secara vertikal */
        min-height: 100vh; /* Pastikan body memiliki tinggi penuh layar */
        margin: 0; /* Hilangkan margin default */
        background-color: #f4f4f9; /* Tambahkan warna latar belakang */
    }
</style>

<script>
    let slideIndex = 0;

    function showSlides() {
        const slides = document.getElementsByClassName("slide");

        // Sembunyikan semua slide
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slideIndex++; // Naikkan index slide

        // Kembali ke slide pertama jika sudah mencapai akhir
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }

        // Tampilkan slide yang sesuai
        slides[slideIndex - 1].style.display = "block";

        // Ulangi setelah 4 detik
        setTimeout(showSlides, 4000);
    } 


    // Panggil fungsi slideshow saat halaman dimuat
    window.onload = showSlides;
</script>
</body>
</html>