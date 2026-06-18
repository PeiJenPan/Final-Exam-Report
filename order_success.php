<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();
$conn = getShopDB();

$orderId = intval($_GET['order_id'] ?? 0);
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND username = ? LIMIT 1");
$stmt->bind_param("is", $orderId, $username);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<script>alert('找不到訂單。'); window.location.href='my_orders.php';</script>";
    exit;
}

$itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>
<!DOCTYPE html><html lang="zh-Hant"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>訂單完成</title><link rel="stylesheet" href="style.css"><style>.nav{text-align:right;margin-bottom:15px}.nav a{display:inline-block;margin-left:8px;padding:8px 16px;background:#fff0f6;color:#c94f7c;border-radius:999px;text-decoration:none;font-weight:bold;font-size:14px;border:1px solid #f4c6d8}.box{background:white;border-radius:24px;padding:28px;border:1px solid #f3c5d8;box-shadow:0 12px 30px rgba(190,80,130,.12)}.success{font-size:48px;text-align:center}.info-row{padding:10px 0;border-bottom:1px solid #f4d3e2;color:#555}.info-row strong{color:#b93f72}.total{text-align:right;font-size:24px;font-weight:bold;color:#d76091;margin-top:20px}</style></head><body>
<div class="page"><div class="container result-container">
    <div class="nav"><a href="shop.php">繼續購物</a><a href="my_orders.php">查看歷史訂單</a><a href="index.php">回首頁</a></div>
    <div class="success">✅</div><div class="badge">Order Completed</div><h1>訂單完成</h1>
    <div class="box">
        <div class="info-row"><strong>訂單編號：</strong>#<?php echo $order['id']; ?></div>
        <div class="info-row"><strong>訂購帳號：</strong><?php echo htmlspecialchars($order['username']); ?></div>
        <div class="info-row"><strong>收件人：</strong><?php echo htmlspecialchars($order['customer_name']); ?></div>
        <div class="info-row"><strong>電話：</strong><?php echo htmlspecialchars($order['phone']); ?></div>
        <div class="info-row"><strong>地址：</strong><?php echo htmlspecialchars($order['address']); ?></div>
        <div class="info-row"><strong>付款方式：</strong><?php echo htmlspecialchars($order['payment_method']); ?></div>
        <div class="info-row"><strong>訂單時間：</strong><?php echo htmlspecialchars($order['created_at']); ?></div>
        <h2 style="color:#b93f72;margin-top:24px;">商品明細</h2>
        <?php while($item=$items->fetch_assoc()): ?>
            <div class="info-row"><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['quantity']; ?>　NT$ <?php echo number_format($item['subtotal']); ?></div>
        <?php endwhile; ?>
        <div class="total">總金額：NT$ <?php echo number_format($order['total_amount']); ?></div>
    </div>
</div></div></body></html>
<?php $itemStmt->close(); $conn->close(); ?>
