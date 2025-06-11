<?php
// edit_product.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
$id = $_GET['id'];

if (isset($_POST['update'])) {
    $name = $_POST['name']; $cat = $_POST['category']; $price = $_POST['price']; $stock = $_POST['stock']; $type = $_POST['type'];
    $stmt = $conn->prepare("UPDATE products SET product_name=?, category_id=?, price=?, stock_quantity=?, product_type=? WHERE product_id=?");
    $stmt->bind_param("sidisi", $name, $cat, $price, $stock, $type, $id);
    $stmt->execute();
    header("Location: products.php");
}
$product = $conn->query("SELECT * FROM products WHERE product_id=$id")->fetch_assoc();
$cat = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head><title>Edit Produk</title></head>
<body>
<h2>Edit Produk</h2>
<form method="post">
Nama: <input type="text" name="name" value="<?= $product['product_name'] ?>" required><br>
Kategori: <select name="category">
<?php while($row=$cat->fetch_assoc()) {
    $sel = ($row['category_id'] == $product['category_id']) ? "selected" : "";
    echo "<option value='{$row['category_id']}' $sel>{$row['category_name']}</option>";
} ?>
</select><br>
Harga: <input type="number" name="price" value="<?= $product['price'] ?>" required><br>
Stok: <input type="number" name="stock" value="<?= $product['stock_quantity'] ?>" required><br>
Tipe: <input type="text" name="type" value="<?= $product['product_type'] ?>" required><br>
<input type="submit" name="update" value="Update">
</form>
</body>
</html>
