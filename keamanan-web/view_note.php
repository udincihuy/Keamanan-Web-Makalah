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

$query = "SELECT * FROM notes WHERE id = '$note_id'";
$result = mysqli_query($conn, $query);

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
    
    <h2>View Catatan (Intentionally XSS and BAC Vulnerable)</h2>

    <div class="note-box">
       
        <h3><?php echo $row['title']; ?></h3>
        <p>
            <em>Dibuat oleh User ID: <?= $row['user_id'] ?> (Cek apakah ini ID Anda? BAC bekerja jika bukan!)</em>
        </p>
        <div>
            <?php 
            
            echo $row['content']; 
            ?>
        </div>
    </div>

</body>
</html>