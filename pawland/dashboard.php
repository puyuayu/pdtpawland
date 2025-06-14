<?php
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];

if ($user['role'] !== 'user') {
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard PawLand - User</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf7f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #7f5539;
            padding: 20px;
            color: #fff;
            text-align: center;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #7f5539;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1em;
            color: #555;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        li {
            margin: 15px 0;
        }

        a {
            display: inline-block;
            background-color: #7f5539;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        a:hover {
            background-color: #5e3f2c;
        }

        footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            color: #999;
        }
    </style>
</head>
<body>
    <header>
        <h1>PawLand 🐾</h1>
    </header>

    <div class="container">
        <h2>Halo, <?= htmlspecialchars($user['username']) ?>!</h2>
        <p>Selamat datang di dashboard pengguna. Kamu bisa menitipkan hewan peliharaanmu dengan mudah di sini.</p>

        <ul>
            <li><a href="tambah_penitipan.php">🐶 Titipkan Hewan</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <footer>
        &copy; <?= date('Y') ?> PawLand. All rights reserved.
    </footer>
</body>
</html>
