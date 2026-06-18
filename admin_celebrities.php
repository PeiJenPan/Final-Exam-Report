<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$conn = new mysqli("localhost", "root", "1133377", "color");

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 刪除案例
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $res = $conn->query("SELECT image_path FROM celebrity_analysis WHERE id = $id");

    if ($res && $row = $res->fetch_assoc()) {
        $images = json_decode($row['image_path'], true);

        if (!is_array($images)) {
            $images = [$row['image_path']];
        }

        foreach ($images as $img) {
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }

    $conn->query("DELETE FROM celebrity_analysis WHERE id = $id");
    header("Location: admin_celebrities.php");
    exit;
}

// 新增明星 AI 分析
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    set_time_limit(300);

    $name = trim($_POST['name']);

    if ($name === "") {
        die("請輸入明星姓名。");
    }

    if (!isset($_FILES["images"])) {
        die("請上傳明星照片。");
    }

    $files = $_FILES["images"];
    $totalFiles = count($files["name"]);

    if ($totalFiles < 1 || $totalFiles > 3) {
        die("請上傳 1～3 張照片。");
    }

    $allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    $target_dir = "uploads/celebrities/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $imageParts = [];
    $savedImages = [];

    for ($i = 0; $i < $totalFiles; $i++) {

        if ($files["error"][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmpName = $files["tmp_name"][$i];
        $mime = mime_content_type($tmpName);

        if (!in_array($mime, $allowedTypes)) {
            continue;
        }

        $ext = pathinfo($files["name"][$i], PATHINFO_EXTENSION);
        $file_name = time() . "_" . uniqid() . "." . $ext;
        $target_file = $target_dir . $file_name;

        if (!move_uploaded_file($tmpName, $target_file)) {
            continue;
        }

        $savedImages[] = $target_file;

        $imageData = base64_encode(file_get_contents($target_file));

        $imageParts[] = [
            "inline_data" => [
                "mime_type" => $mime,
                "data" => $imageData
            ]
        ];
    }

    if (count($imageParts) === 0) {
        die("沒有可分析的圖片。");
    }

    $imagePathForDB = json_encode($savedImages, JSON_UNESCAPED_SLASHES);

    /*
        建議正式使用時改成環境變數：
        $apiKey = getenv("GEMINI_API_KEY");
    */
    $apiKey = "AIzaSyBmGStJbBW9JNfv--8P_NGLCmtjS9zZzQ0";

    if (empty($apiKey)) {
    die("請先在 analyze.php 裡設定 Gemini API Key。");
}

    $prompt = "
你現在是一位專業的 AI 色彩與妝容顧問。

請根據管理員上傳的 1～3 張明星照片，
產生「簡短版明星色彩與妝容參考」。

這份內容是提供一般使用者參考靈感，
不是完整個人分析。

【重要規則】

1. 回答要簡潔。
2. 不要寫太長的分析。
3. 不要解釋太多原因。
4. 不要出現『判斷原因』。
5. 不要出現長篇敘述。
6. 不要出現醫療或敏感推測。
7. 不要有開場白。
8. 不要說『您好』、『以下是分析』。
9. 不要超過 4 個區塊。

請直接輸出以下格式：

## 1. 色彩類型
- 色彩季型：
- 適合色調：
- 關鍵色彩：

## 2. 妝容方向
- 底妝：
- 眼妝：
- 腮紅：
- 唇彩：
- 整體妝感：

## 3. 推薦色票
請提供 5 個適合色彩。

格式必須如下：

[COLOR:#HEX色碼|中文色名]
[COLOR:#HEX色碼|中文色名]
[COLOR:#HEX色碼|中文色名]
[COLOR:#HEX色碼|中文色名]
[COLOR:#HEX色碼|中文色名]

## 4. 風格關鍵字
- 風格：
- 適合穿搭：
- 適合髮色：

請保持：
簡潔、好閱讀、像明星風格參考卡。

所有顏色都要附上 #HEX 色碼。
";

    $parts = array_merge(
        [
            [
                "text" => $prompt
            ]
        ],
        $imageParts
    );

    $data = [
        "contents" => [
            [
                "parts" => $parts
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.6,
            "topK" => 40,
            "topP" => 0.9,
            "maxOutputTokens" => 4096
        ]
    ];

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die("AI 分析失敗：" . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        echo "<pre>";
        echo "Gemini API 發生錯誤：\n\n";
        echo htmlspecialchars($response);
        echo "</pre>";
        exit;
    }

    $content =
        $result["candidates"][0]["content"]["parts"][0]["text"]
        ?? "AI 無法產生分析內容。";

    $stmt = $conn->prepare("INSERT INTO celebrity_analysis (name, image_path, content) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $imagePathForDB, $content);
    $stmt->execute();

    header("Location: admin_celebrities.php");
    exit;
}

$celebrities = $conn->query("SELECT * FROM celebrity_analysis ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>明星案例管理</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .admin-form {
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            border: 1px solid #f3c5d8;
            margin-bottom: 30px;
        }

        .admin-form input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 16px;
        }

        .admin-form small {
            display: block;
            color: #888;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .admin-form button {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .celeb-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .celeb-item {
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #eee;
        }

        .celeb-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .delete-link {
            color: red;
            font-size: 12px;
            text-decoration: none;
        }

        .view-link {
            display: inline-block;
            margin-top: 8px;
            color: #c94f7c;
            font-size: 13px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="page">
    <div class="container">

        <h1>明星色彩分析管理</h1>

        <div class="admin-form">
            <h3>新增明星 AI 分析</h3>

            <small>
                最多可上傳 3 張明星照片。<br>
                建議包含：正面照、45° 側臉、自然光照片。<br><br>
                系統會自動使用 AI 產生「簡短版色彩與妝容分析」，
                並儲存到明星案例庫提供使用者參考。
            </small>

            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="明星姓名" required>

                <input
                    type="file"
                    name="images[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    required
                >

                <div style="text-align:center;">
                    <button type="submit">
                        上傳並產生 AI 分析
                    </button>
                </div>
            </form>
        </div>

        <h3>目前已發布案例</h3>

        <div class="celeb-list">
            <?php while($row = $celebrities->fetch_assoc()): ?>

                <?php
                $images = json_decode($row['image_path'], true);

                if (!is_array($images)) {
                    $images = [$row['image_path']];
                }

                $cover = $images[0];
                ?>

                <div class="celeb-item">
                    <img src="<?php echo htmlspecialchars($cover); ?>">

                    <h4><?php echo htmlspecialchars($row['name']); ?></h4>

                    <a class="view-link" href="view_celebrity.php?id=<?php echo $row['id']; ?>">
                        查看分析
                    </a>
                    <br>

                    <a class="delete-link" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('確定要刪除這個案例嗎？');">
                        刪除案例
                    </a>
                </div>

            <?php endwhile; ?>
        </div>

        <div style="text-align:center;">
            <a href="index.php" class="back">返回首頁</a>
        </div>

    </div>
</div>

</body>
</html>