<?php
include 'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Titip Hewan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf7f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #7f5539;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
        }

        input[type="submit"] {
            background-color: #7f5539;
            color: #fff;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #5e3f2c;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Form Titip Hewan</h2>
        <form method="post" action="proses_tambah.php">
            <label for="nama_hewan">Nama Hewan:</label>
            <input type="text" name="nama_hewan" id="nama_hewan" required>

            <label for="jenis">Jenis Hewan:</label>
            <select name="jenis" id="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Anjing">Anjing</option>
                <option value="Kucing">Kucing</option>
            </select>

            <label for="durasi">Lama Hari Titip:</label>
            <input type="number" name="durasi" id="durasi" min="1" required>
            <input type="hidden" name="tanggal_mulai" value="<?= date('Y-m-d') ?>">
            <input type="submit" value="Titipkan">
        </form>
    </div>

</body>
</html>
