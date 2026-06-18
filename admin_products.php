<?php
session_start();
require_once "shop_db.php";
requireAdminForShop();
$conn = getShopDB();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = intval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;

        if ($name !== '' && $price >= 0 && $stock >= 0) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, stock, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisii", $name, $description, $price, $imageUrl, $stock, $active);
            $stmt->execute();
            $stmt->close();
            $msg = "商品已上架。";
        }
    }

    if (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = intval($_POST['price'] ?? 0);
        $stock = intval($_POST['stock'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=?, stock=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssisiii", $name, $description, $price, $imageUrl, $stock, $active, $id);
        $stmt->execute();
        $stmt->close();
        $msg = "商品已更新。";
    }

    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $msg = "商品已刪除。";
    }
}

$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>
<!DOCTYPE html><html lang="zh-Hant"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>管理商品</title><link rel="stylesheet" href="style.css"><style>.nav{text-align:right;margin-bottom:15px}.nav a{display:inline-block;margin-left:8px;padding:8px 16px;background:#fff0f6;color:#c94f7c;border-radius:999px;text-decoration:none;font-weight:bold;font-size:14px;border:1px solid #f4c6d8}.box{background:white;border-radius:24px;padding:24px;margin-bottom:22px;border:1px solid #f3c5d8;box-shadow:0 12px 30px rgba(190,80,130,.12)}.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}.field label{display:block;margin-bottom:6px;font-weight:bold;color:#444}.field input,.field textarea{width:100%;padding:11px;border-radius:12px;border:1px solid #e7bfd1}.btn{padding:11px 18px;border:none;border-radius:12px;background:#d76091;color:white;font-weight:bold;cursor:pointer}.danger{background:#d93025}.product-admin{border-top:1px solid #f4d3e2;padding-top:18px;margin-top:18px}@media(max-width:768px){.grid{grid-template-columns:1fr}}</style></head><body>
<div class="page"><div class="container result-container">
    <div class="nav"><a href="shop.php">商品頁</a><a href="admin_orders.php">訂單管理</a><a href="index.php">回首頁</a></div>
    <div class="badge">Admin Products</div><h1>商品管理</h1>
    <?php if($msg): ?><div class="box" style="color:#188038;text-align:center;"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
    <div class="box">
        <h2 style="color:#b93f72;">上架新商品</h2>
        <form method="POST">
            <div class="grid"><div class="field"><label>商品名稱</label><input name="name" required></div><div class="field"><label>價格</label><input type="number" name="price" min="0" required></div><div class="field"><label>庫存</label><input type="number" name="stock" min="0" required></div><div class="field"><label>圖片網址或路徑</label><input name="image_url" placeholder="例如 images/lipstick.jpg"></div></div>
            <div class="field" style="margin-top:14px;"><label>商品描述</label><textarea name="description" rows="3"></textarea></div>
            <label><input type="checkbox" name="is_active" checked> 顯示於商品頁</label><br><br>
            <button class="btn" name="create" value="1" type="submit">新增商品</button>
        </form>
    </div>
    <div class="box"><h2 style="color:#b93f72;">商品列表</h2>
    <?php while($p=$products->fetch_assoc()): ?>
        <form class="product-admin" method="POST">
            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
            <div class="grid"><div class="field"><label>商品名稱</label><input name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required></div><div class="field"><label>價格</label><input type="number" name="price" value="<?php echo $p['price']; ?>" min="0" required></div><div class="field"><label>庫存</label><input type="number" name="stock" value="<?php echo $p['stock']; ?>" min="0" required></div><div class="field"><label>圖片網址或路徑</label><input name="image_url" value="<?php echo htmlspecialchars($p['image_url']); ?>"></div></div>
            <div class="field" style="margin-top:14px;"><label>商品描述</label><textarea name="description" rows="3"><?php echo htmlspecialchars($p['description']); ?></textarea></div>
            <label><input type="checkbox" name="is_active" <?php echo $p['is_active']?'checked':''; ?>> 顯示於商品頁</label><br><br>
            <button class="btn" name="update" value="1" type="submit">更新</button>
            <button class="btn danger" name="delete" value="1" type="submit" onclick="return confirm('確定刪除這項商品嗎？')">刪除</button>
        </form>
    <?php endwhile; ?>
    </div>
</div></div></body></html>
<?php $conn->close(); ?>
