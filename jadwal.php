<?php
include 'db.php';

// Handle Create Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $kode_matkul = $_POST['kode_matkul'];
    $mata_kuliah = $_POST['mata_kuliah'];
    $dosen = $_POST['dosen'];
    $hari = $_POST['hari'];
    $waktu = $_POST['waktu'];

    $stmt = $pdo->prepare("INSERT INTO jadwal_informatika (Kode_matkul, Mata_Kuliah, Dosen_Pengampu, Hari, Waktu) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$kode_matkul, $mata_kuliah, $dosen, $hari, $waktu]);

    header("Location: jadwal.php");
    exit();
}

// Handle Update Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $kode_matkul = $_POST['kode_matkul'];
    $mata_kuliah = $_POST['mata_kuliah'];
    $dosen = $_POST['dosen'];
    $hari = $_POST['hari'];
    $waktu = $_POST['waktu'];

    $stmt = $pdo->prepare("UPDATE jadwal_informatika SET Mata_Kuliah = ?, Dosen_Pengampu = ?, Hari = ?, Waktu = ? WHERE Kode_matkul = ?");
    $stmt->execute([$mata_kuliah, $dosen, $hari, $waktu, $kode_matkul]);

    header("Location: jadwal.php");
    exit();
}

// Handle Delete Request
if (isset($_GET['delete'])) {
    $kode_matkul = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM jadwal_informatika WHERE Kode_matkul = ?");
    $stmt->execute([$kode_matkul]);

    header("Location: jadwal.php");
    exit();
}

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
            <li><a href="home.php">Home</a></li>
            <li><a href="data_mahasiswa.php">Data Mahasiswa</a></li>
            <li><a href="jadwal.php">Jadwal Kuliah</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="container">
        <h2>Jadwal Informatika 2023</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th>Hari</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
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
                            <td>
                                <button class="edit-btn" 
                                    data-Kode_Matkul="<?= htmlspecialchars($row['Kode_Matkul']) ?>" 
                                    data-mata_kuliah="<?= htmlspecialchars($row['Mata_Kuliah']) ?>" 
                                    data-dosen="<?= htmlspecialchars($row['Dosen_Pengampu']) ?>" 
                                    data-hari="<?= htmlspecialchars($row['Hari']) ?>" 
                                    data-waktu="<?= htmlspecialchars($row['Waktu']) ?>">Edit</button>
                                <a href="jadwal.php?delete=<?= $row['Kode_Matkul'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol Tambah Jadwal -->
        <div style="text-align: center; margin: 20px 0;">
            <button id="add-btn" class="add-btn">Tambah Jadwal</button>
        </div>

        <!-- Modal for Tambah/Edit Jadwal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close-btn">&times;</span>
                <h2 id="modal-title">Tambah Jadwal</h2>
                <form id="editForm" method="POST" action="">
                    <label for="edit-kode_matkul">Kode Matakuliah:</label>
                    <input type="text" style="width: 515px; height: 13px;" name="kode_matkul" id="edit-kode_matkul" required>
                    <label for="edit-mata_kuliah">Mata Kuliah:</label>
                    <input type="text" style="width: 515px; height: 13px;" name="mata_kuliah" id="edit-mata_kuliah" required>
                    <label for="edit-dosen">Dosen Pengampu:</label>
                    <input type="text" style="width: 515px; height: 13px;" name="dosen" id="edit-dosen" required>
                    <label for="edit-hari">Hari:</label>
                    <select name="hari" id="edit-hari" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                    <label for="edit-waktu">Waktu:</label>
                    <input type="time" style="width: 515px; height: 13px;" name="waktu" id="edit-waktu" required>
                    <button type="submit" id="submit-btn" name="add">Tambah</button>
                </form>
            </div>
        </div>

        <script>
            // Get modal elements
            const modal = document.getElementById("editModal");
            const closeModal = document.querySelector(".close-btn");
            const editButtons = document.querySelectorAll(".edit-btn");
            const addButton = document.getElementById("add-btn");
            const formTitle = document.getElementById("modal-title");
            const submitButton = document.getElementById("submit-btn");

            // Open modal for editing
            editButtons.forEach(button => {
                button.addEventListener("click", () => {
                    const kodeMatkul = button.dataset.kode_matkul;
                    const mataKuliah = button.dataset.mata_kuliah;
                    const dosen = button.dataset.dosen;
                    const hari = button.dataset.hari;
                    const waktu = button.dataset.waktu;

                    // Update form fields with data
                    document.getElementById("edit-kode_matkul").value = kodeMatkul;
                    document.getElementById("edit-mata_kuliah").value = mataKuliah;
                    document.getElementById("edit-dosen").value = dosen;
                    document.getElementById("edit-hari").value = hari;
                    document.getElementById("edit-waktu").value = waktu;

                    // Update modal title and submit button text for editing
                    formTitle.textContent = "Edit Jadwal";
                    submitButton.textContent = "Simpan";
                    submitButton.setAttribute("name", "edit");

                    // Show modal
                    modal.style.display = "block";
                });
            });

            // Open modal for adding new data
            addButton.addEventListener("click", () => {
                // Clear form fields
                document.getElementById("edit-kode_matkul").value = "";
                document.getElementById("edit-mata_kuliah").value = "";
                document.getElementById("edit-dosen").value = "";
                document.getElementById("edit-hari").value = "Senin";
                document.getElementById("edit-waktu").value = "";

                // Update modal title and submit button text for adding
                formTitle.textContent = "Tambah Jadwal";
                submitButton.textContent = "Tambah";
                submitButton.setAttribute("name", "add");

                // Show modal
                modal.style.display = "block";
            });

            // Close modal
            closeModal.addEventListener("click", () => {
                modal.style.display = "none";
            });

            // Close modal when clicking outside
            window.addEventListener("click", event => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        </script>
    </div>
</body>
</html>
