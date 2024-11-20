<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Cek login untuk user dari database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        
        if ($user['username'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;
    } else {
        $error = "Login gagal. Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
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
        .login-container {
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
        input[type="password"] {
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
        .error {
            color: #f44336;
            text-align: center;
            margin-top: 10px;
        }
        .back-to-register {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .back-to-register a {
            color: #007BFF;
            text-decoration: none;
        }
        .back-to-register a:hover {
            text-decoration: underline;
        }

</style>
<div class="login-container">
    <h2>Login</h2>
    
    <form method="POST" action="index.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" style="width: 375px; height: 13px;" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" style="width: 375px; height: 13px;" id="password" required>
        </div>
        <input type="submit" value="Login">
    </form>

    <!-- Menampilkan pesan error jika ada -->
    <?php if (isset($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Tombol untuk mengarah ke halaman registrasi -->
    <div class="back-to-register">
        <a href="registrasi.php">Belum punya akun? Daftar di sini</a>
    </div>
</div>

</body>
</html>
