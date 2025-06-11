<?php
// add_product.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $cat = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, price, stock_quantity, product_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidis", $name, $cat, $price, $stock, $type);
    $stmt->execute();
    header("Location: products.php");
}
$cat = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head><title>Tambah Produk</title></head>
<body>
<h2>Tambah Produk</h2>
<form method="post">
Nama: <input type="text" name="name" required><br>
Kategori: <select name="category">
<?php while($row=$cat->fetch_assoc()) echo "<option value='{$row['category_id']}'>{$row['category_name']}</option>"; ?>
</select><br>
Harga: <input type="number" name="price" required><br>
Stok: <input type="number" name="stock" required><br>
Tipe: <input type="text" name="type" required><br>
<input type="submit" name="save" value="Simpan">
</form>
</body>
</html>
