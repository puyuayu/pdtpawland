<?php
$conn = new mysqli("localhost", "root", "", "pawland");
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);
session_start();
?>
