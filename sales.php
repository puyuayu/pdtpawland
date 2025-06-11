<?php
// sales.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
if (isset($_POST['process'])) {
    $customer = $_POST['customer'];
    $discount = $_POST['discount'];
    $product_ids = implode(",", $_POST['product_id']);
    $quantities = implode(",", $_POST['qty']);
    $prices = implode(",", $_POST['price']);
    $stmt = $conn->prepare("CALL ProcessSale(?, ?, ?, ?, ?, ?, ?)");
    $method = "cash";
    $user_id = $_SESSION['user']['user_id'];
    $stmt->bind_param("iisdsss", $customer, $user_id, $method, $discount, $product_ids, $quantities, $prices);
    $stmt->execute();
    $stmt->close();
}
$customers = $conn->query("SELECT * FROM customers");
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head><title>Penjualan</title></head>
<body>
<h2>Proses Penjualan</h2>
<form method="post">
Pilih Customer: <select name="customer"><?php while($row=$customers->fetch_assoc()) echo "<option value='{$row['customer_id']}'>{$row['customer_name']}</option>"; ?></select><br>
Diskon: <input type="number" name="discount" value="0"><br>
<h3>Produk:</h3>
<?php while($p=$products->fetch_assoc()): ?>
<input type="checkbox" name="product_id[]" value="<?= $p['product_id'] ?>"> <?= $p['product_name'] ?> <br>
Qty: <input type="number" name="qty[]" value="1"> Harga: <input type="number" name="price[]" value="<?= $p['price'] ?>"><br>
<?php endwhile; ?>
<input type="submit" name="process" value="Proses Penjualan">
</form>
</body>
</html>