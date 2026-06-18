<?php
session_start();

$error_msg = "";
$success_msg = isset($_GET['registered']) ? "註冊成功，請使用剛剛建立的帳號登入。" : "";

$conn = new mysqli("localhost", "root", "1133377", "color");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 自動建立會員資料表，不影響原本 celebrity_analysis 等資料表
$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error_msg = "請輸入帳號與密碼。";
    } else {

        // 保留原本管理員快速登入設定，不影響老師測試
        if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['role'] = 'admin';
    $_SESSION['username'] = '超級管理員';

    header("Location: admin_dashboard.php");
    exit;
}

        $stmt = $conn->prepare("SELECT username, password, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if (password_verify($password, $row['password'])) {
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                if ($row['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }

                exit;
            } else {
                $error_msg = "帳號或密碼錯誤，請重新輸入。";
            }

        } else {
            $error_msg = "帳號或密碼錯誤，請重新輸入。";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系統登入 | ColorMe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">
    <div class="container" style="max-width: 450px;">

        <div class="badge">System Login</div>

        <h1 style="font-size: 28px;">歡迎回來</h1>

        <p class="subtitle" style="margin-bottom: 25px;">
            請輸入您的帳號密碼登入系統
        </p>

        <?php if($success_msg): ?>
            <div style="background: #e9f8ef; color: #188038; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                <?php echo htmlspecialchars($success_msg); ?>
            </div>
        <?php endif; ?>

        <?php if($error_msg): ?>
            <div style="background: #ffe8e8; color: #d93025; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                <?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:8px; color:#444; font-weight:bold;">
                    帳號：
                </label>
                <input
                    type="text"
                    name="username"
                    required
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e7bfd1; outline: none;"
                >
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom:8px; color:#444; font-weight:bold;">
                    密碼：
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e7bfd1; outline: none;"
                >
            </div>

            <button type="submit" style="width: 100%; margin-top: 0;">
                登入
            </button>

        </form>

        <div style="margin-top: 22px; text-align: center; line-height: 1.8;">
            <a href="register.php" style="color: #c94f7c; text-decoration: none; font-size: 14px; font-weight: bold;">
                還沒有帳號？前往註冊
            </a>
            <br>
            <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px;">
                ← 先不登入，返回首頁
            </a>
        </div>

        <div style="margin-top: 22px; font-size: 13px; color: #888; text-align: center; line-height: 1.6;">
            <strong>【管理員測試帳號】</strong><br>
            admin / admin123
        </div>

    </div>
</div>

</body>
</html>