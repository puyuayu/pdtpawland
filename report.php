<?php
// report.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
$year = date('Y'); $month = date('m');
$stmt = $conn->prepare("CALL GetMonthlySalesReport(?, ?)");
$stmt->bind_param("ii", $year, $month);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head><title>Laporan</title></head>
<body>
<h2>Laporan Penjualan Bulan Ini</h2>
<table border="1">
<tr><th>Tanggal</th><th>Transaksi</th><th>Pendapatan</th><th>Rata-rata</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['sale_date'] ?></td>
<td><?= $row['total_transactions'] ?></td>
<td><?= number_format($row['daily_revenue']) ?></td>
<td><?= number_format($row['avg_transaction_value']) ?></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>