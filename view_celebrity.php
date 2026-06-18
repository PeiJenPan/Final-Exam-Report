<?php
$conn = new mysqli("localhost", "root", "1133377", "color");

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM celebrity_analysis WHERE id = $id");
$celeb = $res->fetch_assoc();

if (!$celeb) {
    die("找不到該案例。");
}

$images = json_decode($celeb['image_path'], true);

if (!is_array($images)) {
    $images = [$celeb['image_path']];
}

$analysis = $celeb['content'];
$sections = preg_split('/(?=##\s*\d+\.)/', $analysis);

$cards = [];
foreach ($sections as $section) {
    $section = trim($section);
    if ($section !== "") {
        $cards[] = $section;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($celeb['name']); ?> 的色彩分析</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="page">
    <div class="container result-container">

        <div class="badge">Star Analysis</div>

        <h1 style="margin-bottom:10px;">
            <?php echo htmlspecialchars($celeb['name']); ?>
        </h1>

        <div style="display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; flex-wrap: nowrap;">
            <?php foreach ($images as $img): ?>
                <img src="<?php echo htmlspecialchars($img); ?>" style="flex: 1; min-width: 0; max-width: 300px; aspect-ratio: 1/1; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <?php endforeach; ?>
        </div>

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
            $contentWithoutColor = preg_replace('/\[COLOR:.*?\]\s*/', '', $content);
$contentWithoutColor = trim($contentWithoutColor);
$contentWithoutColor = preg_replace("/\n{3,}/", "\n\n", $contentWithoutColor);

if ($contentWithoutColor !== "") {
    $content = $contentWithoutColor . "\n" . $swatchHtml;
} else {
    $content = $swatchHtml;
}
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
                <?php echo $content; ?>
            </div>
        </div>
    <?php endforeach; ?>

        </div>

        <div style="text-align:center; margin-top:30px;">
            <a href="celebrities.php" class="back">返回明星清單</a>
        </div>

    </div>
</div>

</body>
</html>