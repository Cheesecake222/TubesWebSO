<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah ada di sini

// Menangani form submit
$pesan_terkirim = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Validasi input
    if (!empty($nama) && !empty($email) && !empty($pesan)) {
        // Menyimpan pesan ke database
        $query = "INSERT INTO contact_us (nama, email, pesan) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $nama, $email, $pesan);

        if ($stmt->execute()) {
            $pesan_terkirim = "<p class='success'>Pesan Anda berhasil dikirim!</p>";
        } else {
            $pesan_terkirim = "<p class='error'>Terjadi kesalahan saat mengirim pesan.</p>";
        }

        $stmt->close();
    } else {
        $pesan_terkirim = "<p class='error'>Semua kolom harus diisi!</p>";
    }
}

// Menangani penghapusan pesan
if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    // Hapus pesan berdasarkan ID
    $deleteQuery = "DELETE FROM contact_us WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('i', $hapus_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pesan berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan saat menghapus pesan.";
    }

    $stmt->close();
}

// Ambil semua pesan yang sudah ada
$query = "SELECT * FROM contact_us ORDER BY tanggal DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="Asli.css">
    <style>
        /* Styling untuk tombol hapus */
.message-item a {
    color: red;
    text-decoration: none;
    font-weight: bold;
}

.message-item a:hover {
    color: darkred;
}
/* Styling untuk pesan yang baru dikirim */
.messages {
    margin-top: 20px;
}

.message-item {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 15px;
}

.message-item p {
    margin: 5px 0;
}

.message-item hr {
    border: 0;
    border-top: 1px solid #ccc;
}

/* Styling untuk pesan konfirmasi */
form p {
    font-weight: bold;
    color: green;
}

    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Contact Us</h1>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="home.php">Berita</a></li>
        <li><a href="data_mahasiswa.php">Data Mahasiswa</a></li>
        <li><a href="jadwal.php">Jadwal Kuliah</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="container">
<main>
    <h2>Hubungi Kami</h2>

    <!-- Tampilkan pesan setelah submit -->
    <?php echo $pesan_terkirim; ?>

    <!-- Form Contact Us -->
    <form action="contact.php" method="POST">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" style="width: 1230px; height: 13px;" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" style="width: 1230px; height: 13px;" required>

        <label for="pesan">Pesan</label>
        <textarea id="pesan" name="pesan" rows="20"  style="width: 1245px; height: 116px;"  required></textarea>

        <button type="submit">Kirim Pesan</button>
    </form>

    <hr>

    <!-- Tampilkan Pesan yang Dikirim -->
    <h3>Pesan yang Dikirim:</h3>
    <div class="messages">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="message-item">
                <p><strong>Nama:</strong> <?php echo $row['nama']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p><strong>Pesan:</strong> <?php echo nl2br($row['pesan']); ?></p>
                <p><em>Ditulis pada: <?php echo $row['tanggal']; ?></em></p>

                <!-- Tombol Hapus Pesan (Hanya Admin yang Bisa Melihat) -->
                
                <a href="contact.php?hapus_id=<?php echo $row['id']; ?>" 
                 onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">Hapus</a>
                
                
                <hr>
            </div>
        <?php endwhile; ?>
    </div>

</main>
</div>

</body>
</html>
