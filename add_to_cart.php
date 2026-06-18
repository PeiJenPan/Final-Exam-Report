<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();
$conn = getShopDB();

$productId = intval($_POST['product_id'] ?? 0);
$quantity = max(1, intval($_POST['quantity'] ?? 1));

$stmt = $conn->prepare("SELECT id, stock FROM products WHERE id = ? AND is_active = 1 LIMIT 1");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<script>alert('找不到商品。'); window.location.href='shop.php';</script>";
    exit;
}

if ((int)$product['stock'] <= 0) {
    echo "<script>alert('此商品目前沒有庫存。'); window.location.href='shop.php';</script>";
    exit;
}

$quantity = min($quantity, (int)$product['stock']);

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = 0;
}

$_SESSION['cart'][$productId] += $quantity;
$_SESSION['cart'][$productId] = min($_SESSION['cart'][$productId], (int)$product['stock']);

$conn->close();
echo "<script>alert('已加入購物車。'); window.location.href='cart.php';</script>";
exit;
?>
