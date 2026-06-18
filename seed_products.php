<?php
session_start();
require_once "shop_db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('只有管理員可以執行商品上架。'); window.location.href='login.php';</script>";
    exit;
}

$conn = getShopDB();

$products = [
    [
        "name" => "Dior Addict Lip Glow 潤唇膏",
        "description" => "Dior 經典潤色護唇膏，適合日常淡妝與自然妝感，可增加唇部氣色。",
        "price" => 1450,
        "image_url" => "https://placehold.co/600x400/fff0f6/c94f7c?text=Dior+Lip+Glow",
        "stock" => 20
    ],
    [
        "name" => "YSL 情挑誘光水唇膏",
        "description" => "YSL 熱門唇膏系列，質地滋潤，適合打造光澤感唇妝。",
        "price" => 1500,
        "image_url" => "https://placehold.co/600x400/fde8ef/b94a95?text=YSL+Lipstick",
        "stock" => 18
    ],
    [
        "name" => "MAC 子彈唇膏 Ruby Woo",
        "description" => "MAC 經典霧面紅唇色，適合正式妝容、復古妝感與氣場穿搭。",
        "price" => 850,
        "image_url" => "https://placehold.co/600x400/ffe4ef/c94f7c?text=MAC+Ruby+Woo",
        "stock" => 25
    ],
    [
        "name" => "NARS Radiant Creamy Concealer 遮瑕蜜",
        "description" => "NARS 熱門遮瑕產品，可修飾黑眼圈、痘疤與局部暗沉，妝感自然。",
        "price" => 1200,
        "image_url" => "https://placehold.co/600x400/f8efff/b94a95?text=NARS+Concealer",
        "stock" => 15
    ],
    [
        "name" => "Maybelline Fit Me 反孔特霧粉底液",
        "description" => "Maybelline 開架熱銷粉底液，適合油肌與混合肌，妝感偏霧面。",
        "price" => 450,
        "image_url" => "https://placehold.co/600x400/fff7fa/c94f7c?text=Maybelline+Fit+Me",
        "stock" => 35
    ],
    [
        "name" => "L'Oréal Paris Infallible 持久粉底液",
        "description" => "L'Oréal Paris 熱門底妝商品，主打持妝與遮瑕，適合日常通勤妝。",
        "price" => 650,
        "image_url" => "https://placehold.co/600x400/e8ddff/b94a95?text=LOreal+Foundation",
        "stock" => 28
    ],
    [
        "name" => "rom&nd 果汁唇釉 Juicy Lasting Tint",
        "description" => "韓國 rom&nd 人氣唇釉，玻璃唇妝感明顯，適合甜美與韓系妝容。",
        "price" => 350,
        "image_url" => "https://placehold.co/600x400/ffdbe8/c94f7c?text=romand+Tint",
        "stock" => 40
    ],
    [
        "name" => "3CE Blur Water Tint 霧面水唇釉",
        "description" => "3CE 熱門唇彩，質地輕薄，適合柔霧感妝容與韓系穿搭。",
        "price" => 590,
        "image_url" => "https://placehold.co/600x400/fff0f6/b94a95?text=3CE+Tint",
        "stock" => 30
    ],
    [
        "name" => "CLIO Kill Cover Mesh Glow Cushion 氣墊粉餅",
        "description" => "CLIO 人氣氣墊粉餅，妝感帶光澤，適合想要快速完成底妝的使用者。",
        "price" => 790,
        "image_url" => "https://placehold.co/600x400/f8efff/c94f7c?text=CLIO+Cushion",
        "stock" => 22
    ],
    [
        "name" => "CANMAKE Cream Cheek 霜狀腮紅",
        "description" => "CANMAKE 經典開架腮紅，質地服貼，適合打造自然紅潤氣色。",
        "price" => 320,
        "image_url" => "https://placehold.co/600x400/ffe4ef/b94a95?text=CANMAKE+Cheek",
        "stock" => 32
    ],
    [
        "name" => "ETUDE Fixing Tint 霧面唇釉",
        "description" => "ETUDE 熱門韓系唇釉，妝感柔霧，適合日常妝與學生族群。",
        "price" => 420,
        "image_url" => "https://placehold.co/600x400/fff7fa/c94f7c?text=ETUDE+Fixing+Tint",
        "stock" => 26
    ],
    [
        "name" => "peripera Ink Mood Glowy Tint 唇釉",
        "description" => "peripera 人氣光澤唇釉，顏色活潑，適合甜美、清新與韓系妝容。",
        "price" => 360,
        "image_url" => "https://placehold.co/600x400/ffdbe8/b94a95?text=peripera+Tint",
        "stock" => 38
    ]
];

$inserted = 0;
$skipped = 0;

foreach ($products as $product) {

    // 避免重複上架同名商品
    $check = $conn->prepare("SELECT id FROM products WHERE name = ? LIMIT 1");
    $check->bind_param("s", $product["name"]);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        $skipped++;
        $check->close();
        continue;
    }

    $check->close();

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, stock, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param(
        "ssisi",
        $product["name"],
        $product["description"],
        $product["price"],
        $product["image_url"],
        $product["stock"]
    );

    if ($stmt->execute()) {
        $inserted++;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>商品上架完成</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">
    <div class="container">

        <div class="badge">Product Seeder</div>

        <h1>商品上架完成</h1>

        <p class="subtitle">
            系統已自動新增一批化妝品商品。
        </p>

        <div class="analysis-card">
            <h2>上架結果</h2>
            <div class="card-content">
                成功新增商品數量：<?php echo $inserted; ?> 筆<br>
                已存在而略過數量：<?php echo $skipped; ?> 筆<br><br>
                若要修改商品圖片、價格或庫存，請前往商品管理頁調整。
            </div>
        </div>

        <a href="shop.php" class="back">前往商品頁</a>
        <a href="admin_products.php" class="back">前往商品管理</a>

    </div>
</div>

</body>
</html>