<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("請使用正確方式上傳照片。");
}

if (!isset($_FILES["photos"])) {
    die("未收到照片。");
}

$files = $_FILES["photos"];
$totalFiles = count($files["name"]);

if ($totalFiles < 1 || $totalFiles > 5) {
    die("請上傳 1～5 張照片。");
}

$allowedTypes = [
    "image/jpeg",
    "image/png",
    "image/webp"
];

$imageParts = [];

for ($i = 0; $i < $totalFiles; $i++) {

    if ($files["error"][$i] !== UPLOAD_ERR_OK) {
        continue;
    }

    $tmpName = $files["tmp_name"][$i];

    $mime = mime_content_type($tmpName);

    if (!in_array($mime, $allowedTypes)) {
        continue;
    }

    $imageData = base64_encode(file_get_contents($tmpName));

    $imageParts[] = [
        "inline_data" => [
            "mime_type" => $mime,
            "data" => $imageData
        ]
    ];
}

if (count($imageParts) === 0) {
    die("沒有可分析的圖片，請確認圖片格式為 JPG、PNG 或 WEBP。");
}

/*
    Gemini API Key
    實際金鑰放在 secret.php
    secret.php 不上傳 GitHub
*/
require_once __DIR__ . "/secret.php";

$apiKey = $geminiApiKey ?? "";

if (empty($apiKey)) {
    die("請先在 secret.php 裡設定 Gemini API Key。");
}

$prompt = '
你現在是一位頂級的「AI 色彩分析師、彩妝顧問與個人形象風格顧問」。
請根據使用者上傳的 1～5 張照片，進行完整且細緻的個人色彩、臉型、五官、妝容、髮型、穿搭與配件分析。

你的回答會被 PHP 系統切成卡片顯示，因此「標題格式非常重要」。
請務必完全按照指定的 ## 編號標題輸出，不要自行新增或刪除大標題。

【重要判斷原則】
1. 請綜合所有照片判斷，不要只看單一照片。
2. 如果照片角度、光線或濾鏡不同，請以「五官最清楚、光線最自然、膚色最不受陰影影響」的照片作為主要判斷依據。
3. 若照片只有 1 張，請提醒分析可能會受到角度、表情與光線影響。
4. 若照片有 2～3 張，請說明多角度照片有助於提高臉型、五官與膚色判斷準確度。
5. 若照片有 4～5 張，請綜合不同角度與光線後再給出較穩定結論。
6. 所有判斷請使用「可能」、「看起來偏向」、「建議可以」等語氣，不要使用絕對語氣。

【防呆機制與邊界條件】
1. 如果上傳照片中完全沒有人類臉部，請只回覆：
## 0. 無法分析
抱歉，您上傳的照片中無法辨識清楚的人類臉部。請重新上傳包含清晰正面臉部、光線自然、臉部未被遮擋的照片。

2. 如果照片極度昏暗、模糊、臉部被口罩/墨鏡/頭髮大量遮擋，導致無法分析五官與膚色，請只回覆：
## 0. 無法分析
抱歉，您上傳的照片無法清晰辨識臉部特徵或色彩。請重新上傳包含清晰正面臉部、且光線自然的照片。

3. 如果照片可以分析，但品質不是很理想，請不要拒絕分析，而是在「## 1. 照片條件觀察」中提醒準確度可能受到影響。

【輸出格式嚴格要求】
請完全按照以下 ## 標題結構與順序輸出。
每一個段落都要詳細分析。
請不要輸出表格，因為系統會用卡片排版。
請不要輸出 Markdown 表格。
請不要加入未指定的主標題。
請不要在開頭加入「好的」、「以下是」等多餘文字。

## 1. 照片條件觀察
- 照片數量：
- 可用角度：
- 光線狀況：
- 是否可能影響膚色判斷：
- 本次分析基準：
- 準確度提醒：

請說明：
照片是否適合進行臉型、五官與色彩分析。
如果光線偏黃、偏暗、逆光、濾鏡明顯，請具體說明可能造成什麼誤差。

## 2. 臉型分析
- 可能臉型：
- 臉部長寬比例觀察：
- 額頭寬度觀察：
- 顴骨位置觀察：
- 下顎線條觀察：
- 下巴形狀觀察：
- 判斷原因：
- 臉型優勢：
- 臉型修飾方向：

請具體說明為什麼判斷為該臉型。
如果不只一種可能，請寫出「主要偏向」與「次要可能」。

