<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();
$conn = getShopDB();

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_map('intval', array_keys($cart));
    $idList = implode(',', $ids);

    $products = $conn->query("SELECT * FROM products WHERE id IN ($idList)");

    while ($p = $products->fetch_assoc()) {
        $pid = (int)$p['id'];
        $qty = (int)$cart[$pid];
        $subtotal = (int)$p['price'] * $qty;
        $total += $subtotal;

        $items[] = [
            'product' => $p,
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .nav {
            text-align: right;
            margin-bottom: 15px;
        }

        .nav a {
            display: inline-block;
            margin-left: 8px;
            padding: 8px 16px;
            background: #fff0f6;
            color: #c94f7c;
            border-radius: 999px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #f4c6d8;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 18px;
            overflow: hidden;
        }

        .cart-table th,
        .cart-table td {
            padding: 14px;
            border-bottom: 1px solid #f4d3e2;
            text-align: left;
            vertical-align: middle;
        }

        .cart-table th {
            background: #fff0f6;
            color: #b93f72;
        }

        .cart-table th:last-child,
        .cart-table td:last-child {
            text-align: center;
            vertical-align: middle;
        }

        .qty-text {
            display: inline-block;
            min-width: 45px;
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid #e7bfd1;
            text-align: center;
            background: #fff;
            color: #333;
            font-weight: bold;
        }

        .small-btn {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 10px;
            border: none;
            background: #fff0f6;
            color: #c94f7c;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            min-width: 64px;
            line-height: 1.4;
            margin: 0;
        }

        .danger {
            background: #ffe8e8;
            color: #d93025;
        }

        .danger:hover {
            background: #ffd6d6;
        }

        .remove-form {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .remove-form button {
            margin: 0;
        }

        .total {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #d76091;
            margin: 25px 0;
        }

        .checkout-btn {
            display: block;
            text-align: center;
            width: 260px;
            margin-left: auto;
            padding: 14px;
            background: linear-gradient(135deg, #d76091, #b94a95);
            color: white;
            border-radius: 999px;
            text-decoration: none;
            font-weight: bold;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
        }

        .empty-box {
            text-align: center;
            background: #fff8fb;
            border-radius: 22px;
            padding: 30px;
            color: #777;
        }

        @media(max-width:768px) {
            .cart-table {
                font-size: 13px;
            }

            .cart-table th,
            .cart-table td {
                padding: 10px;
            }

            .checkout-btn {
                width: 100%;
            }

            .small-btn {
                padding: 5px 12px;
                min-width: 56px;
                font-size: 12px;
            }

            .qty-text {
                padding: 6px 10px;
                min-width: 38px;
            }
        }
    </style>
</head>

<body>

<div class="page">
    <div class="container result-container">

        <div class="nav">
            <a href="shop.php">繼續購物</a>
            <a href="my_orders.php">我的訂單</a>
            <a href="index.php">回首頁</a>
        </div>

        <div class="badge">
            Shopping Cart
        </div>

        <h1>購物車</h1>

        <?php if (empty($items)): ?>

            <div class="empty-box">
                購物車目前沒有商品。
            </div>

            <a href="shop.php" class="back">
                前往商品頁
            </a>

        <?php else: ?>

            <table class="cart-table">

                <tr>
                    <th>商品</th>
                    <th>單價</th>
                    <th>數量</th>
                    <th>小計</th>
                    <th>操作</th>
                </tr>

                <?php foreach ($items as $item): ?>

                    <?php $p = $item['product']; ?>

                    <tr>
                        <td>
                            <?php echo htmlspecialchars($p['name']); ?>
                        </td>

                        <td>
                            NT$ <?php echo number_format($p['price']); ?>
                        </td>

                        <td>
                            <span class="qty-text">
                                <?php echo htmlspecialchars($item['quantity']); ?>
                            </span>
                        </td>

                        <td>
                            NT$ <?php echo number_format($item['subtotal']); ?>
                        </td>

                        <td>
                            <form class="remove-form" action="update_cart.php" method="POST">
                                <button
                                    class="small-btn danger"
                                    type="submit"
                                    name="remove"
                                    value="<?php echo $p['id']; ?>"
                                >
                                    移除
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>

            <div class="total">
                總金額：NT$ <?php echo number_format($total); ?>
            </div>

            <a class="checkout-btn" href="checkout.php">
                前往結帳
            </a>

        <?php endif; ?>

    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>