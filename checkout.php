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

$items = [];
$total = 0;
$ids = array_map('intval', array_keys($cart));
$idList = implode(',', $ids);
$products = $conn->query("SELECT * FROM products WHERE id IN ($idList)");
while ($p = $products->fetch_assoc()) {
    $pid = (int)$p['id'];
    $qty = (int)$cart[$pid];
    $subtotal = (int)$p['price'] * $qty;
    $total += $subtotal;
    $items[] = ['product'=>$p, 'quantity'=>$qty, 'subtotal'=>$subtotal];
}
?>
<!DOCTYPE html>
<html lang="zh-Hant"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>結帳</title><link rel="stylesheet" href="style.css"><style>.nav{text-align:right;margin-bottom:15px}.nav a{display:inline-block;margin-left:8px;padding:8px 16px;background:#fff0f6;color:#c94f7c;border-radius:999px;text-decoration:none;font-weight:bold;font-size:14px;border:1px solid #f4c6d8}.checkout-grid{display:grid;grid-template-columns:1.2fr .8fr;gap:24px}.box{background:white;border-radius:24px;padding:26px;border:1px solid #f3c5d8;box-shadow:0 12px 30px rgba(190,80,130,.12)}.box h2{color:#b93f72;margin-top:0}.field{margin-bottom:16px}.field label{display:block;margin-bottom:8px;font-weight:bold;color:#444}.field input,.field select,.field textarea{width:100%;padding:12px;border-radius:12px;border:1px solid #e7bfd1;outline:none}.order-row{display:flex;justify-content:space-between;border-bottom:1px solid #f4d3e2;padding:10px 0;color:#555}.total{font-size:22px;font-weight:bold;color:#d76091;text-align:right;margin-top:15px}.submit-btn{width:100%;padding:15px;border:none;border-radius:16px;background:linear-gradient(135deg,#d76091,#b94a95);color:white;font-weight:bold;font-size:16px;cursor:pointer}@media(max-width:768px){.checkout-grid{grid-template-columns:1fr}}</style></head>
<body><div class="page"><div class="container result-container">
    <div class="nav"><a href="cart.php">回購物車</a><a href="shop.php">商品頁</a></div>
    <div class="badge">Checkout</div><h1>結帳頁面</h1>
    <div class="checkout-grid">
        <div class="box">
            <h2>收件資料</h2>
            <form action="place_order.php" method="POST">
                <div class="field"><label>收件人姓名</label><input type="text" name="customer_name" required></div>
                <div class="field"><label>電話</label><input type="text" name="phone" required></div>
                <div class="field"><label>地址</label><textarea name="address" rows="3" required></textarea></div>
                <div class="field"><label>付款方式</label><select name="payment_method" required><option value="貨到付款">貨到付款</option><option value="ATM 轉帳">ATM 轉帳</option><option value="信用卡付款">信用卡付款</option></select></div>
                <button class="submit-btn" type="submit">確認結帳</button>
            </form>
        </div>
        <div class="box">
            <h2>訂單摘要</h2>
            <?php foreach($items as $item): $p=$item['product']; ?>
                <div class="order-row"><span><?php echo htmlspecialchars($p['name']); ?> × <?php echo $item['quantity']; ?></span><span>NT$ <?php echo number_format($item['subtotal']); ?></span></div>
            <?php endforeach; ?>
            <div class="total">總金額：NT$ <?php echo number_format($total); ?></div>
        </div>
    </div>
</div></div></body></html>
<?php $conn->close(); ?>
