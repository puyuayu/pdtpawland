<?php
// products.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
$result = $conn->query("SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id");
?>
<!DOCTYPE html>
<html>
<head><title>Produk</title></head>
<body>
<h2>Data Produk</h2>
<a href="add_product.php">Tambah Produk</a>
<table border="1">
<tr><th>ID</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['product_id'] ?></td>
<td><?= $row['product_name'] ?></td>
<td><?= $row['category_name'] ?></td>
<td><?= number_format($row['price']) ?></td>
<td><?= $row['stock_quantity'] ?></td>
<td><a href="edit_product.php?id=<?= $row['product_id'] ?>">Edit</a> | 
<a href="delete_product.php?id=<?= $row['product_id'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
