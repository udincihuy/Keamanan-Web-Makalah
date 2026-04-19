<?php
session_start();
require 'db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// MENCEGAH SQLi dan IDOR: Ambil catatan HANYA milik user ini menggunakan Prepared Statement
$stmt_get = mysqli_prepare($conn, "SELECT * FROM notes WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_get, "i", $user_id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);

// Tambah catatan (Aman dari SQLi)
if (isset($_POST['add_note'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // MENCEGAH SQL INJECTION: Menggunakan Prepared Statement
    $stmt = mysqli_prepare($conn, "INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $content);
    mysqli_stmt_execute($stmt);
    
    // Refresh untuk melihat data baru
    header("Location: notes.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Catatan - Mini Note-Taking</title>
    <style>body { font-family: sans-serif; margin: 40px; } table { width: 100%; border-collapse: collapse; } th, td { padding: 10px; border: 1px solid #ccc; text-align: left; } form { margin-bottom: 20px; }</style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Halo, <?= $_SESSION['username'] ?>! (ID: <?= $_SESSION['user_id'] ?>)</h2>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Form Tambah Catatan -->
    <fieldset>
        <legend>Buat Catatan Baru (Hint XSS: Coba `&lt;script&gt;alert(1)&lt;/script&gt;`)</legend>
        <form method="POST">
            <p>
                Judul:<br><input type="text" name="title" required>
            </p>
            <p>
                Isi:<br><textarea name="content" rows="4" cols="50" required></textarea>
            </p>
            <button type="submit" name="add_note">Tambah Catatan</button>
        </form>
    </fieldset>

    <br>

    <h3>Daftar Catatan Anda</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Aksi</th>
        </tr>
        <?php if ($result && mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $no++ . '</td>';
                // PENCEGAHAN XSS (Persistent/Stored): Escaping HTML Entities
                echo '<td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</td>';
                // URL view menggunakan ID catatan
                echo '<td><a href="view_note.php?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">Lihat Detil</a></td>';
                echo '</tr>';
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada catatan.</td></tr>";
        }
        ?>
    </table>

</body>
</html>