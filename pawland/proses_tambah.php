<?php
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$nama = $_POST['nama_hewan'];
$jenis = $_POST['jenis'];
$durasi = (int)$_POST['durasi'];
$tanggalMulai = $_POST['tanggal_mulai'];
$user_id = $_SESSION['user']['id'];

// Tarif beda per jenis
if (strtolower($jenis) === 'kucing') {
    $tarif_per_hari = 20000;
} elseif (strtolower($jenis) === 'anjing') {
    $tarif_per_hari = 30000;
} else {
    $tarif_per_hari = 25000; // default jika jenis tidak diketahui
}

$biaya_total = $durasi * $tarif_per_hari;

$conn->begin_transaction(); // Mulai transaksi

try {
    $stmt = $conn->prepare("INSERT INTO penitipan (user_id, nama_hewan, jenis, durasi_hari, tanggal_mulai, status, biaya_total) VALUES (?, ?, ?, ?, ?, 'Diproses', ?)");
    $stmt->bind_param("issisi", $user_id, $nama, $jenis, $durasi, $tanggalMulai, $biaya_total);
    $stmt->execute();

    $conn->commit(); // Simpan semua jika berhasil
    header("Location: list_penitipan.php");
    exit;
} catch (Exception $e) {
    $conn->rollback(); // Batalkan kalau ada error
    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>
