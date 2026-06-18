<?php
// shop_db.php
// 電商模組共用資料庫連線與資料表建立

function getShopDB() {
    $conn = new mysqli("localhost", "root", "1133377", "color");

    if ($conn->connect_error) {
        die("資料庫連線失敗：" . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
    createShopTables($conn);
    return $conn;
}

function createShopTables($conn) {
    $conn->query("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price INT NOT NULL DEFAULT 0,
        image_url VARCHAR(255),
        stock INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $conn->query("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        phone VARCHAR(30) NOT NULL,
        address VARCHAR(255) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        total_amount INT NOT NULL DEFAULT 0,
        status VARCHAR(30) NOT NULL DEFAULT '已成立',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $conn->query("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(100) NOT NULL,
        price INT NOT NULL DEFAULT 0,
        quantity INT NOT NULL DEFAULT 1,
        subtotal INT NOT NULL DEFAULT 0,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function requireLoginForShop() {
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('請先登入帳號，登入後才能使用購物車功能。'); window.location.href='login.php';</script>";
        exit;
    }
}

function requireAdminForShop() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo "<script>alert('此頁面限管理員使用。'); window.location.href='index.php';</script>";
        exit;
    }
}

function cartCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    return array_sum($_SESSION['cart']);
}
?>
