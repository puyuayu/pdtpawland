<?php
// dashboard.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
<h2>Selamat Datang, <?php echo $user['full_name']; ?> (<?php echo $user['role']; ?>)</h2>
<a href="products.php">Manajemen Produk</a> |
<a href="sales.php">Transaksi Penjualan</a> |
<a href="report.php">Laporan Penjualan</a> |
<a href="logout.php">Logout</a>
</body>
</html>