## 3. 五官特徵分析
- 眉眼特色：
- 眼型與眼神風格：
- 鼻部特徵：
- 唇部特徵：
- 臉部留白比例：
- 五官集中度：
- 整體氣質：
- 最適合強調的五官：
- 妝容重點原因：

請分析五官給人的視覺印象，例如柔和、清冷、甜美、成熟、精緻、自然、立體等。
不要使用貶低語氣，請用專業修飾語描述。

## 4. 個人氣質與風格定位
- 主要風格定位：
- 次要風格定位：
- 適合關鍵字：
- 不太適合的風格：
- 判斷原因：

風格定位可從以下方向選擇或組合：
韓系透明感、日系自然感、清冷高級風、甜美純欲風、溫柔奶茶風、港風濃顏感、法式慵懶感、乾淨少年感、知性氣質感、精緻千金感、歐美立體感。

請給出 2～4 個最貼近的風格關鍵字。

## 5. 膚色與色彩分析
- 膚色明暗度：
- 膚色冷暖調：
- 膚色飽和度：
- 膚色乾淨度/透亮感：
- 適合色彩明度：
- 適合色彩飽和度：
- 適合色彩冷暖：
- 判斷依據：
- 光線誤差提醒：

請說明：
為什麼適合偏亮/偏柔/偏暖/偏冷/低飽和/高飽和的顏色。
所有提到的具體顏色，都請附上 #HEX 色碼。

## 6. 四季色彩推測
- 可能季節類型：
- 次要可能季節：
- 季節色彩特徵：
- 適合的色彩方向：
- 不適合的色彩方向：
- 判斷原因：
- 可信度：

請從以下方向判斷：
春季明亮型、春季淺色型、夏季柔和型、夏季冷色型、秋季柔和型、秋季深色型、冬季冷色型、冬季高對比型。

可信度請用百分比表示，例如：可信度約 78%。
如果照片光線不足，可信度不要給太高。

## 7. 專屬命定色票卡
請提供 5 個最適合使用者的核心色彩。
必須嚴格使用以下格式，並且 5 個色票連在同一行：

