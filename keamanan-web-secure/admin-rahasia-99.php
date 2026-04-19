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

// PERBAIKAN: Kontrol Sesi yang ketat, tolak jika bukan Admin tanpa celah.
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("HTTP/1.1 403 Forbidden");
    exit("Akses ditolak! Anda tidak memiliki hak akses sebagai admin.");
}

// Mengambil seluruh data pengguna yang ada di database.
$stmt = mysqli_prepare($conn, "SELECT id, username, is_admin FROM users");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Portal Admin</title>
    <style>body { font-family: sans-serif; margin: 40px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 10px; border: 1px solid #ccc; text-align: left; } .admin-box { border: 2px solid red; padding: 20px; }</style>
</head>
<body>

    <div class="admin-box">
        <h2>PORTAL ADMIN</h2>
        <p>Halaman ini diamankan menggunakan kontrol sesi yang ketat.</p>
        <p>Selamat datang, admin <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.</p>
        <a href="notes.php">Kembali ke Dashboard</a>
        <hr>

        <h3>Data Pengguna Terdaftar:</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Status Admin</th>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>'.htmlspecialchars($row['id']).'</td>';
                echo '<td>'.htmlspecialchars($row['username']).'</td>';
                echo '<td>'.($row['is_admin'] ? 'Administrator' : 'User Biasa').'</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <!-- Fitur Admin (Dummy/Rentan SQLi juga jika ditambah pencarian) -->
    </div>

</body>
</html>