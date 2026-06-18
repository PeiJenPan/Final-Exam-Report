<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員後台</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .admin-menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
            margin-top: 35px;
        }

        .admin-menu-card {
            background: #fff8fb;
            border: 1px solid #f4c6d8;
            border-radius: 24px;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            color: #444;
            transition: 0.25s;
            box-shadow: 0 10px 25px rgba(212,98,148,0.10);
        }

        .admin-menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(212,98,148,0.18);
        }

        .admin-menu-card .icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .admin-menu-card h3 {
            margin: 0 0 10px;
            color: #b93f72;
            font-size: 22px;
        }

        .admin-menu-card p {
            margin: 0;
            color: #777;
            line-height: 1.7;
            font-size: 14px;
        }

        @media(max-width:768px) {
            .admin-menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="container">

        <div style="text-align: right; margin-bottom: 15px;">

            <a href="index.php" style="display: inline-block; padding: 8px 16px; background: #fff0f6; color: #c94f7c; border-radius: 999px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #f4c6d8;">
                回首頁
            </a>

            <a href="logout.php" style="display: inline-block; margin-left: 10px; padding: 8px 16px; background: #fff0f6; color: #c94f7c; border-radius: 999px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #f4c6d8;">
                登出
            </a>

        </div>

        <div class="badge">
            Admin Dashboard
        </div>

        <h1>管理員後台</h1>

        <p class="subtitle">
            管理員可以在此管理商品、訂單與明星案例資料。
        </p>

        <div class="admin-menu-grid">

            <a href="admin_products.php" class="admin-menu-card">
                <div class="icon">📦</div>
                <h3>上架商品</h3>
                <p>
                    新增、修改、刪除商品，設定商品價格、庫存與圖片。
                </p>
            </a>

            <a href="admin_orders.php" class="admin-menu-card">
                <div class="icon">📑</div>
                <h3>檢視訂單</h3>
                <p>
                    查看顧客訂單紀錄、訂單明細與更新訂單狀態。
                </p>
            </a>

            <a href="admin_celebrities.php" class="admin-menu-card">
                <div class="icon">🌟</div>
                <h3>上傳新的明星案例</h3>
                <p>
                    新增明星色彩分析案例，管理明星圖片與分析內容。
                </p>
            </a>

            <a href="celebrities.php" class="admin-menu-card">
                <div class="icon">👀</div>
                <h3>檢視明星案例庫</h3>
                <p>
                    查看前台顯示的明星案例庫頁面，確認呈現效果。
                </p>
            </a>

        </div>

    </div>
</div>

</body>
</html>