[COLOR:#HEX色碼|中文色名] [COLOR:#HEX色碼|中文色名] [COLOR:#HEX色碼|中文色名] [COLOR:#HEX色碼|中文色名] [COLOR:#HEX色碼|中文色名]

色票選擇請涵蓋：
- 1 個日常主色
- 1 個氣色提升色
- 1 個眼妝/唇妝適合色
- 1 個穿搭中性色
- 1 個髮色或配件色

請確保 HEX 色碼為 6 碼，例如 #D8A28C。
不要使用 3 碼色碼。

## 8. 修容與打亮建議
- 修容位置：
- 打亮位置：
- 腮紅位置：
- 鼻影位置：
- 下顎修飾：
- 為什麼這樣修容：
- 建議避免的修容方式：

請根據臉型與五官說明修容。
例如：
若臉型偏圓，強調外側陰影與縱向拉長。
若下顎線明顯，避免過重陰影讓輪廓更硬。
若中庭較長，腮紅可橫向縮短視覺比例。

## 9. 妝容建議與產品推薦
請推薦具體的知名化妝品，並估算合理的台灣市售價格 NT$。
每一項必須包含：
- 適合方向
- 推薦產品
- 色號
- 約略價格
- 適合原因
- [BUY:URL]

URL 請使用 Google Shopping 搜尋連結格式。
URL 內空格請用 + 號取代。

請依照以下格式輸出：

- 底妝：
  適合方向：
  推薦：**品牌 品名 色號**（約 NT$價格）[BUY:https://www.google.com/search?tbm=shop&q=品牌+品名+色號]
  適合原因：

- 眼影：
  適合方向：
  推薦：**品牌 品名 色號**（約 NT$價格）[BUY:https://www.google.com/search?tbm=shop&q=品牌+品名+色號]
  適合原因：

- 唇彩：
  適合方向：
  推薦：**品牌 品名 色號**（約 NT$價格）[BUY:https://www.google.com/search?tbm=shop&q=品牌+品名+色號]
  適合原因：

- 腮紅：
  適合方向：
  推薦：**品牌 品名 色號**（約 NT$價格）[BUY:https://www.google.com/search?tbm=shop&q=品牌+品名+色號]
  適合原因：

- 眉彩/眼線：
  適合方向：
  推薦：**品牌 品名 色號**（約 NT$價格）[BUY:https://www.google.com/search?tbm=shop&q=品牌+品名+色號]
  適合原因：

請優先推薦台灣常見品牌，例如：
CANMAKE、CEZANNE、rom&nd、peripera、3CE、dasique、Maybelline、L\'Oréal Paris、KATE、INTEGRATE、MAC、NARS、Dior、YSL、CHANEL、shu uemura、BOBBI BROWN。

不要虛構過於冷門或不存在的產品。
若不確定價格，請用「約 NT$xxx～xxx」。

## 10. 日常妝容步驟
請提供一套適合上課、上班、日常出門的妝容流程。

格式：
- Step 1 底妝：
- Step 2 眉毛：
- Step 3 眼妝：
- Step 4 腮紅：
- Step 5 唇彩：
- Step 6 定妝：
- 妝容完成後的整體效果：

每一步都要說明為什麼適合使用者。

## 11. 約會/正式場合妝容建議
請提供一套比日常妝更精緻的妝容方向。

格式：
- 妝容風格：
- 眼妝加強方式：
- 唇彩加強方式：
- 修容與打亮：
- 適合場合：
- 為什麼適合：

請避免過度濃妝，除非使用者五官條件適合高對比妝容。

## 12. 髮色與髮型建議
- 推薦髮色 1：
- 推薦髮色 2：
- 推薦髮色 3：
- 建議避免髮色：
- 適合髮型長度：
- 適合瀏海：
- 適合捲度：
- 原因：

每個髮色都必須附上 #HEX 色碼。
請說明為什麼該髮色能修飾膚色或提升氣質。

## 13. 穿搭色彩建議
請提供 2 套具體穿搭方案。

格式：
- 穿搭方案 1：
  上身：
  下身：
  外套：
  鞋包：
  主色調：
  適合原因：

- 穿搭方案 2：
  上身：
  下身：
  外套：
  鞋包：
  主色調：
  適合原因：

請包含具體材質與款式，例如：
米白色針織上衣、霧粉色襯衫、奶茶色西裝外套、深咖直筒褲、珍珠耳環、玫瑰金細鍊等。

所有主色調都要附上 #HEX 色碼。

## 14. 飾品與配件建議
- 適合金屬色：
- 適合飾品材質：
- 適合飾品大小：
- 適合眼鏡框色：
- 適合包包色：
- 適合鞋款色：
- 原因：

請判斷適合金飾、銀飾、玫瑰金、珍珠、透明感壓克力、霧面金屬等。
所有具體顏色請附上 #HEX 色碼。

## 15. 建議避免的顏色與風格
- 妝容地雷色：
- 穿搭地雷色：
- 髮色地雷色：
- 不建議妝容風格：
- 不建議穿搭風格：
- 原因：

請說明這些顏色可能造成：
顯黃、顯灰、顯髒、氣色被壓暗、五官變模糊、臉部比例被放大、妝感不協調等問題。

所有地雷色都要附上 #HEX 色碼。

## 16. 總結
- 最適合的整體風格：
- 最推薦的妝容方向：
- 最推薦的穿搭色系：
- 最推薦的髮色：
- 一句鼓勵總結：

最後一句請用溫柔、專業、有記憶點的語氣鼓勵使用者。

【語氣設定】
全程使用繁體中文。
語氣要溫柔、專業、自信，像真人美妝顧問。
請避免使用醫療診斷詞彙。
不要推測種族、具體年齡、性別認同、宗教、政治立場等敏感身份。
不要批評使用者外貌。
請用「修飾」、「凸顯」、「平衡」、「增加氣色」、「提升立體感」等正向詞彙。
所有提到的重點色彩都必須附上 #HEX 色碼。
';

$parts = [];

$parts[] = [
    "text" => $prompt
];

foreach ($imageParts as $imagePart) {
    $parts[] = $imagePart;
}

// 在準備發送請求前，先告訴 PHP 延長執行時間，避免 AI 想太久被砍斷
set_time_limit(600);

$data = [
    "contents" => [
        [
            "parts" => $parts
        ]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "topK" => 40,
        "topP" => 0.95,
        "maxOutputTokens" => 8192 // 稍微調大上限，給 AI 更多發揮空間
    ],
    // 降低安全審查限制，避免分析膚色或臉型時被誤判而中斷
    "safetySettings" => [
        ["category" => "HARM_CATEGORY_HARASSMENT", "threshold" => "BLOCK_NONE"],
        ["category" => "HARM_CATEGORY_HATE_SPEECH", "threshold" => "BLOCK_NONE"],
        ["category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT", "threshold" => "BLOCK_NONE"],
        ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_NONE"]
    ]
];

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

$maxRetries = 3;
$retryDelay = 3; // 每次失敗後等待 3 秒
$response = false;
$httpCode = 0;
$curlError = "";

for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 180);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $curlError = curl_error($ch);
    }

    curl_close($ch);

    // 成功就跳出重試
    if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
        break;
    }

    // 如果是 503，代表 Gemini 忙碌，等待後重試
    if ($httpCode == 503 && $attempt < $maxRetries) {
        sleep($retryDelay);
        continue;
    }

    // 其他錯誤就不重試
    break;
}

if ($response === false) {
    die("AI 分析請求失敗：" . $curlError);
}

$result = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300) {
    $errorMessage = $result["error"]["message"] ?? "未知錯誤";

    echo "<div style='max-width:800px;margin:50px auto;padding:30px;background:#fff0f6;border-radius:20px;font-family:Microsoft JhengHei;'>";
    echo "<h2 style='color:#b93f72;'>AI 目前忙碌中</h2>";
    echo "<p>Gemini API 目前使用量較高，暫時無法完成分析。</p>";
    echo "<p>錯誤訊息：" . htmlspecialchars($errorMessage) . "</p>";
    echo "<a href='index.php' style='display:inline-block;margin-top:20px;padding:12px 24px;background:#d76091;color:white;border-radius:999px;text-decoration:none;'>回首頁重新分析</a>";
    echo "</div>";
    exit;
}

$analysis =
    $result["candidates"][0]["content"]["parts"][0]["text"]
    ?? "無法取得分析結果。";

// ================================
// 已登入使用者才儲存分析紀錄
// 未登入者不儲存
// ================================
if (isset($_SESSION['username'])) {

    $conn = new mysqli("localhost", "root", "1133377", "color");

    if (!$conn->connect_error) {

        $conn->set_charset("utf8mb4");

        $conn->query("CREATE TABLE IF NOT EXISTS analysis_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            analysis_result LONGTEXT NOT NULL,
            photo_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $username = $_SESSION['username'];
        $photoCount = $totalFiles;

        $stmt = $conn->prepare("INSERT INTO analysis_records (username, analysis_result, photo_count) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssi", $username, $analysis, $photoCount);
            $stmt->execute();
            $stmt->close();
        }

        $conn->close();
    }
}
    $sections = preg_split('/(?=##\s*\d+\.)/', $analysis);

$mainTitle = "";
$cards = [];

foreach ($sections as $section) {
    $section = trim($section);

    if ($section === "") {
        continue;
    }

    if (str_starts_with($section, "# AI")) {
        $lines = explode("\n", $section);
        $mainTitle = trim(str_replace("#", "", $lines[0]));

        $remaining = trim(implode("\n", array_slice($lines, 1)));

        if ($remaining !== "") {
            $cards[] = $remaining;
        }
    } else {
        $cards[] = $section;
    }
}

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI 分析結果</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">

    <div class="container result-container">

        <div class="badge">
            Analysis Result
        </div>

        <h1>
            AI 分析結果
        </h1>

        <div class="result-card-wrapper">

    <?php foreach ($cards as $card): ?>
        <?php
        $lines = explode("\n", trim($card));
        $title = trim($lines[0]);
        $content = trim(implode("\n", array_slice($lines, 1)));
        $title = preg_replace('/^##\s*/', '', $title);
        
        $content = htmlspecialchars($content);

        // A. 處理色票卡 [COLOR:#色碼|名稱]
        if (preg_match_all('/\[COLOR:(#[A-Fa-f0-9]{6})\|(.*?)\]/', $content, $matches)) {
            $swatchHtml = '<div class="swatch-container">';
            for ($i = 0; $i < count($matches[0]); $i++) {
                $color = $matches[1][$i];
                $name = $matches[2][$i];
                
                // 魔法小技巧：將顯示用的 # 換成 HTML 實體 &#35;，避免被 C 步驟的正則表達式誤傷
                $displayText = '&#35;' . ltrim($color, '#'); 
                
                $swatchHtml .= "<div class='swatch-card'><div class='swatch-color' style='background-color:$color;'></div><div class='swatch-label'><strong>$name</strong><br>$displayText</div></div>";
            }
            $swatchHtml .= '</div>';
            // 移除原始標籤並加入生成的 HTML
            $content = preg_replace('/\[COLOR:.*?\]/', '', $content) . $swatchHtml;
        }

        // B. 處理價格與購買連結 [BUY:URL]
        // 允許「NT$」與「數字」之間有空格，並且允許括號有無
// B. 處理價格與購買連結 [BUY:URL] (支援價格區間如 ~ 或 -)
        $content = preg_replace('/(NT\$[0-9\s,~\-]+)/', '<span class="price-tag">$1</span>', $content);
        $content = preg_replace('/\[BUY:(.*?)\]/', '<a href="$1" target="_blank" class="buy-link">🛒 立即查看價格</a>', $content);

        // C. 處理原本的色碼小方塊 (加入負向預查防呆，嚴格避開 HTML 屬性內的色碼)
        $content = preg_replace('/(?<![:"\'=])(#[A-Fa-f0-9]{6})\b/i', '$1 <span style="display:inline-block; width:14px; height:14px; background-color:$1; border-radius:3px; vertical-align:-2px; border:1px solid #ddd;"></span>', $content);

        // D. 粗體與清單 (現有功能保留)
        $content = preg_replace('/\*\*(.*?)\*\*/s', '<strong style="color: #b93f72;">$1</strong>', $content);
        $content = preg_replace('/^[-*]\s+/m', '<span style="color: #d76091; font-weight: bold; margin-right: 5px;">•</span> ', $content);
        ?>

        <div class="analysis-card">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <div class="card-content">
                <?php echo nl2br($content); ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>

        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 40px; padding-bottom: 10px; flex-wrap: wrap;">
            
            <button id="download-btn" onclick="downloadReport()" class="back" style="margin: 0; background: linear-gradient(135deg, #d76091, #b94a95); color: white; border: none; cursor: pointer; box-shadow: 0 8px 20px rgba(215,96,145,0.2);">
                📥 下載完整報告 (圖片)
            </button>
            
            <a href="index.php" class="back" style="margin: 0;">重新上傳照片</a>
            
        </div>

    </div> </div> ```

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadReport() {
    const btn = document.getElementById('download-btn');
    const originalText = btn.innerText;
    
    // 改變按鈕狀態
    btn.innerText = "⏳ 重點圖片產生中...";
    btn.style.opacity = "0.7";
    btn.disabled = true;

    // 1. 取得所有報告卡片
    const cards = document.querySelectorAll('.analysis-card');
    const hiddenCards = []; // 用來記錄被隱藏的卡片

    // 2. 設定你要保留在截圖中的「重點關鍵字」
    // (只要卡片的標題包含這些字，就會被截圖，其他的會被暫時隱藏)
    const keyKeywords = ['色票', '風格', '四季', '穿搭', '產品推薦', '總結'];

    // 3. 掃描所有卡片，隱藏非重點項目
    cards.forEach(card => {
        const title = card.querySelector('h2').innerText;
        // 檢查標題是否有命中關鍵字
        const isKeyPoint = keyKeywords.some(keyword => title.includes(keyword));
        
        if (!isKeyPoint) {
            card.style.display = 'none'; // 暫時隱藏非重點卡片
            hiddenCards.push(card);      // 加入恢復名單
        }
    });

    // 4. 指定截圖範圍 (只框選卡片區塊)
    const area = document.querySelector('.result-card-wrapper');

    // 5. 執行截圖
    html2canvas(area, { 
        scale: window.devicePixelRatio || 2, 
        backgroundColor: "#fff7fa", // 補上網頁背景色
        useCORS: true 
    }).then(canvas => {
        // 觸發下載
        const link = document.createElement('a');
        link.download = '專屬色彩分析重點精華.png';
        link.href = canvas.toDataURL('image/png', 1.0);
        link.click();
        
        // --- 截圖完成，立刻恢復原本隱藏的卡片 ---
        hiddenCards.forEach(card => {
            card.style.display = 'block';
        });

        // 恢復按鈕狀態
        btn.innerText = originalText;
        btn.style.opacity = "1";
        btn.disabled = false;
        
    }).catch(err => {
        alert("產生圖片失敗，請稍後再試！");
        
        // 發生錯誤也要記得恢復卡片顯示
        hiddenCards.forEach(card => {
            card.style.display = 'block';
        });
        btn.innerText = originalText;
        btn.style.opacity = "1";
        btn.disabled = false;
    });
}
</script>

</body>
</html>