<?php
session_start();

$error_msg = "";

$conn = new mysqli("localhost", "root", "1133377", "color");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 自動建立會員資料表，不影響原本系統資料表
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
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm_password === '') {
        $error_msg = "請完整輸入註冊資料。";
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $error_msg = "帳號請使用 3～20 個英文字母、數字或底線。";
    } elseif (strlen($password) < 6) {
        $error_msg = "密碼至少需要 6 個字元。";
    } elseif ($password !== $confirm_password) {
        $error_msg = "兩次輸入的密碼不一致。";
    } elseif ($username === 'admin') {
        $error_msg = "admin 為系統保留帳號，請使用其他帳號。";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "這個帳號已經被註冊，請換一個帳號。";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";

            $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashedPassword, $role);

            if ($insert->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error_msg = "註冊失敗，請稍後再試。";
            }

            $insert->close();
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
    <title>會員註冊 | ColorMe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">
    <div class="container" style="max-width: 450px;">

        <div class="badge">Member Register</div>

        <h1 style="font-size: 28px;">建立新帳號</h1>

        <p class="subtitle" style="margin-bottom: 25px;">
            註冊後即可使用會員身分登入系統
        </p>

        <?php if($error_msg): ?>
            <div style="background: #ffe8e8; color: #d93025; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                <?php echo htmlspecialchars($error_msg); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:8px; color:#444; font-weight:bold;">
                    帳號：
                </label>
                <input
                    type="text"
                    name="username"
                    placeholder="3～20 個英文、數字或底線"
                    required
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e7bfd1; outline: none;"
                >
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:8px; color:#444; font-weight:bold;">
                    密碼：
                </label>
                <input
                    type="password"
                    name="password"
                    placeholder="至少 6 個字元"
                    required
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e7bfd1; outline: none;"
                >
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display:block; margin-bottom:8px; color:#444; font-weight:bold;">
                    確認密碼：
                </label>
                <input
                    type="password"
                    name="confirm_password"
                    required
                    style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #e7bfd1; outline: none;"
                >
            </div>

            <button type="submit" style="width: 100%; margin-top: 0;">
                註冊
            </button>

        </form>

        <div style="margin-top: 22px; text-align: center; line-height: 1.8;">
            <a href="login.php" style="color: #c94f7c; text-decoration: none; font-size: 14px; font-weight: bold;">
                已有帳號？前往登入
            </a>
            <br>
            <a href="index.php" style="color: #888; text-decoration: none; font-size: 14px;">
                ← 返回首頁
            </a>
        </div>

    </div>
</div>

</body>
</html>