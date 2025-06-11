<?php
// delete_product.php
include 'config.php';
if (!isset($_SESSION['user'])) header("Location: login.php");
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE product_id=$id");
header("Location: products.php");
?>