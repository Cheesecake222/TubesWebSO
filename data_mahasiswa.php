<?php
session_start();
include 'db.php';

// Fungsi untuk menambah data
if (isset($_POST['add'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $foto = '';

    // Handling file upload for foto
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "Pergambaran/";
        $foto_name = time() . "_" . basename($_FILES['foto']['name']); // Tambahkan timestamp
        $target_file = $target_dir . $foto_name;

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $foto_name;
            } else {
                echo "Error: Gagal mengunggah file.";
            }
        } else {
            echo "Error: File harus berupa gambar (JPG, JPEG, PNG, GIF).";
        }
    }

    $stmt = $pdo->prepare("INSERT INTO data_kelass (NPM, Nama, Alamat, Jenis_Kelamin, Foto) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$npm, $nama, $alamat, $jenis_kelamin, $foto]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fungsi untuk menghapus data
if (isset($_GET['delete'])) {
    $npm = $_GET['delete'];

    // Hapus foto dari folder
    $stmt = $pdo->prepare("SELECT Foto FROM data_kelass WHERE NPM = ?");
    $stmt->execute([$npm]);
    $data = $stmt->fetch();
    if (!empty($data['Foto']) && file_exists("Pergambaran/" . $data['Foto'])) {
        unlink("Pergambaran/" . $data['Foto']);
    }

    // Hapus data dari database
    $stmt = $pdo->prepare("DELETE FROM data_kelass WHERE NPM = ?");
    $stmt->execute([$npm]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fungsi untuk mengupdate data
if (isset($_POST['update'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    // Ambil data lama dari database
    $stmt = $pdo->prepare("SELECT Foto FROM data_kelass WHERE NPM = ?");
    $stmt->execute([$npm]);
    $oldData = $stmt->fetch();
    $oldFoto = $oldData['Foto'];

    // Penanganan foto baru
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "Pergambaran/";
        $foto_name = time() . "_" . basename($_FILES['foto']['name']); // Nama unik untuk foto baru
        $target_file = $target_dir . $foto_name;

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $foto_name;

                // Hapus foto lama jika ada
                if (!empty($oldFoto) && file_exists($target_dir . $oldFoto)) {
                    unlink($target_dir . $oldFoto);
                }
            } else {
                echo "Error: Gagal mengunggah file.";
                $foto = $oldFoto; // Tetap gunakan foto lama jika upload gagal
            }
        } else {
            echo "Error: File harus berupa gambar (JPG, JPEG, PNG, GIF).";
            $foto = $oldFoto; // Tetap gunakan foto lama jika format tidak valid
        }
    } else {
        $foto = $oldFoto; // Jika tidak ada foto baru, gunakan foto lama
    }

    // Update data ke database
    $stmt = $pdo->prepare("UPDATE data_kelass SET Nama = ?, Alamat = ?, Jenis_Kelamin = ?, Foto = ? WHERE NPM = ?");
    $stmt->execute([$nama, $alamat, $jenis_kelamin, $foto, $npm]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

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
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="home.php">Berita</a></li>
        <li><a href="data_mahasiswa.php">Data Mahasiswa</a></li>
        <li><a href="jadwal.php">Jadwal Kuliah</a></li>
        <li><a href="contact.php">Contact Us</a></li>
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
                    <th>Aksi</th>
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
            <td>
                <!-- Tombol Edit -->
                <a href="#" class="editBtn" 
                   data-npm="<?= htmlspecialchars($data['NPM']) ?>" 
                   data-nama="<?= htmlspecialchars($data['Nama']) ?>" 
                   data-alamat="<?= htmlspecialchars($data['Alamat']) ?>" 
                   data-jenis-kelamin="<?= htmlspecialchars($data['Jenis_Kelamin']) ?>" 
                   data-foto="<?= htmlspecialchars($data['Foto']) ?>">Edit</a>
                <!-- Tombol Hapus -->
                <a href="?delete=<?= $data['NPM'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
            </td>
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


<!-- Modal for Edit -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Data Mahasiswa</h2>
        <form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" id="editNPM" name="npm">
    <label for="editNama">Nama:</label><br>
    <input type="text" id="editNama" style="width: 515px; height: 13px;" name="nama" required><br><br>
    <label for="editAlamat">Alamat:</label><br>
    <input type="text" id="editAlamat" style="width: 515px; height: 13px;" name="alamat" required><br><br>
    <label for="editJenisKelamin">Jenis Kelamin:</label><br>
    <select id="editJenisKelamin" name="jenis_kelamin" required>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select><br><br>
    <label for="foto">Foto Baru (Opsional):</label><br>
    <input type="file" id="foto" style="width: 515px; height: 13px;" name="foto" accept="image/*"><br><br>
    <button type="submit" name="update">Simpan Perubahan</button>
</form>

    </div>
</div>

<script>
    // Get the modal
var modal = document.getElementById("editModal");

// Get the close button
var closeBtn = document.getElementsByClassName("close")[0];

// Get all the edit buttons
var editButtons = document.querySelectorAll(".editBtn");

// Loop through each edit button to add event listener
editButtons.forEach(function(button) {
    button.onclick = function() {
        var npm = this.getAttribute("data-npm");
        var nama = this.getAttribute("data-nama");
        var alamat = this.getAttribute("data-alamat");
        var jenisKelamin = this.getAttribute("data-jenis-kelamin");

        // Set the values inside the modal form
        document.getElementById("editNPM").value = npm;
        document.getElementById("editNama").value = nama;
        document.getElementById("editAlamat").value = alamat;
        document.getElementById("editJenisKelamin").value = jenisKelamin;

        // Show the modal
        modal.style.display = "block";
    }
});

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<!-- Add Button -->
<div style="text-align: center; margin: 20px 0;">
    <button id="add-btn" class="add-btn">Tambah Mahasiswa</button>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Tambah Data Mahasiswa</h2>
        <form method="POST" action="" enctype="multipart/form-data">
    <label for="npm">NPM:</label><br>
    <input type="text" id="npm" style="width: 515px; height: 13px;" name="npm" required><br><br>
    <label for="nama">Nama:</label><br>
    <input type="text" id="nama" style="width: 515px; height: 13px;" name="nama" required><br><br>
    <label for="alamat">Alamat:</label><br>
    <input type="text" id="alamat" style="width: 515px; height: 13px;" name="alamat" required><br><br>
    <label for="jenis_kelamin">Jenis Kelamin:</label><br>
    <select id="jenis_kelamin" name="jenis_kelamin" required>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select><br><br>
    <button type="submit" name="add">Tambah</button>
</form>

    </div>
</div>

<script>
 // Get modal elements
const addModal = document.getElementById("addModal");
const closeAddBtn = document.querySelector(".close");
const addButton = document.getElementById("add-btn");

// Open the modal when the "Tambah Mahasiswa" button is clicked
addButton.addEventListener("click", () => {
    addModal.style.display = "block";
});

// Close the modal when the "x" (close) button is clicked
closeAddBtn.addEventListener("click", () => {
    addModal.style.display = "none";
});

// Close the modal when clicking outside of the modal
window.addEventListener("click", (event) => {
    if (event.target === addModal) {
        addModal.style.display = "none";
    }
});

</script>

</body>
</html>