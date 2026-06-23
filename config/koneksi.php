<?php
$host     = "localhost";
$username = "root";
$password = ""; 
$database = "spk_smartphone";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memulai session untuk fitur login di setiap halaman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>