<?php
session_start();
include 'db.php';

// Tentukan jumlah data per halaman
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Ambil total jumlah data dari database
$total = $pdo->query("SELECT COUNT(*) FROM data_kelass")->fetchColumn();

// Ambil data untuk halaman saat ini
$stmt = $pdo->prepare("SELECT * FROM data_kelass LIMIT :start, :perPage");
$stmt->bindParam(':start', $start, PDO::PARAM_INT);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$data_kelass = $stmt->fetchAll();

// Hitung total halaman
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="Asli.css">
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Data Kelas</h1>
    <ul>
    <li><a href="dashboard_user.php">Dashboard</a></li>
        <li><a href="home_user.php">Berita</a></li>
        <li><a href="data_mahasiswa_user.php">Data Mahasiswa</a></li>
        <li><a href="jadwal_user.php">Jadwal Kuliah</a></li>
        <li><a href="contact_user.php">Contact Us</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Container for table -->
<div class="container">
    <h2>Daftar Mahasiswa Informatika 2023 B</h2>
    <?php if (count($data_kelass) > 0): ?>
        
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Npm</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Jenis Kelamin</th>
                </tr>
            </thead>
           
            <tbody>
    <?php foreach ($data_kelass as $data): ?>
        <tr>
            <td>
                <?php if (!empty($data['Foto'])): ?>
                    <img src="Pergambaran/<?= htmlspecialchars($data['Foto']) ?>" alt="Foto Mahasiswa" style="width:150px; height:150px;">
                <?php else: ?>
                    <img src="Pergambaran/default.png" alt="Foto Default" style="width: 50px; height: 50px;">
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($data['NPM']) ?></td>
            <td><?= htmlspecialchars($data['Nama']) ?></td>
            <td><?= htmlspecialchars($data['Alamat']) ?></td>
            <td><?= htmlspecialchars($data['Jenis_Kelamin']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

        </table>
     <?php else: ?>
        <p>Tidak ada data yang tersedia.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <div class="pagination">
    <!-- Link untuk halaman sebelumnya -->
    <a href="?page=<?= max(1, $page - 1) ?>" class="<?= $page == 1 ? 'disabled' : '' ?>">Prev</a>
    
    <!-- Tampilan informasi halaman saat ini -->
    <span>Page <?= $page ?> of <?= $totalPages ?></span>
    
    <!-- Link untuk halaman berikutnya -->
    <a href="?page=<?= min($totalPages, $page + 1) ?>" class="<?= $page == $totalPages ? 'disabled' : '' ?>">Next</a>

    <!-- Input halaman langsung -->
    <span style="margin-left: 20px;">
        <label for="page">Go to page:</label>
        <input type="number" id="page" name="page" value="<?= $page ?>" min="1" max="<?= $totalPages ?>" style="width: 60px;">
    </span>
    </div>

    <script>
    // Ambil input element
    const pageInput = document.getElementById('page');

    // Fungsi untuk mengarahkan pengguna ke halaman tertentu saat menekan Enter
    pageInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            const page = parseInt(this.value); // Ambil nilai yang diinput pengguna

            // Pastikan halaman berada dalam rentang yang valid
            if (page >= 1 && page <= <?= $totalPages ?>) {
                window.location.href = "?page=" + page; // Redirect ke halaman yang diinginkan
            } else {
                alert('Please enter a valid page number between 1 and <?= $totalPages ?>.');
            }
        }
    });
    </script>

</div>

</body>
</html>
