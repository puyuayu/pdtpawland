<?php
include 'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Ambil data termasuk biaya_total
$stmt = $conn->prepare("SELECT nama_hewan, jenis, durasi_hari, status, biaya_total FROM penitipan WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penitipan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf7f0;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 800px;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Penitipan Anda</h2>
    <table>
        <tr>
            <th>Nama Hewan</th>
            <th>Jenis</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Total Biaya</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_hewan']) ?></td>
            <td><?= htmlspecialchars($row['jenis']) ?></td>
            <td><?= (int)$row['durasi_hari'] ?> hari</td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>Rp<?= $row['biaya_total'] !== null ? number_format($row['biaya_total'], 0, ',', '.') : '0' ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
