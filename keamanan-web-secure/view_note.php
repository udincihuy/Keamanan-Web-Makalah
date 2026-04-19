<?php
session_start();
require 'db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Mengambil ID catatan dari URL (GET Parameter)
$note_id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM notes WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $note_id, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Catatan tidak ditemukan.");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Note - <?= $row['title'] ?></title>
    <style>body { font-family: sans-serif; margin: 40px; } .note-box { padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }</style>
</head>
<body>

    <a href="notes.php">&laquo; Kembali ke Daftar</a>
    <hr>
    
    <h2>View Catatan</h2>

    <div class="note-box">
        <!-- PERBAIKAN: Output judul dan konten disanitasi menggunakan htmlspecialchars() untuk Stored XSS prevention -->
        <h3><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <p>
            <em>Dibuat oleh User ID: <?= htmlspecialchars($row['user_id']) ?></em>
        </p>
        <div>
            <?php 
            
            echo nl2br(htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8')); 
            ?>
        </div>
    </div>

</body>
</html>