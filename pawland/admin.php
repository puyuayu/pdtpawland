<?php
include 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$result = $conn->query("
    SELECT p.*, u.username 
    FROM penitipan p
    JOIN user u ON p.user_id = u.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin PawLand</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf7f0;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #7f5539;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #7f5539;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2e9e4;
        }

        .logout-btn {
            float: right;
            margin-top: -40px;
            background-color: #c1121f;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #a10e1a;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard Admin PawLand</h2>
    <a class="logout-btn" href="logout.php">Logout</a>
    <table>
        <tr>
            <th>Username</th>
            <th>Nama Hewan</th>
            <th>Jenis</th>
            <th>Sisa Durasi</th>
            <th>Status</th>
            <th>Total Biaya</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { 
            $tanggalMulai = new DateTime($row['tanggal_mulai']);
            $hariDurasi = (int)$row['durasi_hari'];
            $tanggalSelesai = clone $tanggalMulai;
            $tanggalSelesai->modify("+$hariDurasi days");
            $today = new DateTime();

            $sisaHari = max(0, $tanggalSelesai->diff($today)->invert ? $tanggalSelesai->diff($today)->days : 0);
            $isExpired = $today >= $tanggalSelesai;

            // Jika sudah habis, ubah status jadi 'Selesai'
            if ($isExpired && $row['status'] !== 'Selesai') {
                $update = $conn->prepare("UPDATE penitipan SET status = 'Selesai' WHERE id = ?");
                $update->bind_param("i", $row['id']);
                $update->execute();
                $row['status'] = 'Selesai';
            }

            // Hitung total biaya
            $hargaPerHari = (strtolower($row['jenis']) === 'anjing') ? 30000 : 20000;
            $totalBiaya = $hariDurasi * $hargaPerHari;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
            echo "<td>" . ($isExpired ? "0 hari" : "$sisaHari hari") . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>Rp" . number_format($totalBiaya, 0, ',', '.') . "</td>";
            echo "</tr>";
        } ?>
    </table>
</div>

</body>
</html>
