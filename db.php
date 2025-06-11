<?php
// config.php - Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "pawland_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
