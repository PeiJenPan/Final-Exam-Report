<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();
$conn = getShopDB();

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "<script>alert('購物車沒有商品。'); window.location.href='shop.php';</script>";
    exit;
}

$customerName = trim($_POST['customer_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = trim($_POST['payment_method'] ?? '');
$username = $_SESSION['username'];

if ($customerName === '' || $phone === '' || $address === '' || $payment === '') {
    echo "<script>alert('請完整填寫結帳資料。'); window.location.href='checkout.php';</script>";
    exit;
}

$ids = array_map('intval', array_keys($cart));
$idList = implode(',', $ids);
$productResult = $conn->query("SELECT * FROM products WHERE id IN ($idList) AND is_active = 1");
$products = [];
while ($p = $productResult->fetch_assoc()) {
    $products[(int)$p['id']] = $p;
}

$total = 0;
$items = [];
foreach ($cart as $pid => $qty) {
    $pid = (int)$pid;
    $qty = (int)$qty;
    if (!isset($products[$pid])) continue;
    $p = $products[$pid];
    if ($qty > (int)$p['stock']) {
        echo "<script>alert('商品「" . addslashes($p['name']) . "」庫存不足，請回購物車調整數量。'); window.location.href='cart.php';</script>";
        exit;
    }
    $subtotal = (int)$p['price'] * $qty;
    $total += $subtotal;
    $items[] = ['product'=>$p, 'quantity'=>$qty, 'subtotal'=>$subtotal];
}

if (empty($items)) {
    echo "<script>alert('購物車商品不存在，請重新選購。'); window.location.href='shop.php';</script>";
    exit;
}

$stmt = $conn->prepare("INSERT INTO orders (username, customer_name, phone, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $username, $customerName, $phone, $address, $payment, $total);
$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();

$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
$stockStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

foreach ($items as $item) {
    $p = $item['product'];
    $pid = (int)$p['id'];
    $name = $p['name'];
    $price = (int)$p['price'];
    $qty = (int)$item['quantity'];
    $subtotal = (int)$item['subtotal'];

    $itemStmt->bind_param("iisiii", $orderId, $pid, $name, $price, $qty, $subtotal);
    $itemStmt->execute();

    $stockStmt->bind_param("ii", $qty, $pid);
    $stockStmt->execute();
}
$itemStmt->close();
$stockStmt->close();

$_SESSION['cart'] = [];
$conn->close();

header("Location: order_success.php?order_id=" . $orderId);
exit;
?>
