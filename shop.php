<?php
session_start();
require_once "shop_db.php";
$conn = getShopDB();

$result = $conn->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品頁面</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .shop-nav {
            text-align: right;
            margin-bottom: 15px;
            line-height: 2.5;
        }

        .shop-nav a {
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
            transition: 0.2s;
        }

        .shop-nav a:hover {
            background: #ffe4ef;
            transform: translateY(-2px);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .product-card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            border: 1px solid #f3c5d8;
            box-shadow: 0 12px 30px rgba(190,80,130,.12);
            display: flex;
            flex-direction: column;
        }

        .product-img {
            width: 100%;
            height: 180px;
            border-radius: 18px;
            object-fit: cover;
            background: #fff0f6;
            margin-bottom: 15px;
        }

        .product-card h2 {
            font-size: 20px;
            color: #b93f72;
            margin: 0 0 10px;
        }

        .product-card p {
            color: #666;
            line-height: 1.7;
            font-size: 14px;
            flex: 1;
        }

        .price {
            font-size: 22px;
            font-weight: bold;
            color: #d76091;
            margin: 10px 0;
        }

        .stock {
            font-size: 13px;
            color: #888;
            margin-bottom: 10px;
        }

        .qty-input {
            width: 80px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #e7bfd1;
            text-align: center;
        }

        .cart-btn {
            margin-top: 12px;
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #d76091, #b94a95);
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .cart-btn:hover {
            transform: translateY(-2px);
        }

        .cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .empty-box {
            text-align: center;
            background: #fff8fb;
            border-radius: 22px;
            padding: 28px;
            color: #777;
        }

        @media(max-width:768px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-img {
                height: 220px;
            }

            .shop-nav {
                text-align: center;
            }

            .shop-nav a {
                font-size: 13px;
                padding: 8px 14px;
                margin-left: 4px;
            }
        }
    </style>
</head>

<body>

<div class="page">
    <div class="container">

        <div class="shop-nav">

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

                <a href="index.php">回首頁</a>
                <a href="logout.php">登出</a>
                <a href="admin_products.php">商品管理</a>
                <a href="admin_orders.php">訂單管理</a>

            <?php elseif (isset($_SESSION['username'])): ?>

                <a href="index.php">回首頁</a>
                <a href="cart.php">購物車（<?php echo cartCount(); ?>）</a>
                <a href="my_orders.php">我的訂單</a>
                <a href="logout.php">登出</a>

            <?php else: ?>

                <a href="index.php">回首頁</a>
                <a href="login.php">登入</a>

            <?php endif; ?>

        </div>

        <div class="badge">ColorMe Shop</div>

        <h1>推薦商品</h1>

        <p class="subtitle">
            瀏覽適合妝容與色彩分析後搭配的商品。
            未登入者可以瀏覽商品，但加入購物車前需要先登入。
        </p>

        <?php if ($result->num_rows === 0): ?>

            <div class="empty-box">
                目前尚未上架商品，請管理員先到商品管理頁新增商品。
            </div>

        <?php else: ?>

            <div class="product-grid">

                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="product-card">

                        <?php $img = trim($row['image_url'] ?? ''); ?>

                        <?php if ($img !== ''): ?>

                            <img
                                class="product-img"
                                src="<?php echo htmlspecialchars($img); ?>"
                                alt="商品圖片"
                            >

                        <?php else: ?>

                            <div class="product-img" style="display:flex;align-items:center;justify-content:center;color:#c94f7c;font-weight:bold;">
                                No Image
                            </div>

                        <?php endif; ?>

                        <h2>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </h2>

                        <p>
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </p>

                        <div class="price">
                            NT$ <?php echo number_format($row['price']); ?>
                        </div>

                        <div class="stock">
                            庫存：<?php echo htmlspecialchars($row['stock']); ?>
                        </div>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

                            <a
                                href="admin_products.php"
                                class="cart-btn"
                                style="display:block;text-align:center;text-decoration:none;"
                            >
                                前往商品管理
                            </a>

                        <?php else: ?>

                            <form action="add_to_cart.php" method="POST">

                                <input
                                    type="hidden"
                                    name="product_id"
                                    value="<?php echo $row['id']; ?>"
                                >

                                <input
                                    class="qty-input"
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="<?php echo max(1, (int)$row['stock']); ?>"
                                >

                                <button
                                    class="cart-btn"
                                    type="submit"
                                    <?php echo ((int)$row['stock'] <= 0) ? 'disabled' : ''; ?>
                                >
                                    <?php echo ((int)$row['stock'] <= 0) ? '已售完' : '加入購物車'; ?>
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>

    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>