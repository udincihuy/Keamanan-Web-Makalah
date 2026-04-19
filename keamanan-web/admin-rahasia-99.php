<?php
session_start();
require 'db.php';

// Cek apakah admin, tapi portal ini seharusnya tetap ada "sesuatu"
// Namun untuk vulnerability Security Misconfiguration atau Obscurity,
// file ini sengaja disembunyikan dan diakses langsung dari URL saja
// (tidak ada di navbar halaman lain)
// Jika tidak diretriksi, ini juga Broken Access Control / Insecure Direct Object Reference

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak! Anda harus login.");
}

// Kadang-kadang ada yang buat portal admin tapi tidak cek status is_admin,
// jadi user biasa yang tahu URL ini bisa akses panel admin (Vulnerability BAC ke 2)
if ($_SESSION['is_admin'] != 1) {
    echo "<h3 style='color:red;'>Hanya Admin yang bisa mengakses halaman ini... TAPI... </h3>";
    // Biarkan script jalan saja (jangan di die() kalau mau user bisa "mencuri" info)
    // ATAU kita restrict sebagian. Kita restrict saja biar lebih realistis.
    die("Anda bukan admin!");
}

// Fitur View Semua User dan DB
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hidden Admin Portal - (Security By Obscurity)</title>
    <style>body { font-family: sans-serif; margin: 40px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 10px; border: 1px solid #ccc; text-align: left; } .admin-box { border: 2px solid red; padding: 20px; }</style>
</head>
<body>

    <div class="admin-box">
        <h2>🔥 PORTAL ADMIN RAHASIA 🔥</h2>
        <p>Anda menemukan halaman ini (Mungkin lewat Brute-Force/Directory Fuzzing)!</p>
        <p>Selamat datang, admin <strong><?= $_SESSION['username'] ?></strong>.</p>
        <a href="notes.php">Kembali ke Dashboard</a>
        <hr>

        <h3>Data Pengguna Terdaftar:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password (Plain Text)</th>
                <th>Status Admin</th>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['username'].'</td>';
                echo '<td>'.$row['password'].'</td>';
                echo '<td>'.($row['is_admin'] ? 'Administrator' : 'User Biasa').'</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <!-- Fitur Admin (Dummy/Rentan SQLi juga jika ditambah pencarian) -->
    </div>

</body>
</html>