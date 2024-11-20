<?php
// Menghubungkan ke database
include('db.php');

// Mulai sesi
session_start();

// Inisialisasi variabel untuk menyimpan pesan kesalahan atau sukses
$error = "";
$success = "";

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        // Cek apakah username atau email sudah ada
        $sql = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 0) {
            // Jika username atau email tidak ada, lanjutkan registrasi
            // Enkripsi password menggunakan password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Query untuk menyimpan pengguna baru
            $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            
            if ($conn->query($sql) === TRUE) {
                $success = "Registrasi berhasil! Anda dapat <a href='login.php'>login di sini</a>.";
            } else {
                $error = "Terjadi kesalahan: " . $conn->error;
            }
        } else {
            $error = "Username atau email sudah terdaftar. Silakan pilih username atau email lain.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error, .success {
            text-align: center;
            margin-top: 15px;
        }
        .error {
            color: #f44336;
        }
        .success {
            color: #4CAF50;
        }
        .back-to-login {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .back-to-login a {
            color: #007BFF;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Registrasi Pengguna</h2>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" style="width: 375px; height: 13px;" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" style="width: 375px; height: 13px;" id="password" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" style="width: 375px; height: 13px;" id="email" required>
        </div>
        <input type="submit" value="Daftar">
    </form>

    <!-- Menampilkan pesan sukses atau error -->
    <div class="error"><?php echo $error; ?></div>
    <div class="success"><?php echo $success; ?></div>

    <!-- Tombol untuk kembali ke halaman login -->
    <div class="back-to-login">
        <a href="index.php">Kembali ke halaman login</a>
    </div>
</div>

</body>
</html>
