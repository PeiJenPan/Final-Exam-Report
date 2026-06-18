<?php
session_start();
require_once "shop_db.php";
requireAdminForShop();
$conn = getShopDB();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['order_id']);
    $status = trim($_POST['status']);
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html><html lang="zh-Hant"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>顧客訂單紀錄</title><link rel="stylesheet" href="style.css"><style>.nav{text-align:right;margin-bottom:15px}.nav a{display:inline-block;margin-left:8px;padding:8px 16px;background:#fff0f6;color:#c94f7c;border-radius:999px;text-decoration:none;font-weight:bold;font-size:14px;border:1px solid #f4c6d8}.order-card{background:white;border-radius:24px;padding:24px;margin-bottom:18px;border:1px solid #f3c5d8;box-shadow:0 12px 30px rgba(190,80,130,.12)}.order-card h2{color:#b93f72;margin-top:0}.row{border-bottom:1px solid #f4d3e2;padding:8px 0;color:#555}.status{padding:8px;border-radius:10px;border:1px solid #e7bfd1}.btn{padding:9px 14px;border:none;border-radius:10px;background:#d76091;color:white;font-weight:bold;cursor:pointer}.empty{text-align:center;background:#fff8fb;border-radius:22px;padding:30px;color:#777}</style></head><body>
<div class="page"><div class="container result-container">
    <div class="nav"><a href="admin_products.php">商品管理</a><a href="shop.php">商品頁</a><a href="index.php">回首頁</a></div>
    <div class="badge">Admin Orders</div><h1>顧客訂單紀錄</h1>
    <?php if($orders->num_rows===0): ?><div class="empty">目前沒有顧客訂單。</div><?php else: ?>
        <?php while($order=$orders->fetch_assoc()): ?>
            <?php
            $itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
            $itemStmt->bind_param("i", $order['id']);
            $itemStmt->execute();
            $items = $itemStmt->get_result();
            ?>
            <div class="order-card">
                <h2>訂單 #<?php echo $order['id']; ?> ｜ <?php echo htmlspecialchars($order['username']); ?></h2>
                <div class="row"><strong>時間：</strong><?php echo htmlspecialchars($order['created_at']); ?></div>
                <div class="row"><strong>收件人：</strong><?php echo htmlspecialchars($order['customer_name']); ?>　<strong>電話：</strong><?php echo htmlspecialchars($order['phone']); ?></div>
                <div class="row"><strong>地址：</strong><?php echo htmlspecialchars($order['address']); ?></div>
                <div class="row"><strong>付款：</strong><?php echo htmlspecialchars($order['payment_method']); ?>　<strong>總金額：</strong>NT$ <?php echo number_format($order['total_amount']); ?></div>
                <div class="row"><strong>商品：</strong><br><?php while($item=$items->fetch_assoc()): ?><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['quantity']; ?>，小計 NT$ <?php echo number_format($item['subtotal']); ?><br><?php endwhile; ?></div>
                <form method="POST" style="margin-top:12px;">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <select class="status" name="status">
                        <?php foreach(['已成立','處理中','已出貨','已完成','已取消'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $order['status']===$s?'selected':''; ?>><?php echo $s; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn" type="submit" name="update_status" value="1">更新狀態</button>
                </form>
            </div>
            <?php $itemStmt->close(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div></div></body></html>
<?php $conn->close(); ?>
