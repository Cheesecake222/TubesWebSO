<?php
include 'db.php';
// Fetch Data from Database
// Fetch Data from Database
try {
    $stmt = $pdo->query("
        SELECT * 
        FROM jadwal_informatika
        ORDER BY 
            FIELD(Hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'), 
            Waktu
    ");
    $jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Informatika 2023</title>
    <link rel="stylesheet" href="Asli.css">
</head>
<body>
    <div class="navbar">
        <h1>Jadwal Kelas</h1>
        <ul>
        <li><a href="dashboard_user.php">Dashboard</a></li>
        <li><a href="home_user.php">Berita</a></li>
        <li><a href="data_mahasiswa_user.php">Data Mahasiswa</a></li>
        <li><a href="jadwal_user.php">Jadwal Kuliah</a></li>
        <li><a href="contact_user.php">Contact Us</a></li>
        <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <h2>Jadwal Informatika 2023 B</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th>Hari</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($jadwal): ?>
                    <?php foreach ($jadwal as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Kode_Matkul']) ?></td>
                            <td><?= htmlspecialchars($row['Mata_Kuliah']) ?></td>
                            <td><?= htmlspecialchars($row['Dosen_Pengampu']) ?></td>
                            <td><?= htmlspecialchars($row['Hari']) ?></td>
                            <td><?= htmlspecialchars($row['Waktu']) ?></td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
