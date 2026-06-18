<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "1133377", "color");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS analysis_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    analysis_result LONGTEXT NOT NULL,
    photo_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT id, username, analysis_result, photo_count, created_at FROM analysis_records WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的分析紀錄</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .history-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 24px 28px;
            margin-bottom: 18px;
            border: 1px solid #f3c5d8;
            box-shadow: 0 12px 30px rgba(190,80,130,0.13);
            position: relative;
            overflow: hidden;
        }

        .history-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(180deg, #d76091, #b94a95);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            padding-left: 8px;
        }

        .history-title {
            flex: 1;
        }

        .history-title h2 {
            margin: 0 0 8px;
            color: #b93f72;
            font-size: 22px;
        }

        .history-meta {
            color: #777;
            font-size: 14px;
            line-height: 1.8;
        }

        .history-summary {
            margin-top: 14px;
            padding-left: 8px;
            color: #555;
            font-size: 15px;
            line-height: 1.8;
        }

        .toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid #f4c6d8;
            background: #fff0f6;
            color: #c94f7c;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toggle-btn:hover {
            background: #ffe4ef;
            transform: translateY(-2px);
        }

        .toggle-btn.open {
            transform: rotate(180deg);
            background: #d76091;
            color: white;
        }

        .history-detail {
            display: none;
            margin-top: 20px;
            padding: 22px;
            border-radius: 18px;
            background: #fff8fb;
            border: 1px solid #f4c6d8;
            color: #444;
            line-height: 1.8;
            white-space: normal;
        }

        .history-detail.show {
            display: block;
        }

        @media(max-width:768px) {
            .history-card {
                padding: 22px 18px;
            }

            .history-title h2 {
                font-size: 18px;
            }

            .toggle-btn {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .history-detail {
                padding: 18px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="container result-container">

        <div style="text-align: right; margin-bottom: 15px;">
            <a href="index.php" style="display: inline-block; padding: 8px 16px; background: #fff0f6; color: #c94f7c; border-radius: 999px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #f4c6d8;">
                回首頁
            </a>

            <a href="logout.php" style="display: inline-block; margin-left: 10px; padding: 8px 16px; background: #fff0f6; color: #c94f7c; border-radius: 999px; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #f4c6d8;">
                登出
            </a>
        </div>

        <div class="badge">
            Analysis History
        </div>

        <h1>我的分析紀錄</h1>

        <p class="subtitle">
            這裡會顯示你登入後產生的 AI 色彩、妝容與臉型分析紀錄。
            點擊向下箭頭即可展開完整分析結果。
        </p>

        <?php if ($result->num_rows === 0): ?>

            <div class="analysis-card">
                <h2>尚無分析紀錄</h2>
                <div class="card-content">
                    目前還沒有儲存任何分析紀錄。<br>
                    請先回首頁上傳照片並完成 AI 分析。
                </div>
            </div>

        <?php else: ?>

            <div class="result-card-wrapper">

                <?php while ($row = $result->fetch_assoc()): ?>

                    <?php
                        $fullText = $row['analysis_result'];

                        // 做一段簡短摘要，先去除換行與多餘空白
                        $plainText = trim(preg_replace('/\s+/', ' ', $fullText));

                        // 摘要最多顯示 80 個中文字左右
                        if (mb_strlen($plainText, 'UTF-8') > 80) {
                            $summary = mb_substr($plainText, 0, 80, 'UTF-8') . "……";
                        } else {
                            $summary = $plainText;
                        }
                    ?>

                    <div class="history-card">

                        <div class="history-header" onclick="toggleHistory(this)">

                            <div class="history-title">

                                <h2>
                                    分析時間：<?php echo htmlspecialchars($row['created_at']); ?>
                                </h2>

                                <div class="history-meta">
                                    上傳照片數量：
                                    <strong style="color:#b93f72;">
                                        <?php echo htmlspecialchars($row['photo_count']); ?> 張
                                    </strong>
                                </div>

                            </div>

                            <button type="button" class="toggle-btn">
                                ↓
                            </button>

                        </div>

                        <div class="history-summary">
                            <strong style="color:#b93f72;">簡略摘要：</strong>
                            <?php echo htmlspecialchars($summary); ?>
                        </div>

                        <div class="history-detail">
                            <?php echo nl2br(htmlspecialchars($row['analysis_result'])); ?>
                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>

        <a href="index.php" class="back">
            回首頁
        </a>

    </div>
</div>

<script>
function toggleHistory(header) {
    const card = header.closest(".history-card");
    const detail = card.querySelector(".history-detail");
    const button = card.querySelector(".toggle-btn");

    detail.classList.toggle("show");
    button.classList.toggle("open");

    if (detail.classList.contains("show")) {
        button.innerText = "↑";
    } else {
        button.innerText = "↓";
    }
}
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>