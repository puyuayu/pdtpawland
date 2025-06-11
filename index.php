<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PawLand Pet Shop</title>
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        header { background-color: #4CAF50; padding: 20px; text-align: center; color: white; }
        nav { background: #333; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        .hero { padding: 100px 20px; text-align: center; background: url('https://i.ibb.co/Yj3pX0d/petshop.jpg') no-repeat center center; background-size: cover; color: white; }
        .hero h1 { font-size: 48px; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
        .content { padding: 40px; text-align: center; }
        footer { background: #333; color: white; text-align: center; padding: 10px; }
        .btn { padding: 15px 30px; background: #4CAF50; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 18px; }
    </style>
</head>
<body>

<header>
    <h1>Selamat Datang di PawLand Pet Shop</h1>
</header>

<nav>
    <a href="#about">Tentang Kami</a>
    <a href="#services">Layanan</a>
    <a href="#contact">Kontak</a>
    <a href="login.php">Login</a>
</nav>

<div class="hero">
    <h1>Semua Kebutuhan Hewan Peliharaan Anda Ada di Sini!</h1>
    <a href="login.php" class="btn">Masuk Sistem</a>
</div>

<div class="content" id="about">
    <h2>Tentang PawLand</h2>
    <p>PawLand Pet Shop menyediakan berbagai jenis hewan peliharaan, makanan, aksesoris, perawatan, dan layanan kesehatan hewan secara profesional.</p>
</div>

<div class="content" id="services">
    <h2>Layanan Kami</h2>
    <p>🐶 Grooming | 🐱 Boarding | 🐕 Pemeriksaan Dokter Hewan | 🐾 Penjualan Hewan dan Aksesoris</p>
</div>

<div class="content" id="contact">
    <h2>Kontak</h2>
    <p>📞 0812-3456-7890 | ✉️ info@pawland.com</p>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> PawLand Pet Shop. All Rights Reserved.
</footer>

</body>
</html>
