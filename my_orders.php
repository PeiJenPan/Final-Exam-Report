<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();
$conn = getShopDB();
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html><html lang="zh-Hant"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>我的訂單</title><link rel="stylesheet" href="style.css"><style>.nav{text-align:right;margin-bottom:15px}.nav a{display:inline-block;margin-left:8px;padding:8px 16px;background:#fff0f6;color:#c94f7c;border-radius:999px;text-decoration:none;font-weight:bold;font-size:14px;border:1px solid #f4c6d8}.order-card{background:white;border-radius:24px;padding:24px;margin-bottom:18px;border:1px solid #f3c5d8;box-shadow:0 12px 30px rgba(190,80,130,.12);position:relative;overflow:hidden}.order-card:before{content:"";position:absolute;left:0;top:0;width:8px;height:100%;background:linear-gradient(180deg,#d76091,#b94a95)}.order-header{display:flex;justify-content:space-between;gap:15px;align-items:center;cursor:pointer;padding-left:10px}.order-title h2{margin:0 0 8px;color:#b93f72;font-size:22px}.meta{color:#666;line-height:1.8}.toggle{width:42px;height:42px;border-radius:50%;border:1px solid #f4c6d8;background:#fff0f6;color:#c94f7c;font-size:20px;font-weight:bold;cursor:pointer}.detail{display:none;margin-top:18px;padding:18px;border-radius:18px;background:#fff8fb;border:1px solid #f4c6d8;line-height:1.8}.detail.show{display:block}.row{border-bottom:1px solid #f4d3e2;padding:8px 0}.empty{text-align:center;background:#fff8fb;border-radius:22px;padding:30px;color:#777}</style></head><body>
<div class="page"><div class="container result-container">
    <div class="nav"><a href="shop.php">商品頁</a><a href="cart.php">購物車</a><a href="index.php">回首頁</a></div>
    <div class="badge">My Orders</div><h1>我的歷史訂單</h1>
    <?php if($orders->num_rows===0): ?><div class="empty">目前沒有訂單紀錄。</div><?php else: ?>
        <?php while($order=$orders->fetch_assoc()): ?>
            <?php
            $itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $itemStmt->bind_param("i", $order['id']);
            $itemStmt->execute();
            $items = $itemStmt->get_result();
            ?>
            <div class="order-card">
                <div class="order-header" onclick="toggleOrder(this)">
                    <div class="order-title"><h2>訂單 #<?php echo $order['id']; ?></h2><div class="meta">時間：<?php echo htmlspecialchars($order['created_at']); ?>｜狀態：<?php echo htmlspecialchars($order['status']); ?>｜總金額：NT$ <?php echo number_format($order['total_amount']); ?></div></div>
                    <button class="toggle" type="button">↓</button>
                </div>
                <div class="detail">
                    <div class="row"><strong>收件人：</strong><?php echo htmlspecialchars($order['customer_name']); ?></div>
                    <div class="row"><strong>電話：</strong><?php echo htmlspecialchars($order['phone']); ?></div>
                    <div class="row"><strong>地址：</strong><?php echo htmlspecialchars($order['address']); ?></div>
                    <div class="row"><strong>付款方式：</strong><?php echo htmlspecialchars($order['payment_method']); ?></div>
                    <h3 style="color:#b93f72;">商品明細</h3>
                    <?php while($item=$items->fetch_assoc()): ?><div class="row"><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['quantity']; ?>　NT$ <?php echo number_format($item['subtotal']); ?></div><?php endwhile; ?>
                </div>
            </div>
            <?php $itemStmt->close(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div></div><script>function toggleOrder(header){const card=header.closest('.order-card');const detail=card.querySelector('.detail');const btn=card.querySelector('.toggle');detail.classList.toggle('show');btn.innerText=detail.classList.contains('show')?'↑':'↓';}</script></body></html>
<?php $stmt->close(); $conn->close(); ?>
