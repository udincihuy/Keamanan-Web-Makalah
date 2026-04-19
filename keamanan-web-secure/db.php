<?php
// Pengaturan Koneksi Database (Ubah sesuai dengan server DB Anda)
$host = "localhost";
$user = "root";       // Default XAMPP
$pass = "";           // Default XAMPP
$db   = "vuln_notes";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>