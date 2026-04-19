<?php
session_start();
require 'db.php';

// Jika pengguna sudah login, arahkan ke halaman cataatn
if (isset($_SESSION['user_id'])) {
    header("Location: notes.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong!";
    } else {
        // Cek apakah username sudah ada
        $stmt_check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // Masukkan pengguna baru ke database (default tidak admin)
            $stmt_insert = mysqli_prepare($conn, "INSERT INTO users (username, password, is_admin) VALUES (?, ?, 0)");
            
            // Perhatian: Menyimpan password dalam plaintext (TIDAK disarankan untuk produksi, tapi kita pakai metode aslinya)
            mysqli_stmt_bind_param($stmt_insert, "ss", $username, $password);

            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Akun berhasil dibuat! Silakan <a href='index.php'>login di sini</a>.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar. Coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Mini Note-Taking</title>
    <style>body { font-family: sans-serif; margin: 40px; } .login-box { max-width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; }</style>
</head>
<body>

<div class="login-box">
    <h2>Pendaftaran Akun</h2>
    
    <?php if ($error != ''): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success != ''): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <?php if ($success == ''): ?>
    <form method="POST" action="">
        <p>
            <label>Username</label><br>
            <input type="text" name="username" required>
        </p>
        <p>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </p>
        <button type="submit">Daftar</button>
        <p style="margin-top: 15px; font-size: 14px;">Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
